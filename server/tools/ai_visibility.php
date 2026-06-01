<?php
declare(strict_types=1);

/**
 * ai_visibility — returns the project's recorded AI citation snapshots:
 * which of your tracked topics ChatGPT / Claude / Perplexity / Google AI
 * Overviews have cited in their search panel.
 *
 * Requires X-API-Key. Data comes from Ranki.io's TopicRadarService which
 * periodically captures SERP snapshots and flags AI citations.
 */
return function (array $args, string $apiKey): array {
    $projectId = (string) ($args['project_id'] ?? '');
    if ($projectId === '') {
        throw new \RuntimeException("project_id is required. Call `list_projects` first.");
    }

    $params = ['per_page='.max(1, min(100, (int) ($args['per_page'] ?? 50)))];
    if (! empty($args['cited_only'])) {
        $params[] = 'cited_only=1';
    }
    if (! empty($args['since'])) {
        $params[] = 'since='.rawurlencode((string) $args['since']);
    }

    $endpoint = '/api/v1/projects/'.rawurlencode($projectId).'/ai-visibility?'.implode('&', $params);
    $resp = rk_mcp_api_call($endpoint, $apiKey);

    $rows = $resp['data'] ?? [];
    $meta = $resp['meta'] ?? [];
    $summary = $meta['summary'] ?? [];

    $cited = (int) ($summary['cited'] ?? 0);
    $total = (int) ($summary['total'] ?? 0);
    $pct = $total > 0 ? round($cited / $total * 100, 1) : 0;

    $out = "AI VISIBILITY — project {$projectId}\n";
    $out .= 'Since '.($meta['since'] ?? 'unknown')."\n\n";

    $out .= "Summary\n";
    $out .= "  Snapshots:        {$total}\n";
    $out .= "  AI-cited:         {$cited} ({$pct}%)\n\n";

    if ($total === 0) {
        $out .= "No snapshots in this window. Ranki.io captures these periodically — check the project's Topic Radar at https://app.ranki.io/projects/{$projectId}/topics.\n";

        return rk_mcp_text_content($out);
    }

    $out .= "Recent snapshots\n";
    foreach ($rows as $r) {
        $citedMark = ! empty($r['ai_cited']) ? '★ AI-CITED' : '         ';
        $out .= sprintf(
            "  %s  %-55s  pos=%-5s  src=%-5s  %s\n",
            $citedMark,
            substr((string) ($r['keyword'] ?? ''), 0, 55),
            $r['position'] !== null ? sprintf('%5.1f', (float) $r['position']) : '  n/a',
            (string) ($r['source'] ?? '?'),
            (string) ($r['captured_at'] ?? ''),
        );
    }

    if (($meta['current_page'] ?? 1) < ($meta['last_page'] ?? 1)) {
        $out .= "\nMore pages available — call again with the next page.\n";
    }

    $out .= "\nNext steps:\n";
    $out .= "  1. Focus content updates on the keywords that are NOT yet AI-cited but ARE ranking top 10 in classic SERPs — those are the easiest to push into AI Overviews.\n";
    $out .= "  2. Add FAQPage schema + definitional intros on the pages targeting those keywords (call `audit_aeo` against each).\n";

    return rk_mcp_text_content($out);
};
