<?php
declare(strict_types=1);

/**
 * audit_hidden_pages — classify pages that should NOT be in Google's index.
 *
 * Two input modes:
 *   1. `urls` (array): user pastes their route list / public folder listing.
 *      No network calls — pure pattern matching.
 *   2. `domain` (string): we fetch /sitemap.xml + /robots.txt + crawl the
 *      homepage one level deep (cap 100 URLs), then classify what we found.
 *
 * Output: Markdown table per URL/path with recommendation
 * (keep · noindex · robots-disallow · unsure) + reasoning + the exact
 * snippet to add to robots.txt or as a meta tag.
 *
 * Token philosophy: zero LLM calls. Classification is rule-based — every
 * pattern is documented inline so the calling AI can explain it.
 */
return function (array $args, string $apiKey): array {
    $urls = $args['urls'] ?? [];
    $domain = trim((string) ($args['domain'] ?? ''));

    if (! is_array($urls)) {
        $urls = [];
    }
    $urls = array_values(array_filter(array_map('trim', $urls)));

    if (empty($urls) && $domain === '') {
        return rk_mcp_text_content(
            "## Need at least one input:\n\n".
            "- `urls`: array of paths or URLs to classify (no fetching, no limit)\n".
            "- `domain`: a site root to crawl 1 level deep (cap: 100 URLs)\n\n".
            "Examples:\n\n".
            "```json\n".
            '{ "urls": ["/admin", "/api/v1/*", "/preview/draft-123", "/login", "/about"] }'."\n".
            "```\n\n".
            "```json\n".
            '{ "domain": "https://example.com" }'."\n".
            "```"
        );
    }

    $out = "## Pages that should be hidden from search engines\n\n";

    // Collect everything we'll classify
    $items = [];

    foreach ($urls as $u) {
        $items[] = ['source' => 'user-provided', 'value' => $u];
    }

    if ($domain !== '') {
        $crawled = rk_mcp_light_crawl($domain);
        $out .= "**Crawled** `{$domain}` — found ".count($crawled['urls'])." URLs.";
        if ($crawled['notes']) {
            $out .= " Notes: ".implode(' · ', $crawled['notes']);
        }
        $out .= "\n\n";

        foreach ($crawled['urls'] as $u) {
            $items[] = ['source' => 'crawl', 'value' => $u];
        }
    }

    if (empty($items)) {
        return rk_mcp_text_content("Nothing to classify. Pass `urls` or `domain`.");
    }

    // Dedupe by value
    $seen = [];
    $unique = [];
    foreach ($items as $i) {
        $key = $i['value'];
        if (! isset($seen[$key])) {
            $seen[$key] = true;
            $unique[] = $i;
        }
    }

    // Classify
    $rows = [];
    $byVerdict = ['robots-disallow' => 0, 'noindex' => 0, 'keep' => 0, 'unsure' => 0];
    foreach ($unique as $i) {
        $c = rk_mcp_classify_path($i['value']);
        $byVerdict[$c['verdict']]++;
        $rows[] = ['url' => $i['value'], 'source' => $i['source']] + $c;
    }

    // Summary
    $out .= "**Summary:** ".count($unique)." paths classified — ";
    $parts = [];
    if ($byVerdict['robots-disallow']) {
        $parts[] = "**{$byVerdict['robots-disallow']}** robots-disallow";
    }
    if ($byVerdict['noindex']) {
        $parts[] = "**{$byVerdict['noindex']}** noindex";
    }
    if ($byVerdict['keep']) {
        $parts[] = "**{$byVerdict['keep']}** keep indexed";
    }
    if ($byVerdict['unsure']) {
        $parts[] = "**{$byVerdict['unsure']}** unsure (review)";
    }
    $out .= implode(' · ', $parts)."\n\n";

    // Table
    $out .= "| Path | Verdict | Why | How to apply |\n";
    $out .= "|---|---|---|---|\n";
    foreach ($rows as $r) {
        $verdict = match ($r['verdict']) {
            'robots-disallow' => '🚫 robots-disallow',
            'noindex' => '🔒 noindex',
            'keep' => '✓ keep',
            default => '❓ unsure',
        };
        $apply = $r['apply'] === '' ? '—' : '`'.str_replace('|', '\\|', $r['apply']).'`';
        $out .= "| `{$r['url']}` | {$verdict} | {$r['reason']} | {$apply} |\n";
    }
    $out .= "\n";

    // Aggregate robots.txt suggestion
    $disallows = array_values(array_filter($rows, fn ($r) => $r['verdict'] === 'robots-disallow'));
    if (! empty($disallows)) {
        $out .= "## Suggested `robots.txt` block\n\n";
        $out .= "Add this under `User-agent: *` in your `/robots.txt`:\n\n";
        $out .= "```\nUser-agent: *\n";
        foreach ($disallows as $d) {
            $path = preg_replace('#^https?://[^/]+#', '', $d['url']);
            $path = $path ?: '/';
            $out .= "Disallow: {$path}\n";
        }
        $out .= "```\n\n";
    }

    // Apply guidance
    $noindexes = array_values(array_filter($rows, fn ($r) => $r['verdict'] === 'noindex'));
    if (! empty($noindexes)) {
        $out .= "## Suggested `noindex` meta tags\n\n";
        $out .= "For each of these pages, add this inside `<head>`:\n\n";
        $out .= "```html\n<meta name=\"robots\" content=\"noindex, nofollow\">\n```\n\n";
        $out .= "**Why noindex (not robots-disallow)?** These pages may legitimately exist for users (login, password reset, thank-you pages) but shouldn't compete in search. `noindex` lets crawlers visit but tells them not to surface the page in SERPs. `robots-disallow` is stronger — used for paths that shouldn't be crawled at all.\n\n";
    }

    $out .= "## Rule of thumb\n\n";
    $out .= "- **robots-disallow** = path shouldn't be crawled (admin, API endpoints, draft folders, internal tooling)\n";
    $out .= "- **noindex** = page can be crawled but shouldn't show in search (login, signup, thank-you, account pages)\n";
    $out .= "- **keep** = real content for users + search engines\n";
    $out .= "- **unsure** = looks borderline — the calling AI should ask the user before recommending an action\n\n";
    $out .= "After applying, verify with `site:yourdomain.com [path]` in Google. Pages that still show up after 14 days haven't been re-crawled yet — request indexing in Search Console.";

    return rk_mcp_text_content($out);
};

