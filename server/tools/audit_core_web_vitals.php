<?php
declare(strict_types=1);

/**
 * audit_core_web_vitals — distilled CWV report from PageSpeed Insights
 * with per-metric fix recipes (e.g. "LCP element is hero.png at 2.4MB,
 * convert to WebP saves ~1.8MB → -1.1s LCP").
 *
 * Different from audit_speed in shape: that one is the firehose, this one
 * is the doctor's note — one paragraph per metric with the literal fix.
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
            return rk_mcp_text_content("PageSpeed Insights rate-limited the request (HTTP 429). The Ranki MCP is sharing an unauthenticated PSI quota — Google caps it tightly. Try again in 60 seconds, or set GOOGLE_PSI_API_KEY on the server to lift the cap to 25,000 calls/day.");
        }

        return rk_mcp_text_content("Couldn't fetch Core Web Vitals for {$url} (HTTP {$code}). The URL must be public and reachable from the open internet.");
    }

    $data = json_decode((string) $body, true);
    $audits = $data['lighthouseResult']['audits'] ?? [];

    $lcpVal = $audits['largest-contentful-paint']['numericValue'] ?? null;
    $clsVal = $audits['cumulative-layout-shift']['numericValue'] ?? null;
    $inpVal = $audits['interaction-to-next-paint']['numericValue']
        ?? $audits['experimental-interaction-to-next-paint']['numericValue']
        ?? null;
    $lcpDisp = $audits['largest-contentful-paint']['displayValue'] ?? 'n/a';
    $clsDisp = $audits['cumulative-layout-shift']['displayValue'] ?? 'n/a';
    $inpDisp = $audits['interaction-to-next-paint']['displayValue']
        ?? $audits['experimental-interaction-to-next-paint']['displayValue']
        ?? 'n/a';

    $rateLcp = $lcpVal === null ? 'unknown' : ($lcpVal <= 2500 ? 'good' : ($lcpVal <= 4000 ? 'needs-improvement' : 'poor'));
    $rateCls = $clsVal === null ? 'unknown' : ($clsVal <= 0.1 ? 'good' : ($clsVal <= 0.25 ? 'needs-improvement' : 'poor'));
    $rateInp = $inpVal === null ? 'unknown' : ($inpVal <= 200 ? 'good' : ($inpVal <= 500 ? 'needs-improvement' : 'poor'));

    // LCP element details
    $lcpElement = $audits['largest-contentful-paint-element']['details']['items'][0]['items'][0]['node']['snippet'] ?? null;
    $lcpUrl = null;
    foreach (($audits['largest-contentful-paint-element']['details']['items'] ?? []) as $it) {
        foreach (($it['items'] ?? []) as $sub) {
            if (! empty($sub['url'])) {
                $lcpUrl = $sub['url'];
                break 2;
            }
        }
    }

    $out = "CORE WEB VITALS — {$url}\n";
    $out .= 'Strategy: '.$strategy."\n\n";

    $out .= "LCP — Largest Contentful Paint: {$lcpDisp} ({$rateLcp})\n";
    if ($lcpElement) {
        $out .= '  Largest element: '.substr($lcpElement, 0, 200)."\n";
    }
    if ($lcpUrl) {
        $out .= '  Resource URL:    '.$lcpUrl."\n";
    }
    $out .= match ($rateLcp) {
        'good' => "  Status: passing Google's threshold (<2.5s). Don't regress.\n",
        'needs-improvement' => "  Fix recipes:\n"
            .'    1. Preload the LCP image: <link rel="preload" as="image" href="'.($lcpUrl ?: '...').'" fetchpriority="high">'."\n"
            ."    2. Convert the LCP image to WebP / AVIF (call `optimize_images`).\n"
            ."    3. Move the LCP element above the fold in the markup.\n"
            ."    4. Inline critical CSS for the LCP element.\n",
        'poor' => "  Fix recipes (poor — fix urgently):\n"
            ."    1. Optimize the LCP resource itself — call `optimize_images` if it's an image, defer/split if it's JS-injected content.\n"
            .'    2. Preload it: <link rel="preload" as="image" href="'.($lcpUrl ?: '...').'" fetchpriority="high">'."\n"
            ."    3. Check TTFB — if your server response > 800ms, the rest of the budget is gone before LCP can paint.\n"
            ."    4. Remove render-blocking CSS / JS in the <head> (defer non-critical).\n",
        default => "  (LCP not measured.)\n",
    };
    $out .= "\n";

    $out .= "CLS — Cumulative Layout Shift: {$clsDisp} ({$rateCls})\n";
    $out .= match ($rateCls) {
        'good' => "  Status: passing Google's threshold (<0.1).\n",
        'needs-improvement', 'poor' => "  Fix recipes:\n"
            ."    1. Add explicit width and height attributes to every <img> tag.\n"
            ."    2. Reserve space for ads / iframes (use min-height or aspect-ratio).\n"
            ."    3. Avoid inserting content above existing content (e.g., late-loading banners).\n"
            ."    4. Preload web fonts and use font-display: optional to avoid layout shift when fonts swap.\n"
            ."    5. For carousels / sliders, set a fixed height container before the JS hydrates.\n",
        default => "  (CLS not measured.)\n",
    };
    $out .= "\n";

    $out .= "INP — Interaction to Next Paint: {$inpDisp} ({$rateInp})\n";
    $out .= match ($rateInp) {
        'good' => "  Status: passing Google's threshold (<200ms).\n",
        'needs-improvement', 'poor' => "  Fix recipes:\n"
            ."    1. Break long JavaScript tasks (>50ms) into smaller chunks (use setTimeout or scheduler.yield).\n"
            ."    2. Defer third-party scripts (analytics, chat widgets) — they often block the main thread.\n"
            ."    3. Replace heavy event handlers with passive listeners where possible.\n"
            ."    4. Move expensive work off the main thread with Web Workers.\n"
            ."    5. If using React: wrap expensive renders in React.memo / useMemo, avoid re-renders on every keystroke.\n",
        default => "  (INP not measured — needs real user interaction. Try a desktop strategy or open the URL manually first.)\n",
    };
    $out .= "\n";

    $out .= "Once changes ship, re-run `audit_core_web_vitals` to confirm metrics moved. Google rewards green CWV in mobile rankings.\n";

    return rk_mcp_text_content($out);
};
