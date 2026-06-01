<?php
declare(strict_types=1);

/**
 * MCP tool registry. Each tool is a single file under tools/ that returns
 * a callable. Definitions (name, description, input schema) live here so
 * tools/list can render them without loading every tool file.
 *
 * Convention: tools that need a Ranki.io API key are listed in
 * RK_MCP_KEYED_TOOLS — the dispatcher enforces presence of X-API-Key for
 * those and skips IP rate-limiting (key has its own quota).
 */

const RK_MCP_KEYED_TOOLS = ['list_projects', 'get_article', 'get_account', 'list_rank_tracking', 'list_gsc_keywords', 'ai_visibility'];

function rk_mcp_is_keyed_tool(string $name): bool
{
    return in_array($name, RK_MCP_KEYED_TOOLS, true);
}

function rk_mcp_tool_definitions(): array
{
    return [
        [
            'name' => 'seo_starter_kit',
            'description' => 'For vibe-coders who just shipped a site and don\'t know what SEO files to add. Returns the exact contents of robots.txt, sitemap.xml, llms.txt, and JSON-LD structured data — plus a deployment checklist. The calling AI applies the files to the user\'s repo. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['domain' => ['type' => 'string', 'description' => 'The site you\'re optimizing, e.g. example.com']],
                'required' => ['domain'],
            ],
        ],
        [
            'name' => 'find_topic_ideas',
            'description' => 'For vibe-coders who don\'t know what to write about. Given a site URL, returns a structured brief telling your AI how to discover 15 article topics across informational / commercial / transactional intent, with prioritization criteria. The calling AI generates the actual topics. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['url' => ['type' => 'string', 'description' => 'Your site root, e.g. https://example.com']],
                'required' => ['url'],
            ],
        ],
        [
            'name' => 'find_keyword_gap',
            'description' => 'For vibe-coders who don\'t know which keywords competitors are stealing. Returns the methodology for keyword-gap analysis — the calling AI walks the user through it. If no competitors given, instructs the AI to ask the user first. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'url' => ['type' => 'string', 'description' => 'Your site root'],
                    'competitors' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => 'Optional: 1-5 competitor URLs the user provided'],
                ],
                'required' => ['url'],
            ],
        ],
        [
            'name' => 'explain_seo_terms',
            'description' => "Plain-English glossary of 40+ SEO + AEO terms — what every vibe-coder runs into reading an SEO audit. Returns the jargon (SEO, AEO, GEO, FAQPage, JSON-LD, llms.txt, canonical, E-E-A-T, Core Web Vitals, doorway pages, helpful content update, etc.) grouped by category with practical context. Optional 'category' arg: basics | aeo | technical | analytics | penalty | all. No API key required.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'category' => ['type' => 'string', 'description' => 'Filter to one category. Default: all.'],
                ],
            ],
        ],
        [
            'name' => 'install_skill',
            'description' => "Returns the exact install commands for the Ranki SEO + AEO Skill across every supported AI agent (Claude Code, Claude Desktop, Cursor, Windsurf, Claude.ai web Projects, generic AGENTS.md). The Skill is a Markdown playbook that auto-activates on SEO/AEO prompts and orchestrates the other Ranki MCP tools. Optional 'agent' arg picks one (claude_code | claude_desktop | cursor | windsurf | claude_web | generic | all). No API key required.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'agent' => ['type' => 'string', 'description' => 'Which agent to install for. Default: all.'],
                ],
            ],
        ],
        [
            'name' => 'propose_titles_metas',
            'description' => "For 'rewrite my titles and meta descriptions' — reads one or more URLs (or a free-text description for un-deployed pages), extracts the actual page facts (h1, first paragraph, current title, detected intent), and returns a Markdown table with 5 title + meta description CANDIDATES per page across different angles (descriptive, benefit-led, question-format, specific, keyword-first). User picks per page, calling AI applies. Built-in length validation (50-65 char titles, 140-160 char metas). No API key, no LLM tokens spent.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'urls' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => 'Up to 8 absolute URLs to analyze.'],
                    'pages_description' => ['type' => 'string', 'description' => 'Optional free-text description for pages not deployed yet.'],
                    'focus_keyword' => ['type' => 'string', 'description' => 'Optional brand or primary keyword to front-load in candidates.'],
                ],
            ],
        ],
        [
            'name' => 'audit_hidden_pages',
            'description' => "For 'which pages should I hide from search engines?' — classifies a list of paths/URLs OR crawls a domain (1 level deep, capped 100 URLs) and returns a Markdown table marking each as `robots-disallow` (admin, API, drafts), `noindex` (login, account, thank-you, errors), `keep` (real content) or `unsure` (review). Includes a ready-to-paste robots.txt block + noindex meta snippet. Pure pattern matching, zero LLM tokens.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'urls' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => 'Paths or URLs to classify.'],
                    'domain' => ['type' => 'string', 'description' => 'Site root to crawl 1 level deep instead of (or in addition to) urls.'],
                ],
            ],
        ],
        [
            'name' => 'audit_speed',
            'description' => "Measure real Core Web Vitals and Lighthouse scores for a URL via Google PageSpeed Insights. Returns Performance / Accessibility / SEO / Best Practices scores; LCP / CLS / INP / FCP / TTFB values; image opportunities with bytes saved per file; render-blocking JS / CSS; failing on-page SEO audits. The calling agent uses this to decide what to fix first — most commonly: call `optimize_images` next, then convert the actual files and rewrite <img> markup. No API key required.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'url' => ['type' => 'string', 'description' => 'Full URL to test'],
                    'strategy' => ['type' => 'string', 'description' => '"mobile" (default — Google ranks mobile-first) or "desktop"'],
                ],
                'required' => ['url'],
            ],
        ],
        [
            'name' => 'audit_core_web_vitals',
            'description' => 'Focused Core Web Vitals audit — same PageSpeed Insights backend as `audit_speed`, but returns one paragraph per metric (LCP / CLS / INP) with literal fix recipes ("LCP element is hero.png at 2.4MB, convert to WebP saves 1.8MB → -1.1s LCP"). Picks the LCP element URL out of Lighthouse so the agent knows exactly which file to optimize. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'url' => ['type' => 'string', 'description' => 'Full URL to test'],
                    'strategy' => ['type' => 'string', 'description' => '"mobile" or "desktop". Default: mobile.'],
                ],
                'required' => ['url'],
            ],
        ],
        [
            'name' => 'optimize_images',
            'description' => "For each image URL the user gives (typically from `audit_speed` opportunities), return: target format (AVIF + WebP), target widths for a responsive 1x / 2x set, alt-text suggestion from the file stem, plus a ready-to-paste responsive `<picture>` block with `srcset`. Also returns the literal CLI command to run (sharp-cli or cwebp/avifenc) so the agent converts the files locally in the repo. Pure heuristics — no API key required, no conversion server-side.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'images' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => '1-20 image URLs or relative paths'],
                    'max_width' => ['type' => 'integer', 'description' => 'Target max width for the 1x variant in pixels. Default: 1600.'],
                ],
                'required' => ['images'],
            ],
        ],
        [
            'name' => 'audit_aeo',
            'description' => 'Audit a URL for Answer Engine Optimization. Checks: FAQPage / Article JSON-LD, definitional intro (<80 words, "X is" pattern), author byline, llms.txt presence, robots.txt allowing GPTBot/ClaudeBot/PerplexityBot, answer-style H2/H3 headings, structured tables. Returns scorecard + per-check fix recipes. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['url' => ['type' => 'string', 'description' => 'Full URL to audit, e.g. https://example.com/blog/post']],
                'required' => ['url'],
            ],
        ],
        [
            'name' => 'audit_seo',
            'description' => 'Audit a URL for on-page SEO. Checks title length, meta description, H1 count, canonical, image alt coverage, internal link count, JSON-LD presence, viewport meta, HTTPS, OpenGraph completeness. Returns scorecard + fix recipes. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['url' => ['type' => 'string', 'description' => 'Full URL to audit']],
                'required' => ['url'],
            ],
        ],
        [
            'name' => 'generate_sitemap_xml',
            'description' => 'Generate a sitemap.xml from a list of URLs. Pass in your routes/URLs and get back a ready-to-deploy sitemap with current lastmod dates. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'urls' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => 'List of absolute URLs to include'],
                    'changefreq' => ['type' => 'string', 'description' => 'Optional changefreq: always/hourly/daily/weekly/monthly/yearly/never. Default: weekly.'],
                ],
                'required' => ['urls'],
            ],
        ],
        [
            'name' => 'generate_llms_txt',
            'description' => 'Generate an llms.txt file template — the emerging standard for telling LLMs (ChatGPT, Claude, Perplexity) what your site is about, key pages, and crawl preferences. Pass site name, summary, and key URLs. No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'site_name' => ['type' => 'string'],
                    'summary' => ['type' => 'string', 'description' => 'One-paragraph site description'],
                    'key_pages' => ['type' => 'array', 'items' => ['type' => 'object', 'properties' => ['url' => ['type' => 'string'], 'title' => ['type' => 'string']]], 'description' => 'Important pages to highlight'],
                ],
                'required' => ['site_name', 'summary'],
            ],
        ],
        [
            'name' => 'generate_robots_txt',
            'description' => 'Generate a robots.txt that explicitly allows or denies AI crawlers (GPTBot, ClaudeBot, PerplexityBot, Google-Extended, ChatGPT-User, anthropic-ai). Default: allow all (you want AI search traffic). No API key required.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'sitemap_url' => ['type' => 'string', 'description' => 'Full URL to sitemap.xml (e.g. https://example.com/sitemap.xml)'],
                    'allow_ai' => ['type' => 'boolean', 'description' => 'Allow AI crawlers (default: true)'],
                    'disallow_paths' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => 'Paths to block for all crawlers, e.g. ["/admin", "/draft"]'],
                ],
                'required' => ['sitemap_url'],
            ],
        ],
        [
            'name' => 'get_account',
            'description' => 'Check your Ranki.io API key works and see your account snapshot (email, plan, daily and monthly limits, current usage). Best first call after pasting a key. Requires X-API-Key.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => new \stdClass,
            ],
        ],
        [
            'name' => 'list_projects',
            'description' => 'List the projects in your Ranki.io account. Returns id, name, url, status, language. Use this to confirm which content Ranki.io is generating for you. Requires X-API-Key.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['per_page' => ['type' => 'integer', 'description' => 'Default 25, max 50']],
            ],
        ],
        [
            'name' => 'get_article',
            'description' => 'Fetch a single article from your Ranki.io account by nano_id. Returns title, content_html, focus_keyword (array), TOC, embedded image URLs, SEO score. Requires X-API-Key.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['article_id' => ['type' => 'string', 'description' => 'nano_id of the article (e.g. LISQJJOGF). Get one from list_projects then drill into a project.']],
                'required' => ['article_id'],
            ],
        ],
        [
            'name' => 'list_rank_tracking',
            'description' => "Returns the Google Search Console summary for a Ranki.io project: 28-day totals (clicks / impressions / keyword count), top 20 keywords by clicks, and top 20 opportunity keywords (position > 10, impressions ≥ 10 — easy wins). Use this to anchor a content session in real ranking data instead of guessing. Requires X-API-Key + the project must have GSC connected at app.ranki.io.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'project_id' => ['type' => 'string', 'description' => "nano_id of the project (get from `list_projects`)"],
                ],
                'required' => ['project_id'],
            ],
        ],
        [
            'name' => 'list_gsc_keywords',
            'description' => "Paginated full list of Google Search Console keywords for a Ranki.io project (current 28-day window). Use when `list_rank_tracking` summary isn't enough — e.g. 'show me every keyword with impressions > 50' or 'sort by position ascending'. Requires X-API-Key.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'project_id' => ['type' => 'string', 'description' => 'Project nano_id'],
                    'sort' => ['type' => 'string', 'description' => 'clicks | impressions | position | ctr (default: clicks)'],
                    'dir' => ['type' => 'string', 'description' => 'asc | desc (default: desc)'],
                    'min_impressions' => ['type' => 'integer', 'description' => 'Filter floor on impressions'],
                    'per_page' => ['type' => 'integer', 'description' => 'Default 50, max 100'],
                ],
                'required' => ['project_id'],
            ],
        ],
        [
            'name' => 'ai_visibility',
            'description' => "Returns the project's recorded AI-citation snapshots — which of your tracked topics appeared in ChatGPT / Claude / Perplexity / Google AI Overview SERPs at the time of capture. Includes count and percentage of topics currently cited by AI search. Use to decide which content to upgrade for AEO. Requires X-API-Key.",
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'project_id' => ['type' => 'string', 'description' => 'Project nano_id'],
                    'since' => ['type' => 'string', 'description' => 'ISO date (default: 30 days ago)'],
                    'cited_only' => ['type' => 'boolean', 'description' => 'Only return AI-cited rows'],
                    'per_page' => ['type' => 'integer', 'description' => 'Default 50, max 100'],
                ],
                'required' => ['project_id'],
            ],
        ],
    ];
}

