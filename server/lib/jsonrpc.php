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
    // Behind Cloudflare → use CF-Connecting-IP; otherwise X-Forwarded-For; else REMOTE_ADDR.
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $h) {
        if (! empty($_SERVER[$h])) {
            $val = explode(',', (string) $_SERVER[$h])[0];

            return trim($val);
        }
    }

    return '0.0.0.0';
}

/**
 * Wrap a tool's plain-text/JSON output in the MCP content envelope expected
 * by clients. MCP tools return `{ content: [{ type: "text", text: "..." }] }`.
 */
function rk_mcp_text_content(string $text): array
{
    return ['content' => [['type' => 'text', 'text' => $text]]];
}
