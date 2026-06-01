<?php
declare(strict_types=1);

/**
 * audit_seo — on-page SEO scorecard. Title, description, H1 uniqueness,
 * canonical, image alt coverage, OpenGraph, viewport, HTTPS, internal
 * links, JSON-LD presence.
 */
return function (array $args, string $apiKey): array {
    $url = (string) ($args['url'] ?? '');
    if ($url === '') {
        throw new \RuntimeException('url is required');
    }

    $fetched = rk_mcp_fetch_url($url);
    if ($fetched['status'] >= 400) {
        return rk_mcp_text_content("Could not fetch {$url} (HTTP {$fetched['status']}).");
    }

    $html = $fetched['html'];
    $checks = [];

    // Title
    preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $tm);
    $title = trim($tm[1] ?? '');
    $titleLen = mb_strlen($title);
    $titleOk = $titleLen >= 30 && $titleLen <= 70;
    $checks[] = [
        'name' => "Title length (30-70 chars; current: {$titleLen})",
        'pass' => $titleOk,
        'fix' => $titleOk ? null : ($titleLen < 30 ? 'Title is too short — Google may rewrite it. Aim for 50-65 chars including primary keyword.' : 'Title is too long — Google truncates around 60 chars. Front-load the keyword.'),
    ];

    // Meta description
    preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $dm);
    $desc = trim($dm[1] ?? '');
    $descLen = mb_strlen($desc);
    $descOk = $descLen >= 70 && $descLen <= 165;
    $checks[] = [
        'name' => "Meta description (70-165 chars; current: {$descLen})",
        'pass' => $descOk,
        'fix' => $descOk ? null : ($descLen === 0 ? 'No <meta name="description"> found. Add one — Google may use it as the SERP snippet.' : 'Description out of range. Aim for 140-160 chars, include the primary keyword in the first 120.'),
    ];

    // H1 count
    preg_match_all('/<h1[\s>]/i', $html, $h1m);
    $h1Count = count($h1m[0] ?? []);
    $h1Ok = $h1Count === 1;
    $checks[] = [
        'name' => "H1 count (exactly 1; current: {$h1Count})",
        'pass' => $h1Ok,
        'fix' => $h1Ok ? null : ($h1Count === 0 ? 'No <h1> on page. Add ONE h1 with the primary keyword.' : 'Multiple H1s. Demote extras to h2.'),
    ];

    // Canonical
    $hasCanonical = (bool) preg_match('/<link[^>]+rel=["\']canonical["\']/i', $html);
    $checks[] = [
        'name' => 'Canonical link',
        'pass' => $hasCanonical,
        'fix' => $hasCanonical ? null : 'Add <link rel="canonical" href="…"> in <head>. Prevents duplicate-content penalties.',
    ];

    // Viewport
    $hasViewport = (bool) preg_match('/<meta[^>]+name=["\']viewport["\']/i', $html);
    $checks[] = [
        'name' => 'Mobile viewport',
        'pass' => $hasViewport,
        'fix' => $hasViewport ? null : 'Add <meta name="viewport" content="width=device-width, initial-scale=1">. Required for mobile-first indexing.',
    ];

    // HTTPS
    $isHttps = str_starts_with($fetched['final_url'], 'https://');
    $checks[] = [
        'name' => 'HTTPS',
        'pass' => $isHttps,
        'fix' => $isHttps ? null : 'Serve over HTTPS. Hard ranking factor.',
    ];

    // OpenGraph
    $hasOg = (bool) preg_match('/<meta[^>]+property=["\']og:title["\']/i', $html);
    $checks[] = [
        'name' => 'OpenGraph og:title',
        'pass' => $hasOg,
        'fix' => $hasOg ? null : 'Add OG tags (og:title, og:description, og:image, og:url, og:type) so social shares render rich previews.',
    ];

    // Image alt coverage
    preg_match_all('/<img[^>]*>/i', $html, $imgMatches);
    $imgs = $imgMatches[0] ?? [];
    $imgsWithAlt = array_filter($imgs, fn ($i) => (bool) preg_match('/\salt=/i', $i));
    $altCoverage = count($imgs) > 0 ? (int) round(count($imgsWithAlt) / count($imgs) * 100) : 100;
    $altOk = $altCoverage >= 90;
    $checks[] = [
        'name' => "Image alt coverage ({$altCoverage}%)",
        'pass' => $altOk,
        'fix' => $altOk ? null : 'Add descriptive alt="…" to every <img>. Accessibility + image-search ranking.',
    ];

    // Internal links
    $host = parse_url($fetched['final_url'], PHP_URL_HOST);
    preg_match_all('/<a[^>]+href=["\']([^"\']+)["\']/i', $html, $linkMatches);
    $internal = 0;
    foreach ($linkMatches[1] ?? [] as $href) {
        if (str_starts_with($href, '/') || (parse_url($href, PHP_URL_HOST) === $host)) {
            $internal++;
        }
    }
    $linksOk = $internal >= 3;
    $checks[] = [
        'name' => "Internal links (≥3; current: {$internal})",
        'pass' => $linksOk,
        'fix' => $linksOk ? null : 'Add at least 3 contextual links to related pages on your site. Helps Google understand topic hubs.',
    ];

    // JSON-LD any
    $hasJsonLd = (bool) preg_match('/<script[^>]+type=["\']application\/ld\+json["\']/i', $html);
    $checks[] = [
        'name' => 'JSON-LD structured data',
        'pass' => $hasJsonLd,
        'fix' => $hasJsonLd ? null : 'Add at least one JSON-LD <script> block (Organization, WebSite, BreadcrumbList, Article…). Unlocks rich results.',
    ];

    $passed = count(array_filter($checks, fn ($c) => $c['pass']));
    $total = count($checks);
    $score = $total > 0 ? (int) round(($passed / $total) * 100) : 0;

    $report = "SEO Audit for {$url}\nScore: {$score}/100 ({$passed}/{$total} checks passed)\n\n";
    foreach ($checks as $c) {
        $report .= ($c['pass'] ? '✅' : '❌').' '.$c['name']."\n";
        if (! $c['pass'] && $c['fix']) {
            $report .= '   Fix: '.$c['fix']."\n";
        }
    }

    return rk_mcp_text_content($report);
};