/**
 * Light crawl: GET /sitemap.xml + /robots.txt + homepage. Extract URL list
 * (cap 100). No recursion — that's the user's AI's job if they want deeper.
 *
 * @return array{urls: array<int, string>, notes: array<int, string>}
 */
function rk_mcp_light_crawl(string $domain): array
{
    $domain = rtrim($domain, '/');
    if (! preg_match('#^https?://#', $domain)) {
        $domain = 'https://'.$domain;
    }

    $urls = [];
    $notes = [];

    // 1) sitemap.xml
    $sitemap = rk_mcp_fetch_url($domain.'/sitemap.xml', 6);
    if ($sitemap['status'] === 200 && str_contains($sitemap['html'], '<loc>')) {
        preg_match_all('/<loc>(.*?)<\/loc>/i', $sitemap['html'], $m);
        foreach ($m[1] as $u) {
            $urls[] = trim($u);
        }
        $notes[] = 'sitemap.xml '.count($m[1]).' URLs';
    } else {
        $notes[] = 'no sitemap.xml';
    }

    // 2) homepage <a href>
    $home = rk_mcp_fetch_url($domain.'/', 6);
    if ($home['status'] === 200) {
        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\']/i', $home['html'], $hm);
        foreach ($hm[1] as $href) {
            if (preg_match('/^(mailto:|tel:|javascript:|#)/i', $href)) {
                continue;
            }
            // Resolve relative
            if (str_starts_with($href, '/')) {
                $href = $domain.$href;
            } elseif (! preg_match('#^https?://#i', $href)) {
                $href = $domain.'/'.ltrim($href, '/');
            }
            // Only same-host
            if (parse_url($href, PHP_URL_HOST) === parse_url($domain, PHP_URL_HOST)) {
                $urls[] = $href;
            }
        }
        $notes[] = 'homepage parsed';
    }

    // Dedupe + cap
    $urls = array_values(array_unique($urls));
    if (count($urls) > 100) {
        $urls = array_slice($urls, 0, 100);
        $notes[] = 'capped at 100';
    }

    return ['urls' => $urls, 'notes' => $notes];
}