function rk_mcp_call_tool(string $name, array $args, string $apiKey): array
{
    $toolFile = __DIR__.'/../tools/'.$name.'.php';
    if (! is_file($toolFile)) {
        throw new \RuntimeException("Unknown tool: {$name}");
    }
    $fn = require $toolFile;

    return $fn($args, $apiKey);
}

/**
 * Internal helper for tools that proxy to the Ranki.io REST API.
 * Used by list_projects + get_article.
 *
 * @return array<string, mixed>
 */
function rk_mcp_api_call(string $endpoint, string $apiKey, string $method = 'GET'): array
{
    if ($apiKey === '') {
        throw new \RuntimeException(
            "This tool reads your private Ranki.io data, so it needs your API key.\n\n".
            "Generate one in 30 seconds at: https://app.ranki.io/developer\n".
            "Then set it in your MCP client config:\n".
            "  • stdio (Claude Desktop / Code): env.RANKI_API_KEY = \"rk_live_...\"\n".
            "  • HTTP (Cursor / Windsurf):       headers.X-API-Key = \"rk_live_...\""
        );
    }

    $url = 'https://app.ranki.io'.$endpoint;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['X-API-Key: '.$apiKey, 'Accept: application/json'],
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_TIMEOUT => 15,
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($body === false) {
        throw new \RuntimeException("Couldn't reach app.ranki.io (network error: {$err}). Try again in a moment — this is usually transient.");
    }

    $decoded = json_decode((string) $body, true);

    // Translate HTTP-level errors into human messages keyed off the upstream
    // error code so the user gets a precise next step.
    if ($code === 401) {
        $reason = is_array($decoded) ? ($decoded['error'] ?? '') : '';
        if ($reason === 'missing_api_key') {
            throw new \RuntimeException(
                "Your MCP client didn't send the API key header.\n\n".
                "Double-check the config — stdio clients use env.RANKI_API_KEY, HTTP clients use headers.X-API-Key.\n".
                "Then restart the client (some IDEs need a full reload before MCP env changes take effect)."
            );
        }
        throw new \RuntimeException(
            "Your Ranki.io API key isn't valid (it may have been regenerated or revoked).\n\n".
            "1. Open https://app.ranki.io/developer and click Reveal to copy the current key.\n".
            "2. Paste it into your MCP client config.\n".
            "3. Restart the client and retry.\n\n".
            "If you generated a new key, the previous one stopped working the moment you did."
        );
    }

    if ($code === 429) {
        $retry = is_array($decoded) ? ($decoded['retry_after'] ?? null) : null;
        throw new \RuntimeException(
            "Rate limited by app.ranki.io (60 requests per minute per key)".
            ($retry ? ". Retry in {$retry}s." : '. Slow down by a few seconds and retry.')
        );
    }

    if (! is_array($decoded)) {
        throw new \RuntimeException("Unexpected response from app.ranki.io (HTTP {$code}). If this keeps happening, ping support@ranki.io with the request you tried.");
    }

    if ($code >= 400) {
        $msg = $decoded['message'] ?? "app.ranki.io returned HTTP {$code}";
        throw new \RuntimeException($msg);
    }

    return $decoded;
}

