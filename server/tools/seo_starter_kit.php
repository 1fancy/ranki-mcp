<?php
declare(strict_types=1);

/**
 * seo_starter_kit — one-shot answer for "I vibe-coded a site, what SEO files do I need?"
 *
 * Returns a deterministic checklist + the exact file contents to add. The
 * caller's Claude then writes the files into the user's repo. We never
 * try to read the user's repo from the MCP server — that's the agent's job.
 */
return function (array $args, string $apiKey): array {
    $domain = trim((string) ($args['domain'] ?? ''));
    if ($domain === '') {
        throw new \RuntimeException('domain is required (the site you\'re optimizing, e.g. example.com)');
    }
    // Normalize: strip protocol + trailing slash so the templates below stay clean
    $domain = preg_replace('#^https?://#', '', $domain);
    $domain = rtrim($domain, '/');

    $out = "## SEO starter kit for {$domain}\n\n";
    $out .= "**Your task as the calling AI:** add these 4 files to the user's repo, in this order. After each, verify with the user that the file is in the right path for their framework (Next.js → `public/`, Astro → `public/`, Laravel → `public/`, custom node → wherever static files are served).\n\n";

    // 1. robots.txt
    $out .= "### 1. `robots.txt` (must be at site root)\n";
    $out .= "Tells crawlers what they can access. AI crawlers (GPTBot, ClaudeBot, PerplexityBot) explicitly allowed because the user wants AI search citation traffic.\n";
    $out .= "```\n";
    $out .= "User-agent: *\nDisallow:\n\n";
    $out .= "User-agent: GPTBot\nAllow: /\n\n";
    $out .= "User-agent: ChatGPT-User\nAllow: /\n\n";
    $out .= "User-agent: ClaudeBot\nAllow: /\n\n";
    $out .= "User-agent: anthropic-ai\nAllow: /\n\n";
    $out .= "User-agent: PerplexityBot\nAllow: /\n\n";
    $out .= "User-agent: Google-Extended\nAllow: /\n\n";
    $out .= "Sitemap: https://{$domain}/sitemap.xml\n";
    $out .= "```\n\n";

    // 2. sitemap.xml
    $out .= "### 2. `sitemap.xml` (must be at site root)\n";
    $out .= "Lists every indexable URL. The agent should crawl the user's routes/file system to enumerate URLs, then build this file. Use `lastmod` from file modification time (or commit date for static-site generators).\n";
    $out .= "```xml\n";
    $out .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $out .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    $out .= "  <url>\n";
    $out .= "    <loc>https://{$domain}/</loc>\n";
    $out .= "    <lastmod>".gmdate('Y-m-d')."</lastmod>\n";
    $out .= "    <changefreq>weekly</changefreq>\n";
    $out .= "    <priority>1.0</priority>\n";
    $out .= "  </url>\n";
    $out .= "  <!-- Agent: add one <url> block per page in the user's site -->\n";
    $out .= "</urlset>\n";
    $out .= "```\n";
    $out .= "Use the `generate_sitemap_xml` MCP tool to build this from a URL list.\n\n";

    // 3. llms.txt
    $out .= "### 3. `llms.txt` (must be at site root — emerging standard)\n";
    $out .= "Tells LLMs what the site is, key pages, and crawl preferences. Adopted by ChatGPT/Claude/Perplexity for citation grounding.\n";
    $out .= "```markdown\n";
    $out .= "# [Site name]\n\n";
    $out .= "> [One-paragraph description — Claude should write this from the user's homepage copy]\n\n";
    $out .= "## Key pages\n\n";
    $out .= "- [Homepage](https://{$domain}/)\n";
    $out .= "- [Pricing](https://{$domain}/pricing)\n";
    $out .= "- [Docs](https://{$domain}/docs)\n";
    $out .= "- [Blog](https://{$domain}/blog)\n\n";
    $out .= "## About\n\n";
    $out .= "[3-5 bullets covering what the site does, who it's for, what's unique]\n";
    $out .= "```\n";
    $out .= "Use the `generate_llms_txt` MCP tool to build this with real content from the user's site.\n\n";

    // 4. JSON-LD
    $out .= "### 4. JSON-LD structured data (in every page's `<head>`)\n";
    $out .= "The single biggest AEO signal — tells search engines what each page IS, not just what's on it.\n\n";
    $out .= "**For the homepage:**\n";
    $out .= "```html\n";
    $out .= "<script type=\"application/ld+json\">\n";
    $out .= "{\n";
    $out .= "  \"@context\": \"https://schema.org\",\n";
    $out .= "  \"@type\": \"Organization\",\n";
    $out .= "  \"name\": \"[Site name]\",\n";
    $out .= "  \"url\": \"https://{$domain}\",\n";
    $out .= "  \"description\": \"[from homepage]\",\n";
    $out .= "  \"sameAs\": [\"https://twitter.com/...\", \"https://linkedin.com/...\"]\n";
    $out .= "}\n";
    $out .= "</script>\n";
    $out .= "```\n\n";
    $out .= "**For every blog post / article:**\n";
    $out .= "```html\n";
    $out .= "<script type=\"application/ld+json\">\n";
    $out .= "{\n";
    $out .= "  \"@context\": \"https://schema.org\",\n";
    $out .= "  \"@type\": \"Article\",\n";
    $out .= "  \"headline\": \"[title]\",\n";
    $out .= "  \"author\": {\"@type\": \"Person\", \"name\": \"[author]\"},\n";
    $out .= "  \"datePublished\": \"[ISO 8601]\",\n";
    $out .= "  \"image\": [\"[hero image URL]\"]\n";
    $out .= "}\n";
    $out .= "</script>\n";
    $out .= "```\n\n";
    $out .= "**For any page answering 3+ FAQs:** add a `FAQPage` JSON-LD block. This is the highest-citation signal for ChatGPT/Perplexity.\n\n";

    $out .= "---\n\n";
    $out .= "### After deploying these 4 files:\n";
    $out .= "1. Submit `sitemap.xml` to Google Search Console (`https://search.google.com/search-console`).\n";
    $out .= "2. Wait 48h, then re-run `audit_seo` and `audit_aeo` to confirm the score jumped.\n";
    $out .= "3. To check AI-citation visibility, ask the user to search for their brand in ChatGPT and Perplexity. If not cited yet, run `find_topic_ideas` to plan content that closes the gap.\n";

    return rk_mcp_text_content($out);
};
