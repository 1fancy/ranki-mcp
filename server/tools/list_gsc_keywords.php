<?php
declare(strict_types=1);

/**
 * list_gsc_keywords — paginated GSC keyword list (current 28-day window).
 *
 * Requires X-API-Key. Use for "show me every keyword I rank for" or
 * "filter to ones with impressions > 50". list_rank_tracking gives the
 * summary; this gives the full list.
 */
return function (array $args, string $apiKey): array {
    $projectId = (string) ($args['project_id'] ?? '');
    if ($projectId === '') {
        throw new \RuntimeException("project_id is required. Call `list_projects` to find your project's nano_id.");
    }

    $params = [];
    if (isset($args['sort']) && in_array((string) $args['sort'], ['clicks', 'impressions', 'position', 'ctr'], true)) {
        $params[] = 'sort='.rawurlencode((string) $args['sort']);
    }
    if (isset($args['dir']) && in_array((string) $args['dir'], ['asc', 'desc'], true)) {
        $params[] = 'dir='.rawurlencode((string) $args['dir']);
    }
    if (isset($args['min_impressions'])) {
        $params[] = 'min_impressions='.(int) $args['min_impressions'];
    }
    $perPage = max(1, min(100, (int) ($args['per_page'] ?? 50)));
    $params[] = 'per_page='.$perPage;

    $endpoint = '/api/v1/projects/'.rawurlencode($projectId).'/keywords?'.implode('&', $params);
    $resp = rk_mcp_api_call($endpoint, $apiKey);

    $rows = $resp['data'] ?? [];
    $meta = $resp['meta'] ?? [];

    if (empty($meta['gsc_connected'])) {
        return rk_mcp_text_content(
            "Project {$projectId} doesn't have Google Search Console connected.\n".
            "Connect at https://app.ranki.io/projects/{$projectId}/gsc and resync, then try again."
        );
    }

    if (empty($rows)) {
        return rk_mcp_text_content(
            "No keywords returned for project {$projectId}.\n".
            (empty($meta['last_synced_at']) ? "GSC has never been synced for this project.\n" : "Last sync: {$meta['last_synced_at']}.\n").
            "Trigger a sync at https://app.ranki.io/projects/{$projectId}/gsc."
        );
    }

    $out = "GSC KEYWORDS — project {$projectId}\n";
    $out .= 'Page '.((int) ($meta['current_page'] ?? 1)).' of '.((int) ($meta['last_page'] ?? 1)).' · ';
    $out .= ((int) ($meta['total'] ?? 0)).' keywords total · last synced '.($meta['last_synced_at'] ?? 'unknown')."\n\n";

    foreach ($rows as $k) {
        $out .= sprintf(
            "  %-55s clicks=%-5d impr=%-7d ctr=%5.2f%% pos=%5.1f\n",
            substr((string) ($k['keyword'] ?? ''), 0, 55),
            (int) ($k['clicks'] ?? 0),
            (int) ($k['impressions'] ?? 0),
            (float) ($k['ctr'] ?? 0) * 100,
            (float) ($k['position'] ?? 0),
        );
    }

    if (($meta['current_page'] ?? 1) < ($meta['last_page'] ?? 1)) {
        $out .= "\nMore pages available — call again with per_page=".$perPage." and the next page.\n";
    }

    return rk_mcp_text_content($out);
};