/**
 * Reject URLs that resolve to private, loopback, link-local or cloud-meta
 * IPs (SSRF guard). Returns null on success; on failure returns a human
 * error string suitable to return to the caller.
 *
 * This is called *before* curl, and again on every redirect target, so an
 * attacker can't 302 from a public host into 169.254.169.254 etc.
 */
function rk_mcp_url_blocked_reason(string $url): ?string
{
    if (mb_strlen($url) > 2048) {
        return 'URL is too long (max 2048 characters).';
    }
    $parts = parse_url($url);
    if (! is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
        return 'URL is malformed. Expected an absolute http:// or https:// URL.';
    }
    $scheme = strtolower($parts['scheme']);
    if (! in_array($scheme, ['http', 'https'], true)) {
        return "Only http:// and https:// URLs are accepted (got: {$scheme}://). file:// gopher:// and other schemes are disabled.";
    }

    $host = strtolower($parts['host']);
    if ($host === 'localhost' || str_ends_with($host, '.localhost') || $host === 'metadata.google.internal') {
        return "Refusing to fetch {$host} — it resolves to internal infrastructure.";
    }

    // Resolve to every IP (v4 + v6) and reject if any is private/reserved.
    // Using dns_get_record so we cover both families.
    $ips = [];
    foreach (dns_get_record($host, DNS_A | DNS_AAAA) ?: [] as $rec) {
        if (! empty($rec['ip'])) {
            $ips[] = $rec['ip'];
        }
        if (! empty($rec['ipv6'])) {
            $ips[] = $rec['ipv6'];
        }
    }
    if (filter_var($host, FILTER_VALIDATE_IP)) {
        $ips[] = $host;
    }
    if (empty($ips)) {
        return "Couldn't resolve host {$host}. Check the URL is correct and the site is reachable.";
    }
    foreach ($ips as $ip) {
        if (rk_mcp_ip_is_private($ip)) {
            return "Refusing to fetch {$host} — it resolves to a private, loopback, link-local or cloud-metadata address ({$ip}).";
        }
    }

    return null;
}

