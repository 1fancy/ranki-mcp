<?php
declare(strict_types=1);

/**
 * Per-IP and per-key rate limits for MCP tool calls.
 *
 * Tier               Daily cap        Scope
 * ----               ---------        -----
 * Free (no key)      5 calls          IP address
 * Paid (with key)    500 calls        API key (rk_live_...)
 *
 * Counters live in /tmp/ranki-mcp-rl/ as plain files (sha1(scope|day)).
 * Simple, no DB roundtrip, fast enough for our scale.
 */

const RK_MCP_FREE_LIMIT = 5;
const RK_MCP_KEYED_LIMIT = 500;

function rk_mcp_check_ip(string $ip, ?int $limitOverride = null): array
{
    return rk_mcp_check_scope('ip:'.$ip, $limitOverride ?? RK_MCP_FREE_LIMIT);
}

function rk_mcp_check_key(string $apiKey): array
{
    // Hash the key so the bucket filename doesn't leak the plaintext key
    // to anyone with shell access to /tmp.
    return rk_mcp_check_scope('key:'.hash('sha256', $apiKey), RK_MCP_KEYED_LIMIT);
}

/**
 * Atomic read-modify-write of the daily counter. flock() prevents the
 * race where two concurrent requests both read N, both write N+1, and
 * the cap leaks. fopen('c+') creates the file if missing and opens for
 * read+write (which flock requires).
 *
 * @return array{allowed: bool, used: int, limit: int, reset_at: string, seconds_until_reset: int}
 */
function rk_mcp_check_scope(string $scope, int $limit): array
{
    $dir = sys_get_temp_dir().'/ranki-mcp-rl';
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $day = gmdate('Y-m-d');
    $file = $dir.'/'.sha1($scope.'|'.$day);

    $count = 0;
    $allowed = false;
    $fh = @fopen($file, 'c+');
    if ($fh !== false) {
        if (flock($fh, LOCK_EX)) {
            $existing = stream_get_contents($fh);
            $count = is_string($existing) && $existing !== '' ? (int) $existing : 0;
            $allowed = $count < $limit;
            if ($allowed) {
                $count++;
                ftruncate($fh, 0);
                rewind($fh);
                fwrite($fh, (string) $count);
                fflush($fh);
            }
            flock($fh, LOCK_UN);
        }
        fclose($fh);
    }

    $tomorrow = gmmktime(0, 0, 0, (int) gmdate('n'), (int) gmdate('j') + 1, (int) gmdate('Y'));

    return [
        'allowed' => $allowed,
        'used' => $count,
        'limit' => $limit,
        'reset_at' => gmdate('Y-m-d\TH:i:s\Z', $tomorrow),
        'seconds_until_reset' => max(0, $tomorrow - time()),
    ];
}

/** Back-compat for callers that only need the boolean. */
function rk_mcp_allow_ip(string $ip): bool
{
    return rk_mcp_check_ip($ip)['allowed'];
}

function rk_mcp_format_reset(int $seconds): string
{
    if ($seconds < 60) {
        return $seconds.'s';
    }
    $h = intdiv($seconds, 3600);
    $m = intdiv($seconds % 3600, 60);
    if ($h === 0) {
        return $m.'m';
    }

    return $h.'h '.$m.'m';
}
