<?php
declare(strict_types=1);

/**
 * list_rank_tracking — fetches the user's Google Search Console keyword
 * data for a project. Returns top keywords (clicks), low-ranking
 * opportunities (position > 10 with impressions), and totals.
 *
 * Requires X-API-Key. The Ranki.io project must have GSC connected.
 */
return function (array $args, string $apiKey): array {
    $projectId = (string) ($args['project_id'] ?? '');
    if ($projectId === '') {
        throw new \RuntimeException("project_id is required. Call `list_projects` first to find your project's nano_id.");
    }

    $resp = rk_mcp_api_call('/api/v1/projects/'.rawurlencode($projectId).'/gsc', $apiKey);
    $data = $resp['data'] ?? [];

    if (empty($data['gsc_connected'])) {
        return rk_mcp_text_content(
            "Project {$projectId} doesn't have Google Search Console connected.\n\n".
            "Connect it at https://app.ranki.io/projects/{$projectId}/gsc (one OAuth click) and then re-run this tool.\n\n".
            "Without GSC, the only ranking data we can surface is the AI-citation snapshots from `list_rank_tracking_ai` (call that instead)."
        );
    }

    $totals = $data['totals'] ?? [];
    $top = $data['top_keywords'] ?? [];
    $opps = $data['opportunities'] ?? [];

    $out = "GOOGLE SEARCH CONSOLE — project {$projectId}\n";
    $out .= 'Property: '.($data['gsc_property'] ?? 'n/a')."\n";
    $out .= 'Last synced: '.($data['last_synced_at'] ?? 'never')."\n\n";

    $out .= "Totals (last 28 days)\n";
    $out .= '  Keywords tracked: '.((int) ($totals['keywords'] ?? 0))."\n";
    $out .= '  Total clicks:     '.((int) ($totals['clicks'] ?? 0))."\n";
    $out .= '  Total impressions:'.((int) ($totals['impressions'] ?? 0))."\n\n";

    if (! empty($top)) {
        $out .= "Top 20 keywords by clicks\n";
        foreach ($top as $k) {
            $out .= sprintf(
                "  %-50s clicks=%-5d impr=%-7d ctr=%5.2f%% pos=%5.1f\n",
                substr((string) ($k['keyword'] ?? ''), 0, 50),
                (int) ($k['clicks'] ?? 0),
                (int) ($k['impressions'] ?? 0),
                (float) ($k['ctr'] ?? 0) * 100,
                (float) ($k['position'] ?? 0),
            );
        }
        $out .= "\n";
    }

    if (! empty($opps)) {
        $out .= "Top 20 opportunities (position > 10, impressions ≥ 10 — easy wins if you optimize content)\n";
        foreach ($opps as $k) {
            $out .= sprintf(
                "  %-50s impr=%-6d pos=%5.1f ctr=%5.2f%%\n",
                substr((string) ($k['keyword'] ?? ''), 0, 50),
                (int) ($k['impressions'] ?? 0),
                (float) ($k['position'] ?? 0),
                (float) ($k['ctr'] ?? 0) * 100,
            );
        }
        $out .= "\n";
        $out .= "These are the keywords to write content for next — already-ranking but not yet on page 1.\n";
        $out .= "Pick the top 3 and call `find_topic_ideas` with each to plan articles.\n";
    }

    return rk_mcp_text_content($out);
};
