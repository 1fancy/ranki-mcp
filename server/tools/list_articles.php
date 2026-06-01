<?php
declare(strict_types=1);

/**
 * list_articles — paginated index of articles in a Ranki.io project.
 *
 * Each row carries everything the calling agent needs to decide which
 * article to open in full: nano_id, project_id, title, status, language,
 * focus_keyword[], TOC outline (h2/h3 list), word count, SEO score,
 * published_at. The agent then calls `get_article(article_id=…)` for
 * the full HTML and image URLs.
 *
 * Optional filters: status (draft|review|scheduled|published|…),
 *                   per_page (1-100, default 25),
 *                   page.
 */
return function (array $args, string $apiKey): array {
    $projectId = trim((string) ($args['project_id'] ?? ''));
    if ($projectId === '') {
        throw new \RuntimeException(
            "project_id is required. Call `list_projects` first to find your project's nano_id."
        );
    }

    $params = [];
    $perPage = max(1, min(100, (int) ($args['per_page'] ?? 25)));
    $params[] = 'per_page='.$perPage;
    if (! empty($args['page'])) {
        $params[] = 'page='.(int) $args['page'];
    }
    if (! empty($args['status'])) {
        $status = preg_replace('/[^a-z_]/i', '', (string) $args['status']);
        if ($status !== '') {
            $params[] = 'status='.rawurlencode($status);
        }
    }

    $endpoint = '/api/v1/projects/'.rawurlencode($projectId).'/articles?'.implode('&', $params);
    $resp = rk_mcp_api_call($endpoint, $apiKey);

    $rows = $resp['data'] ?? [];
    $meta = $resp['meta'] ?? [];
    $total = (int) ($meta['total'] ?? count($rows));
    $page = (int) ($meta['current_page'] ?? 1);
    $lastPage = (int) ($meta['last_page'] ?? 1);

    if ($total === 0) {
        return rk_mcp_text_content(
            "No articles in project {$projectId} yet".
            (! empty($args['status']) ? " with status={$args['status']}" : '').
            ".\n\n".
            "Generate one at https://app.ranki.io/projects/{$projectId} or use the Ranki.io content pipeline."
        );
    }

    $out = "ARTICLES — project {$projectId}\n";
    $out .= "Page {$page} of {$lastPage} · {$total} articles total\n";
    if (! empty($args['status'])) {
        $out .= "Filter: status={$args['status']}\n";
    }
    $out .= str_repeat('-', 72)."\n\n";

    foreach ($rows as $a) {
        $nano = (string) ($a['id'] ?? '?');
        $title = (string) ($a['title'] ?? '(untitled)');
        $status = (string) ($a['status'] ?? '?');
        $lang = (string) ($a['language'] ?? '?');
        $words = (int) ($a['word_count'] ?? 0);
        $seo = $a['seo_score'] !== null ? (string) $a['seo_score'] : '—';
        $published = $a['published_at'] ?? null;
        $updated = $a['updated_at'] ?? null;

        $kws = $a['focus_keyword'] ?? [];
        $kwsLine = is_array($kws) && ! empty($kws) ? implode(', ', array_slice($kws, 0, 5)) : '(none)';

        $toc = $a['toc'] ?? [];
        $tocLine = '';
        if (is_array($toc) && ! empty($toc)) {
            $top = array_slice(array_filter($toc, fn ($h) => (int) ($h['level'] ?? 0) === 2), 0, 6);
            $tocLine = implode(' · ', array_map(fn ($h) => (string) ($h['text'] ?? ''), $top));
        }

        $out .= "[{$nano}]  {$title}\n";
        $out .= "  Status: {$status} · Lang: {$lang} · Words: {$words} · SEO: {$seo}\n";
        $out .= "  Keywords: {$kwsLine}\n";
        if ($tocLine !== '') {
            $out .= "  TOC (h2): {$tocLine}\n";
        }
        if ($published) {
            $out .= "  Published: {$published}\n";
        } elseif ($updated) {
            $out .= "  Updated:   {$updated}\n";
        }
        $out .= "\n";
    }

    if ($page < $lastPage) {
        $out .= str_repeat('-', 72)."\n";
        $out .= "More pages available. Call again with page=".($page + 1)." (per_page={$perPage}).\n";
    }

    $out .= "\nTo open one in full (with content_html, images, SEO checklist):\n";
    $out .= "  call get_article(article_id=\"<nano_id from above>\")\n";

    return rk_mcp_text_content($out);
};
