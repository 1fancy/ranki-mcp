<?php
/**
 * Ranki.io MCP server — HTTP/JSON-RPC 2.0 endpoint.
 *
 * Hosted at mcp.ranki.io. Vibe-coders point Claude Desktop / Cursor / Code
 * here (directly, or via the `@ranki/mcp` npx shim). The server returns
 * SEO + AEO advice and structure (sitemap.xml, llms.txt, robots.txt) so
 * the user's own Claude can evaluate the advice against their code.
 *
 * Most tools are advisor-only and require NO key — they're rate-limited
 * per IP (5/day for free, unlimited with X-API-Key against app.ranki.io).
 * Two tools (list_projects, get_article) require a key because they fetch
 * the caller's private Ranki.io data via the REST API at app.ranki.io.
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');

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

$raw = file_get_contents('php://input') ?: '';
$req = json_decode($raw, true);
if (! is_array($req) || ($req['jsonrpc'] ?? null) !== '2.0' || ! isset($req['method'])) {
    rk_mcp_reply_error(null, -32600, 'Invalid Request');
}

$method = (string) $req['method'];
$params = $req['params'] ?? [];
$id = $req['id'] ?? null;

try {
    switch ($method) {
        case 'initialize':
            rk_mcp_reply($id, [
                'protocolVersion' => '2024-11-05',
                'capabilities' => ['tools' => new \stdClass],
                'serverInfo' => ['name' => 'ranki', 'version' => '0.1.0'],
            ]);
            break;

        case 'tools/list':
            rk_mcp_reply($id, ['tools' => rk_mcp_tool_definitions()]);
            break;

        case 'tools/call':
            $toolName = $params['name'] ?? null;
            $args = $params['arguments'] ?? [];
            if (! is_string($toolName) || $toolName === '') {
                rk_mcp_reply_error($id, -32602, 'Tool name required');
            }

            // Per-IP rate-limit for advisor tools. Keyed tools (list_projects,
            // get_article) bypass this because the X-API-Key is its own
            // quota / billing signal.
            $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
            if (! rk_mcp_is_keyed_tool($toolName) && $apiKey === '') {
                if (! rk_mcp_allow_ip(rk_mcp_client_ip())) {
                    rk_mcp_reply_error($id, -32000, 'Free tier: 5 calls/day per IP. Set X-API-Key for unlimited (generate at https://app.ranki.io/profile#developer).');
                }
            }

            $result = rk_mcp_call_tool($toolName, is_array($args) ? $args : [], $apiKey);
            rk_mcp_reply($id, $result);
            break;

        case 'ping':
            rk_mcp_reply($id, []);
            break;

        default:
            rk_mcp_reply_error($id, -32601, "Method not found: {$method}");
    }
} catch (\Throwable $e) {
    error_log('[ranki-mcp] '.$e->getMessage());
    rk_mcp_reply_error($id, -32603, 'Internal error: '.$e->getMessage());
}
