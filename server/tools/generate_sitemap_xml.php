<?php
declare(strict_types=1);

return function (array $args, string $apiKey): array {
    $urls = $args['urls'] ?? [];
    $changefreq = (string) ($args['changefreq'] ?? 'weekly');

    if (! is_array($urls) || empty($urls)) {
        throw new \RuntimeException('urls (non-empty array of absolute URLs) is required');
    }

    $valid = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'];
    if (! in_array($changefreq, $valid, true)) {
        $changefreq = 'weekly';
    }

    $today = gmdate('Y-m-d');
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($urls as $u) {
        $u = trim((string) $u);
        if ($u === '' || ! filter_var($u, FILTER_VALIDATE_URL)) {
            continue;
        }
        $xml .= "  <url>\n";
        $xml .= '    <loc>'.htmlspecialchars($u, ENT_XML1, 'UTF-8')."</loc>\n";
        $xml .= "    <lastmod>{$today}</lastmod>\n";
        $xml .= "    <changefreq>{$changefreq}</changefreq>\n";
        $xml .= "    <priority>0.8</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>';

    return rk_mcp_text_content("Save the following as sitemap.xml at your site root, then submit to Google Search Console:\n\n```xml\n{$xml}\n```");
};