/**
 * Pattern-based classifier. No LLM. Rules are intentionally conservative —
 * when in doubt return "unsure" so the calling AI asks the user.
 *
 * @return array{verdict: string, reason: string, apply: string}
 */
function rk_mcp_classify_path(string $url): array
{
    // Extract path portion if a full URL was passed
    $path = preg_replace('#^https?://[^/]+#', '', $url);
    $path = $path ?: $url; // already a path
    $pathLower = strtolower($path);

    // ===== robots-disallow patterns =====
    // Admin / internal / tooling routes
    if (preg_match('#^/(admin|wp-admin|administrator|dashboard|cms|backend|panel|control-panel|cp)(/|$)#', $pathLower)) {
        return ['verdict' => 'robots-disallow', 'reason' => 'Admin / backend route', 'apply' => 'Disallow: '.rk_mcp_path_glob($path)];
    }

    // API endpoints
    if (preg_match('#^/(api|graphql|rest|webhooks?|hooks?|wp-json)(/|$)#', $pathLower)) {
        return ['verdict' => 'robots-disallow', 'reason' => 'API / webhook endpoint (JSON, not human-readable)', 'apply' => 'Disallow: '.rk_mcp_path_glob($path)];
    }

    // Preview / staging / draft
    if (preg_match('#/(preview|draft|drafts|staging|test|tests|sandbox|wip|tmp|temp)(/|$)#', $pathLower)) {
        return ['verdict' => 'robots-disallow', 'reason' => 'Preview / staging / draft content', 'apply' => 'Disallow: '.rk_mcp_path_glob($path)];
    }

    // Internal / private folders
    if (preg_match('#/(internal|private|hidden|_)#', $pathLower)) {
        return ['verdict' => 'robots-disallow', 'reason' => 'Marked internal / private', 'apply' => 'Disallow: '.rk_mcp_path_glob($path)];
    }

    // Build / source / config artifacts
    if (preg_match('#/(\.git|\.env|node_modules|vendor|\.next|\.nuxt|dist|build|\.cache)(/|$)#', $pathLower)) {
        return ['verdict' => 'robots-disallow', 'reason' => 'Build artifact / source folder — should never be web-accessible at all', 'apply' => 'Disallow: '.rk_mcp_path_glob($path)];
    }

    // Search results pages (often duplicate content)
    if (preg_match('#/(search|s\?)#', $pathLower) || preg_match('#[?&](q|query|s|search)=#', $pathLower)) {
        return ['verdict' => 'robots-disallow', 'reason' => 'Internal search results — Google flags as duplicate', 'apply' => 'Disallow: /*?*q='];
    }

    // Health / status / metrics
    if (preg_match('#^/(healthz?|status|metrics|ping|up|live|ready)$#', $pathLower)) {
        return ['verdict' => 'robots-disallow', 'reason' => 'Health-check / monitoring endpoint', 'apply' => 'Disallow: '.$path];
    }

    // ===== noindex patterns =====
    // Auth pages — exist for users but shouldn't compete
    if (preg_match('#^/(login|signin|sign-in|signup|sign-up|register|logout|reset-password|forgot-password|verify-email|onboarding|2fa|mfa)(/|$|\?)#', $pathLower)) {
        return ['verdict' => 'noindex', 'reason' => 'Auth flow — visitors need it, search engines don\'t', 'apply' => '<meta name="robots" content="noindex, nofollow">'];
    }

    // Account / user pages
    if (preg_match('#^/(account|profile|settings|billing|invoices?|subscription|user|me|my-)#', $pathLower)) {
        return ['verdict' => 'noindex', 'reason' => 'Authenticated user area', 'apply' => '<meta name="robots" content="noindex, nofollow">'];
    }

    // Thank-you / success / confirmation
    if (preg_match('#^/(thank-you|thanks|success|confirm|confirmation|order-confirmed|checkout-success)#', $pathLower)) {
        return ['verdict' => 'noindex', 'reason' => 'Post-conversion page — shouldn\'t rank in SERPs', 'apply' => '<meta name="robots" content="noindex, nofollow">'];
    }

    // 404 / error pages
    if (preg_match('#^/(404|500|403|error)#', $pathLower)) {
        return ['verdict' => 'noindex', 'reason' => 'Error page — must noindex (and return correct HTTP status)', 'apply' => '<meta name="robots" content="noindex, follow"> + ensure HTTP 404/500'];
    }

    // Print / amp / mobile variants (usually duplicate)
    if (preg_match('#/(print|amp)\b#', $pathLower) || str_ends_with($pathLower, '?print=1')) {
        return ['verdict' => 'noindex', 'reason' => 'Variant of a canonical page — duplicate content risk', 'apply' => 'Add <link rel="canonical"> to the main version + noindex this one'];
    }

    // Filter / tag / pagination params
    if (preg_match('#[?&](page|sort|filter|order|view|fbclid|gclid|utm_)#', $pathLower)) {
        return ['verdict' => 'noindex', 'reason' => 'Filter / UTM / tracking param URL — canonical to the clean URL', 'apply' => '<link rel="canonical" href="...clean-url..."> on the parent'];
    }

    // ===== keep patterns =====
    if (preg_match('#^/(blog|articles?|posts?|news|guides?|tutorials?|docs?|learn|resources?)(/|$)#', $pathLower)) {
        return ['verdict' => 'keep', 'reason' => 'Content directory — keep indexed', 'apply' => ''];
    }

    if (preg_match('#^/(about|company|team|contact|pricing|features|product|services?|home|index|customers?|testimonials?|case-stud)#', $pathLower)) {
        return ['verdict' => 'keep', 'reason' => 'Marketing / product page — keep indexed', 'apply' => ''];
    }

    if ($path === '/' || $path === '' || $pathLower === '/index.html' || $pathLower === '/index.php') {
        return ['verdict' => 'keep', 'reason' => 'Homepage — definitely keep', 'apply' => ''];
    }

    // OG image / asset endpoints
    if (preg_match('#\.(jpg|jpeg|png|webp|gif|svg|ico|woff2?|ttf|otf|eot)$#i', $pathLower)) {
        return ['verdict' => 'keep', 'reason' => 'Asset file — Google indexes images for image search', 'apply' => ''];
    }
    if (preg_match('#\.(css|js|map|json|xml|txt|pdf)$#i', $pathLower)) {
        // robots.txt, sitemap.xml, llms.txt are SEO-positive
        if (preg_match('#/(robots\.txt|sitemap.*\.xml|llms\.txt|humans\.txt)$#i', $pathLower)) {
            return ['verdict' => 'keep', 'reason' => 'SEO infrastructure file', 'apply' => ''];
        }

        return ['verdict' => 'unsure', 'reason' => 'Static asset — usually keep, but some sites disallow .json/.map for security', 'apply' => ''];
    }

    // Unknown / wildcard / numeric
    if (preg_match('#/\d+$#', $pathLower) || preg_match('#\*#', $pathLower)) {
        return ['verdict' => 'unsure', 'reason' => 'Numeric or wildcard path — ambiguous without knowing the route', 'apply' => ''];
    }

    return ['verdict' => 'unsure', 'reason' => 'No matching pattern — ask the user what this route serves before deciding', 'apply' => ''];
}

/**
 * Turn /api/v1/users/123 into /api/ when crafting a glob — broader robots
 * rule that covers the whole subtree.
 */
function rk_mcp_path_glob(string $path): string
{
    if ($path === '/' || $path === '') {
        return '/';
    }
    // Take the first segment, add trailing /
    // Use ~ as delimiter so the # inside the character class isn't misread
    if (preg_match('~^(/[^/?#]+)~', $path, $m)) {
        return $m[1].'/';
    }

    return $path;
}
