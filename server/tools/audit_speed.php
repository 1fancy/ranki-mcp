<?php
declare(strict_types=1);

/**
 * audit_speed — measures real Core Web Vitals + page-weight issues via
 * Google PageSpeed Insights API (free, 25k req/day server-wide). Returns
 * a prioritized fix list the calling AI can act on directly: oversized
 * images to convert, render-blocking scripts to defer, fonts to preload.
 *
 * Strategy can be "mobile" (default — Google ranks mobile-first) or
 * "desktop". Both call the same upstream endpoint with strategy switch.
 */
return function (array $args, string $apiKey): array {
    $url = trim((string) ($args['url'] ?? ''));
    if ($url === '') {
        throw new \RuntimeException('url is required');
    }
    if (! preg_match('~^https?://~i', $url)) {
        $url = 'https://'.$url;
    }
    $strategy = strtolower((string) ($args['strategy'] ?? 'mobile'));
    if (! in_array($strategy, ['mobile', 'desktop'], true)) {
        $strategy = 'mobile';
    }

    $psiKey = getenv('GOOGLE_PSI_API_KEY') ?: '';
    $endpoint = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed'
        .'?url='.urlencode($url)
        .'&strategy='.$strategy
        .'&category=PERFORMANCE'
        ."&category=ACCESSIBILITY"
        ."&category=SEO"
        ."&category=BEST_PRACTICES"
        .($psiKey !== '' ? '&key='.urlencode($psiKey) : '');

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; RankiMCP/0.3; +https://ranki.io/developers/mcp)',
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false || $code >= 400) {
        if ($code === 429) {
            return rk_mcp_text_content("PageSpeed Insights rate-limited the request (HTTP 429). The Ranki MCP server is sharing an unauthenticated PSI quota — Google caps it tightly. Try again in 60 seconds, or set GOOGLE_PSI_API_KEY on the server (free Google Cloud key) to lift the cap to 25,000 calls/day.");
        }
        if ($code === 403) {
            return rk_mcp_text_content("PageSpeed Insights rejected the request (HTTP 403). The server's GOOGLE_PSI_API_KEY is invalid or out of quota — ping support@ranki.io.");
        }
        if ($code === 400) {
            return rk_mcp_text_content("PageSpeed Insights couldn't analyze {$url} (HTTP 400). Either the URL isn't reachable from the open internet, it requires login, or Lighthouse failed to load the page. Try the public homepage instead.");
        }

        return rk_mcp_text_content("PageSpeed Insights returned HTTP {$code} for {$url}. If this keeps happening, ping support@ranki.io.");
    }

    $data = json_decode((string) $body, true);
    if (! is_array($data)) {
        return rk_mcp_text_content("PageSpeed Insights returned an unexpected response. Try again in a moment.");
    }

    $lhr = $data['lighthouseResult'] ?? [];
    $categories = $lhr['categories'] ?? [];
    $audits = $lhr['audits'] ?? [];

    $scoreLine = static function (array $cat, string $label): string {
        $raw = $cat['score'] ?? null;
        if ($raw === null) {
            return "  {$label}: n/a";
        }
        $score = (int) round(((float) $raw) * 100);

        return "  {$label}: {$score}/100";
    };

    $performance = $categories['performance'] ?? [];
    $accessibility = $categories['accessibility'] ?? [];
    $seoCat = $categories['seo'] ?? [];
    $best = $categories['best-practices'] ?? [];

    // Core Web Vitals
    $lcp = $audits['largest-contentful-paint']['displayValue'] ?? 'n/a';
    $cls = $audits['cumulative-layout-shift']['displayValue'] ?? 'n/a';
    $inp = $audits['interaction-to-next-paint']['displayValue']
        ?? $audits['experimental-interaction-to-next-paint']['displayValue']
        ?? 'n/a';
    $fcp = $audits['first-contentful-paint']['displayValue'] ?? 'n/a';
    $ttfb = $audits['server-response-time']['displayValue'] ?? 'n/a';

    // Image opportunities
    $imageIssues = [];
    foreach (['uses-optimized-images', 'modern-image-formats', 'uses-responsive-images', 'efficient-animated-content', 'offscreen-images'] as $auditId) {
        $a = $audits[$auditId] ?? null;
        if ($a === null) {
            continue;
        }
        $savingMs = $a['details']['overallSavingsMs'] ?? 0;
        $savingBytes = $a['details']['overallSavingsBytes'] ?? 0;
        if ($savingMs < 100 && $savingBytes < 10240) {
            continue;
        }
        $items = $a['details']['items'] ?? [];
        $itemsOut = [];
        foreach (array_slice($items, 0, 5) as $it) {
            $u = $it['url'] ?? '';
            if ($u === '') {
                continue;
            }
            $itemsOut[] = '    - '.$u
                .(isset($it['totalBytes']) ? sprintf(' (%.1f KB)', $it['totalBytes'] / 1024) : '')
                .(isset($it['wastedBytes']) ? sprintf(' → save %.1f KB', $it['wastedBytes'] / 1024) : '');
        }
        $imageIssues[] = '  · '.$a['title'].' — '.($a['description'] ?? '')."\n".implode("\n", $itemsOut);
    }

    // Render-blocking / unused
    $blockingIssues = [];
    foreach (['render-blocking-resources', 'unused-css-rules', 'unused-javascript', 'unminified-css', 'unminified-javascript'] as $auditId) {
        $a = $audits[$auditId] ?? null;
        if ($a === null || (($a['score'] ?? 1) >= 0.9)) {
            continue;
        }
        $blockingIssues[] = '  · '.$a['title'].' — '.($a['displayValue'] ?? '');
    }

    // Failing SEO audits
    $seoFails = [];
    foreach ($audits as $id => $a) {
        if (! is_array($a) || ! isset($a['score'])) {
            continue;
        }
        if ($a['score'] === 0 || $a['score'] === 0.0 || $a['score'] === false) {
            if (in_array($id, ['document-title', 'meta-description', 'image-alt', 'robots-txt', 'canonical', 'hreflang', 'is-crawlable', 'tap-targets', 'viewport', 'http-status-code'], true)) {
                $seoFails[] = '  · '.$a['title'];
            }
        }
    }

    $out = "PAGESPEED INSIGHTS — {$url}\n";
    $out .= 'Strategy: '.$strategy."\n\n";

    $out .= "Lighthouse scores\n";
    $out .= $scoreLine($performance, 'Performance ')."\n";
    $out .= $scoreLine($accessibility, 'Accessibility')."\n";
    $out .= $scoreLine($best, 'Best Practices')."\n";
    $out .= $scoreLine($seoCat, 'SEO         ')."\n\n";

    $out .= "Core Web Vitals\n";
    $out .= "  LCP (Largest Contentful Paint):  {$lcp}\n";
    $out .= "  CLS (Cumulative Layout Shift):   {$cls}\n";
    $out .= "  INP (Interaction to Next Paint): {$inp}\n";
    $out .= "  FCP (First Contentful Paint):    {$fcp}\n";
    $out .= "  TTFB (Server response):          {$ttfb}\n\n";

    if (! empty($imageIssues)) {
        $out .= "Image opportunities (call `optimize_images` next, then convert the files)\n";
        $out .= implode("\n", $imageIssues)."\n\n";
    }

    if (! empty($blockingIssues)) {
        $out .= "JS / CSS issues\n";
        $out .= implode("\n", $blockingIssues)."\n\n";
    }

    if (! empty($seoFails)) {
        $out .= "On-page SEO failures (call `audit_seo` for fix recipes)\n";
        $out .= implode("\n", $seoFails)."\n\n";
    }

    $out .= "Next steps for the agent:\n";
    $out .= "  1. If image opportunities above, call `optimize_images` with the URLs to get target format + dims + alt + <picture> markup, then convert the actual files in the repo with `sharp` (Node) or `cwebp` (CLI).\n";
    $out .= "  2. If LCP > 2.5s and the largest element is an image, preload it: <link rel=\"preload\" as=\"image\" href=\"...\" fetchpriority=\"high\">.\n";
    $out .= "  3. If CLS > 0.1, add explicit width/height attributes to every <img> and reserve space for ads / late-loaded content.\n";
    $out .= "  4. If render-blocking JS, defer non-critical scripts (`<script defer>` or `<script async>`).\n";
    $out .= "  5. Re-run `audit_speed` after deploy to confirm scores moved.\n";

    return rk_mcp_text_content($out);
};
