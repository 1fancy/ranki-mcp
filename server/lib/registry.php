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

const RK_MCP_KEYED_TOOLS = ['list_projects', 'get_article'];

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
            'name' => 'list_projects',
            'description' => 'List the projects in your Ranki.io account. Requires X-API-Key. Returns id, name, url, status. Useful for inspecting what content Ranki.io is generating for you.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['per_page' => ['type' => 'integer', 'description' => 'Default 25, max 50']],
            ],
        ],
        [
            'name' => 'get_article',
            'description' => 'Fetch a single article from your Ranki.io account by nano_id. Requires X-API-Key. Returns title, content_html, focus_keyword (array), TOC, embedded image URLs, SEO score.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => ['article_id' => ['type' => 'string', 'description' => 'nano_id of the article (e.g. LISQJJOGF)']],
                'required' => ['article_id'],
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
        throw new \RuntimeException('This tool requires an API key. Set X-API-Key header — generate one at https://app.ranki.io/profile#developer');
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
    curl_close($ch);

    if ($body === false) {
        throw new \RuntimeException('Network error reaching app.ranki.io');
    }
    $decoded = json_decode((string) $body, true);
    if (! is_array($decoded)) {
        throw new \RuntimeException("Unexpected response from app.ranki.io (HTTP {$code})");
    }
    if ($code >= 400) {
        throw new \RuntimeException($decoded['message'] ?? 'API error: HTTP '.$code);
    }

    return $decoded;
}

/**
 * Fetch a URL's HTML with a short timeout. Used by audit_aeo / audit_seo.
 * Returns ['html' => string, 'status' => int, 'final_url' => string].
 */
function rk_mcp_fetch_url(string $url, int $timeout = 10): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; RankiMCP/0.1; +https://ranki.io/developers/mcp)',
    ]);
    $html = (string) curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $finalUrl = (string) curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    return ['html' => $html, 'status' => $status, 'final_url' => $finalUrl ?: $url];
}
