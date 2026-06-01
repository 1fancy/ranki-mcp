<?php
declare(strict_types=1);

/**
 * Minimal JSON-RPC 2.0 reply helpers. Both reply functions terminate
 * the request — callers do not need to `exit` themselves.
 */

function rk_mcp_reply(int|string|null $id, mixed $result): never
{
    echo json_encode([
        'jsonrpc' => '2.0',
        'id' => $id,
        'result' => $result,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function rk_mcp_reply_error(int|string|null $id, int $code, string $message, mixed $data = null): never
{
    $err = ['code' => $code, 'message' => $message];
    if ($data !== null) {
        $err['data'] = $data;
    }
    echo json_encode([
        'jsonrpc' => '2.0',
        'id' => $id,
        'error' => $err,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function rk_mcp_client_ip(): string
{
    // Only honour proxy-supplied IP headers when REMOTE_ADDR is actually a
    // trusted proxy. We trust Cloudflare's IPv4 ranges (kept short; current
    // ranges as of 2024-2026) plus any extra ranges from MCP_TRUSTED_PROXIES.
    //
    // Without this check, X-Forwarded-For is attacker-controlled and the
    // free-tier IP rate-limit (5/UTC-day) is trivially bypassable.
    static $cloudflareRanges = [
        '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
        '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20',
        '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
        '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
    ];

    $remote = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    $extra = getenv('MCP_TRUSTED_PROXIES') ?: '';
    $trusted = $extra !== '' ? array_merge($cloudflareRanges, array_map('trim', explode(',', $extra))) : $cloudflareRanges;
    $trustProxy = false;
    foreach ($trusted as $cidr) {
        if (rk_mcp_ip_in_cidr($remote, $cidr)) {
            $trustProxy = true;
            break;
        }
    }

    if ($trustProxy) {
        if (! empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $val = trim(explode(',', (string) $_SERVER['HTTP_CF_CONNECTING_IP'])[0]);
            if (filter_var($val, FILTER_VALIDATE_IP)) {
                return $val;
            }
        }
        // Fallback to first XFF entry only when behind a trusted proxy.
        if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $val = trim(explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
            if (filter_var($val, FILTER_VALIDATE_IP)) {
                return $val;
            }
        }
    }

    return filter_var($remote, FILTER_VALIDATE_IP) ? $remote : '0.0.0.0';
}

/**
 * Test whether an IP (v4 or v6) falls inside a CIDR. Single-IP CIDRs (no
 * slash) match literally. Returns false on any malformed input rather than
 * throwing — callers pre-filter.
 */
function rk_mcp_ip_in_cidr(string $ip, string $cidr): bool
{
    if (! filter_var($ip, FILTER_VALIDATE_IP)) {
        return false;
    }
    if (! str_contains($cidr, '/')) {
        return $ip === $cidr;
    }
    [$subnet, $bits] = explode('/', $cidr, 2);
    $bits = (int) $bits;
    $ipPacked = @inet_pton($ip);
    $subnetPacked = @inet_pton($subnet);
    if ($ipPacked === false || $subnetPacked === false || strlen($ipPacked) !== strlen($subnetPacked)) {
        return false;
    }
    $bytesFull = intdiv($bits, 8);
    $bitsRemainder = $bits % 8;
    if ($bytesFull > 0 && substr($ipPacked, 0, $bytesFull) !== substr($subnetPacked, 0, $bytesFull)) {
        return false;
    }
    if ($bitsRemainder === 0) {
        return true;
    }
    $mask = chr((0xff << (8 - $bitsRemainder)) & 0xff);
    return (substr($ipPacked, $bytesFull, 1) & $mask) === (substr($subnetPacked, $bytesFull, 1) & $mask);
}

/**
 * Wrap a tool's plain-text/JSON output in the MCP content envelope expected
 * by clients. MCP tools return `{ content: [{ type: "text", text: "..." }] }`.
 */
function rk_mcp_text_content(string $text): array
{
    return ['content' => [['type' => 'text', 'text' => $text]]];
}
