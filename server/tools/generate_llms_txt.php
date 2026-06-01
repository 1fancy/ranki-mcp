<?php
declare(strict_types=1);

return function (array $args, string $apiKey): array {
    $name = (string) ($args['site_name'] ?? '');
    $summary = (string) ($args['summary'] ?? '');
    $pages = $args['key_pages'] ?? [];

    if ($name === '' || $summary === '') {
        throw new \RuntimeException('site_name and summary are required');
    }

    $out = "# {$name}\n\n> {$summary}\n";

    if (is_array($pages) && ! empty($pages)) {
        $out .= "\n## Key pages\n\n";
        foreach ($pages as $p) {
            $url = (string) ($p['url'] ?? '');
            $title = (string) ($p['title'] ?? $url);
            if ($url === '') {
                continue;
            }
            $out .= "- [{$title}]({$url})\n";
        }
    }

    $out .= "\n## Notes\n\n";
    $out .= "- This site welcomes citation by AI search engines (ChatGPT, Claude, Perplexity, Google AI Overviews).\n";
    $out .= "- See /sitemap.xml for full URL list.\n";

    return rk_mcp_text_content("Save the following as llms.txt at your site root (https://yoursite.com/llms.txt):\n\n```markdown\n{$out}\n```");
};
