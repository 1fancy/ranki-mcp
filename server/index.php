<?php
/**
 * Ranki.io MCP server — HTTP/JSON-RPC 2.0 endpoint.
 *
 * Hosted at mcp.ranki.io. Clients (Claude Code, Cursor, Windsurf, ChatGPT
 * Desktop, etc.) POST JSON-RPC 2.0 messages here. Eight advisor tools are
 * free (rate-limited 5/IP/day); two bridge tools (list_projects,
 * get_article) plus the whoami tool (get_account) require an X-API-Key
 * from app.ranki.io.
 *
 * Error messages are written human-first — every failure includes what
 * went wrong, why it matters, and the exact next step the user can take.
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Expose-Headers: X-RateLimit-Limit, X-RateLimit-Remaining, X-RateLimit-Reset');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'method_not_allowed', 'message' => 'POST JSON-RPC only']);
    exit;
}

require __DIR__.'/lib/jsonrpc.php';
require __DIR__.'/lib/registry.php';
require __DIR__.'/lib/ratelimit.php';

// Reject oversized bodies early — JSON-RPC requests are tiny (a few KB at
// most). 64 KB is plenty for the largest tool call (e.g., audit_hidden_pages
// with a long URL list).
$declaredLen = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
$maxBody = 65536;
if ($declaredLen > $maxBody) {
    http_response_code(413);
    echo json_encode(['error' => 'payload_too_large', 'message' => "Request body must be ≤{$maxBody} bytes."]);
    exit;
}

$raw = file_get_contents('php://input', false, null, 0, $maxBody + 1) ?: '';
if (strlen($raw) > $maxBody) {
    http_response_code(413);
    echo json_encode(['error' => 'payload_too_large', 'message' => "Request body must be ≤{$maxBody} bytes."]);
    exit;
}

// Cap JSON depth to defend against deeply-nested DoS payloads. 16 levels
// is well above what any legitimate MCP request needs.
try {
    $req = json_decode($raw, true, 16, JSON_THROW_ON_ERROR);
} catch (\JsonException $e) {
    rk_mcp_reply_error(null, -32700, 'Parse error: '.$e->getMessage());
}
if (! is_array($req) || ($req['jsonrpc'] ?? null) !== '2.0' || ! isset($req['method'])) {
    rk_mcp_reply_error(null, -32600, 'Invalid Request — expected a JSON-RPC 2.0 body with "method" and "jsonrpc": "2.0".');
}

$method = (string) $req['method'];
$params = $req['params'] ?? [];
$id = $req['id'] ?? null;
$apiKey = trim((string) ($_SERVER['HTTP_X_API_KEY'] ?? ''));

try {
    switch ($method) {
        case 'initialize':
            rk_mcp_reply($id, [
                'protocolVersion' => '2024-11-05',
                'capabilities' => ['tools' => new \stdClass],
                'serverInfo' => ['name' => 'ranki', 'version' => '0.2.0'],
            ]);
            break;

        case 'tools/list':
            rk_mcp_reply($id, ['tools' => rk_mcp_tool_definitions()]);
            break;

        case 'tools/call':
            $toolName = $params['name'] ?? null;
            $args = $params['arguments'] ?? [];
            if (! is_string($toolName) || $toolName === '') {
                rk_mcp_reply_error($id, -32602, 'Missing tool name. Call `tools/list` to see all available tools.');
            }
            // Defence-in-depth — the registry lookup below is the real gate,
            // but reject anything that doesn't match the tool-name shape
            // before we touch the filesystem at all.
            if (! preg_match('/^[a-z][a-z0-9_]{0,63}$/', $toolName)) {
                rk_mcp_reply_error($id, -32602, "Invalid tool name: {$toolName}");
            }

            // Keyed tools — verify a key exists before spending an upstream
            // round-trip.
            if (rk_mcp_is_keyed_tool($toolName) && $apiKey === '') {
                rk_mcp_reply_error(
                    $id,
                    -32001,
                    sprintf(
                        "This tool (`%s`) reads your private Ranki.io data, so it needs your API key.\n\n".
                        "1. Generate one at https://app.ranki.io/developer (free, 30 seconds).\n".
                        "2. Add it to your MCP client config under env.RANKI_API_KEY (stdio) or headers.X-API-Key (HTTP).\n".
                        "3. Restart the client and retry this tool.",
                        $toolName
                    )
                );
            }

            // Rate-limit: free tier (no key) = 5/IP/day; keyed tier =
            // 500/key/day. We ALSO enforce a per-IP ceiling on keyed
            // callers so a single bad actor can't burn quota then DoS the
            // server from one IP. Counters are independent.
            $ip = rk_mcp_client_ip();
            $ipRl = rk_mcp_check_ip($ip, $apiKey !== '' ? 100 : null);
            if ($apiKey === '') {
                $rl = $ipRl;
            } else {
                $rl = rk_mcp_check_key($apiKey);
                // Surface the tighter of the two limits in headers so MCP
                // clients see what's actually capping them.
                if (! $ipRl['allowed']) {
                    $rl = $ipRl;
                }
            }
            header('X-RateLimit-Limit: '.$rl['limit']);
            header('X-RateLimit-Remaining: '.max(0, $rl['limit'] - $rl['used']));
            header('X-RateLimit-Reset: '.$rl['reset_at']);

            if (! $rl['allowed']) {
                $reset = rk_mcp_format_reset($rl['seconds_until_reset']);
                if ($apiKey === '') {
                    rk_mcp_reply_error(
                        $id,
                        -32000,
                        "You've reached the free daily quota: 5 of 5 calls used from your IP.\n\n".
                        "Quota resets in {$reset} (at {$rl['reset_at']}).\n\n".
                        "For 500 calls per day on the same tools, grab a free Ranki.io API key (no credit card):\n".
                        "  https://app.ranki.io/developer\n\n".
                        "An API key also unlocks the bridge tools: list_projects, get_article, get_account."
                    );
                } else {
                    rk_mcp_reply_error(
                        $id,
                        -32000,
                        "Daily quota reached on this API key: 500 of 500 calls used today.\n\n".
                        "Quota resets in {$reset} (at {$rl['reset_at']}).\n\n".
                        "Running a real workload that needs more? Email support@ranki.io with your use case; we lift caps for legit agency and power-user pipelines."
                    );
                }
            }

            // Dispatch — bridge tools may still 401 if the key is invalid.
            // rk_mcp_api_call throws \RuntimeException with a friendly
            // message; the catch below turns it into a JSON-RPC error.
            $result = rk_mcp_call_tool($toolName, is_array($args) ? $args : [], $apiKey);
            rk_mcp_reply($id, $result);
            break;

        case 'ping':
            rk_mcp_reply($id, []);
            break;

        default:
            rk_mcp_reply_error($id, -32601, "Method not found: {$method}. Supported: initialize, tools/list, tools/call, ping.");
    }
} catch (\Throwable $e) {
    error_log('[ranki-mcp] '.$e->getMessage());
    rk_mcp_reply_error($id, -32603, $e->getMessage());
}