/**
 * @return bool true iff the IP is loopback, private, link-local,
 *              cloud-metadata, multicast, or otherwise non-public.
 */
function rk_mcp_ip_is_private(string $ip): bool
{
    if (! filter_var($ip, FILTER_VALIDATE_IP)) {
        return true;
    }
    // PHP's built-in filter rejects private + reserved ranges. But it
    // doesn't cover 169.254.169.254 (AWS IMDS) — actually it does, since
    // 169.254/16 is reserved. Still pass explicit metadata IPs to be safe.
    if (in_array($ip, [
        '169.254.169.254', '169.254.170.2',  // AWS IMDS v1/v2
        '100.100.100.200',                    // Alibaba Cloud
        '0.0.0.0',
    ], true)) {
        return true;
    }
    return ! filter_var(
        $ip,
        FILTER_VALIDATE_IP,
        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    );
}

/**
 * Fetch a URL's HTML with a short timeout. SSRF-safe: validates scheme,
 * resolves the host, rejects private IPs, follows up to 5 redirects
 * manually (re-validating each hop), caps body at 8 MB.
 *
 * Returns ['html' => string, 'status' => int, 'final_url' => string,
 *          'error' => ?string].
 */
function rk_mcp_fetch_url(string $url, int $timeout = 10): array
{
    $maxRedirects = 5;
    $maxBytes = 8 * 1024 * 1024;
    $current = $url;

    for ($i = 0; $i <= $maxRedirects; $i++) {
        $reason = rk_mcp_url_blocked_reason($current);
        if ($reason !== null) {
            // Throw so the dispatcher's catch turns this into a clean
            // JSON-RPC error. Returning an "empty html" lets the tool
            // continue and accidentally reflect the bad URL in output.
            throw new \RuntimeException($reason);
        }

        $ch = curl_init($current);
        $bodySize = 0;
        $body = '';
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; RankiMCP/0.3; +https://ranki.io/developers/mcp)',
            CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$body, &$bodySize, $maxBytes) {
                $len = strlen($chunk);
                if ($bodySize + $len > $maxBytes) {
                    $body .= substr($chunk, 0, $maxBytes - $bodySize);
                    return 0; // abort transfer (response is too big)
                }
                $body .= $chunk;
                $bodySize += $len;
                return $len;
            },
        ]);
        curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $redirectTo = (string) curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        curl_close($ch);

        if ($status >= 300 && $status < 400 && $redirectTo !== '') {
            // Absolute URL? curl gives us absolute on most setups, but be
            // defensive and resolve manually if relative.
            if (! preg_match('~^https?://~i', $redirectTo)) {
                $base = parse_url($current);
                if (str_starts_with($redirectTo, '//')) {
                    $redirectTo = ($base['scheme'] ?? 'https').':'.$redirectTo;
                } elseif (str_starts_with($redirectTo, '/')) {
                    $redirectTo = ($base['scheme'] ?? 'https').'://'.$base['host'].$redirectTo;
                } else {
                    // Relative path — drop, too edge-case to follow safely.
                    return ['html' => $body, 'status' => $status, 'final_url' => $current, 'error' => null];
                }
            }
            $current = $redirectTo;
            continue;
        }

        return ['html' => $body, 'status' => $status, 'final_url' => $current, 'error' => null];
    }

    return ['html' => '', 'status' => 0, 'final_url' => $current, 'error' => 'Too many redirects (>5).'];
}
