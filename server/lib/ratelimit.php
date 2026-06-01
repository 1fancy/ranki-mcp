<?php
declare(strict_types=1);

/**
 * File-based per-IP rate limit for unauthenticated MCP advisor calls.
 * 5 calls per IP per UTC day. Files stored under /tmp/ranki-mcp-rl/.
 * Simple, no DB roundtrip, fast enough for our scale.
 */

function rk_mcp_allow_ip(string $ip): bool
{
    $dir = sys_get_temp_dir().'/ranki-mcp-rl';
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $day = gmdate('Y-m-d');
    $file = $dir.'/'.sha1($ip.'|'.$day);

    $count = is_file($file) ? (int) file_get_contents($file) : 0;
    if ($count >= 5) {
        return false;
    }

    @file_put_contents($file, (string) ($count + 1));

    return true;
}
