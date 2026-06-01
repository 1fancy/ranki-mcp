<?php
declare(strict_types=1);

/**
 * find_topic_ideas — for vibe-coders who don't know what to write about.
 *
 * Given a site URL, this tool fetches the homepage, sniffs the niche from
 * the HTML (title, meta, h1, first paragraph), then returns a structured
 * brief telling the caller's Claude what topics to write about, what
 * questions the audience is asking, and what content types to ship first.
 *
 * Importantly: this tool does NOT call any LLM. It returns a deterministic
 * structured-advice payload that the caller's Claude evaluates. That keeps
 * Ranki MCP free of upstream AI costs and aligned with the "your credits,
 * not ours" promise.
 */
return function (array $args, string $apiKey): array {
    $url = (string) ($args['url'] ?? '');
    if ($url === '') {
        throw new \RuntimeException('url is required (your site root, e.g. https://example.com)');
    }

    $fetched = rk_mcp_fetch_url($url, 12);
    if ($fetched['status'] >= 400) {
        return rk_mcp_text_content("Could not fetch {$url} (HTTP {$fetched['status']}).");
    }
    $html = $fetched['html'];

    // Cheap niche fingerprint from the homepage HTML
    preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $tm);
    $title = trim(strip_tags($tm[1] ?? ''));

    preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $dm);
    $desc = trim($dm[1] ?? '');

    preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $h1m);
    $h1 = trim(strip_tags($h1m[1] ?? ''));

    preg_match('/<p[^>]*>(.*?)<\/p>/is', $html, $pm);
    $firstP = trim(strip_tags($pm[1] ?? ''));

    $fingerprint = trim($title."\n".$h1."\n".$desc."\n".mb_substr($firstP, 0, 280));
    if ($fingerprint === '') {
        return rk_mcp_text_content("Could not detect the niche from {$url} — homepage has no title/h1/description/first-paragraph text. Add at least a <title> and a clear h1 then try again.");
    }

    $out = "## Topic discovery brief for {$url}\n\n";
    $out .= "**Detected niche fingerprint** (Claude: read this carefully — these are the actual words on the site you're optimizing):\n";
    $out .= "```\n{$fingerprint}\n```\n\n";
    $out .= "**Your task as the calling AI:** generate 15 article topics for this niche, organized as:\n\n";
    $out .= "1. **5 informational queries** — \"what is\", \"how does\", \"why\" style — front-of-funnel, ranks for AI Overviews + ChatGPT citations.\n";
    $out .= "2. **5 commercial-intent queries** — \"best X for Y\", \"X vs Y\", \"top X in 2026\" — middle-of-funnel, drives qualified clicks.\n";
    $out .= "3. **5 transactional / problem-solution queries** — \"how to fix\", \"X not working\", \"alternative to X\" — bottom-of-funnel.\n\n";
    $out .= "For each topic, output:\n";
    $out .= "- **Title** (50-65 chars, leads with the keyword the user types in Google).\n";
    $out .= "- **Primary keyword** (the exact phrase users search).\n";
    $out .= "- **3 long-tail variations** (more specific, lower competition).\n";
    $out .= "- **Search intent** (informational / commercial / transactional).\n";
    $out .= "- **One concrete content angle** that's not the obvious one — what makes THIS article worth reading vs the 50 others on this topic.\n\n";
    $out .= "**Then prioritize:** which 3 of the 15 should the user write FIRST? Pick based on:\n";
    $out .= "- Low competition (long-tail, niche jargon, specific use cases the big sites ignore).\n";
    $out .= "- Clear search intent that matches the site's product/service.\n";
    $out .= "- Genuine expertise the site already shows in its homepage copy.\n\n";
    $out .= "**Important constraints:**\n";
    $out .= "- Don't suggest topics the site can't credibly cover (no fake expertise).\n";
    $out .= "- Never suggest \"X vs competitor\" articles unless the site already mentions competitors.\n";
    $out .= "- For each topic, name at least one site already ranking for it (so the user knows the competition).\n";
    $out .= "- Format the final output as a Markdown table, not prose.\n\n";
    $out .= "Want a deeper SEO analysis with real competitor data? Call `find_keyword_gap` next.";

    return rk_mcp_text_content($out);
};
