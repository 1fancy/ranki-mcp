<?php
declare(strict_types=1);

return function (array $args, string $apiKey): array {
    $id = trim((string) ($args['article_id'] ?? ''));
    if ($id === '') {
        throw new \RuntimeException('article_id (nano_id) is required');
    }

    $resp = rk_mcp_api_call("/api/v1/articles/{$id}", $apiKey);
    $a = $resp['data'] ?? null;
    if (! $a) {
        return rk_mcp_text_content("Article {$id} not found or you don't have access.");
    }

    $kw = is_array($a['focus_keyword'] ?? null) ? implode(', ', $a['focus_keyword']) : 'n/a';
    $tocLines = '';
    foreach ($a['toc'] ?? [] as $i => $t) {
        if (is_array($t)) {
            $tocLines .= '  '.str_repeat('  ', max(0, ($t['level'] ?? 2) - 2)).'- '.($t['text'] ?? '')."\n";
        } else {
            $tocLines .= '  - '.(string) $t."\n";
        }
    }
    $images = implode("\n  ", $a['images'] ?? []);

    $out = "Article: {$a['title']}\n";
    $out .= "Status: {$a['status']} · ".($a['word_count'] ?? 0)." words · SEO ".($a['seo_score'] ?? 'n/a')."/100\n";
    $out .= "Focus keywords: {$kw}\n\n";
    if ($tocLines) {
        $out .= "Outline:\n{$tocLines}\n";
    }
    if ($images !== '') {
        $out .= "Images:\n  {$images}\n\n";
    }
    if (! empty($a['content_html'])) {
        $out .= "HTML preview (first 800 chars):\n".mb_substr(strip_tags($a['content_html']), 0, 800)."…\n";
    }

    return rk_mcp_text_content($out);
};
