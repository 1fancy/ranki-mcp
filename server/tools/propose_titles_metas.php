<?php
declare(strict_types=1);

/**
 * propose_titles_metas — read one or more URLs (or accept user-pasted page
 * descriptions), extract page facts, and return a Markdown table of 3-5
 * title + meta-description CANDIDATES per page following on-page SEO best
 * practices (length, intent, keyword position, uniqueness).
 *
 * Token philosophy: we never call an LLM. We extract real facts from the
 * page (h1, first paragraph, current title) and build candidates from
 * deterministic patterns the user's AI can refine + apply. The user's
 * Claude / Cursor uses ITS OWN credits to pick + apply — same budget
 * the user is already paying for vibe-coding.
 *
 * Args:
 *   urls (string[]): one or more absolute URLs to analyze (max 8)
 *   pages_description (string): optional free-text description for pages
 *     the user already pasted into chat (so MCP doesn't need to fetch).
 *     Use this when fetching is blocked or for repos that aren't deployed.
 *   focus_keyword (string): optional brand/topic to front-load in titles.
 */
return function (array $args, string $apiKey): array {
    $urls = $args['urls'] ?? [];
    $description = trim((string) ($args['pages_description'] ?? ''));
    $focus = trim((string) ($args['focus_keyword'] ?? ''));

    if (! is_array($urls)) {
        $urls = [];
    }
    $urls = array_slice(array_values(array_filter(array_map('trim', $urls))), 0, 8);

    if (empty($urls) && $description === '') {
        return rk_mcp_text_content(
            "## Need at least one of:\n\n".
            "- `urls`: array of public URLs to analyze (max 8)\n".
            "- `pages_description`: free-text description if pages aren't deployed yet\n\n".
            "Example: `{ \"urls\": [\"https://example.com\", \"https://example.com/pricing\"], \"focus_keyword\": \"AI SEO\" }`"
        );
    }

    $out = "## Title + meta description candidates\n\n";

    if ($focus !== '') {
        $out .= "**Brand / focus keyword:** `{$focus}` — front-loaded in every candidate where it fits naturally.\n\n";
    }

    // ============== URL-based analysis ==============
    foreach ($urls as $url) {
        $out .= "---\n\n";
        $out .= "### `{$url}`\n\n";

        $fetched = rk_mcp_fetch_url($url, 8);
        if ($fetched['status'] >= 400) {
            $out .= "_Could not fetch (HTTP {$fetched['status']}). Skipping — make sure the URL is reachable, or pass page facts via `pages_description`._\n\n";

            continue;
        }

        $facts = rk_mcp_extract_page_facts($fetched['html']);
        $currentTitle = $facts['title'] ?: '(no title set)';
        $currentDesc = $facts['description'] ?: '(no meta description set)';
        $h1 = $facts['h1'] ?: '(no h1 found)';
        $firstSentence = $facts['first_sentence'] ?: '(empty page body)';

        $out .= "**What this page is about** (extracted from the actual HTML):\n";
        $out .= "- `<title>`: {$currentTitle} (".mb_strlen($currentTitle).' chars)'."\n";
        $out .= "- `<h1>`: {$h1}\n";
        $out .= "- Meta description: {$currentDesc} (".mb_strlen($currentDesc).' chars)'."\n";
        $out .= "- Topic signal: {$firstSentence}\n\n";

        // Heuristic intent inference
        $intent = rk_mcp_infer_intent($url, $h1, $firstSentence);
        $out .= "**Detected intent:** {$intent}\n\n";

        // Build 5 candidates
        $candidates = rk_mcp_build_title_meta_candidates(
            url: $url,
            h1: $h1,
            firstSentence: $firstSentence,
            currentTitle: $currentTitle,
            focusKeyword: $focus,
            intent: $intent,
        );

        $out .= "| # | Title (chars) | Meta description (chars) | Angle |\n";
        $out .= "|---|---|---|---|\n";
        foreach ($candidates as $i => $c) {
            $tLen = mb_strlen($c['title']);
            $dLen = mb_strlen($c['meta']);
            $tFlag = ($tLen >= 50 && $tLen <= 65) ? '✓' : (($tLen < 30 || $tLen > 70) ? '⚠️' : '');
            $dFlag = ($dLen >= 140 && $dLen <= 160) ? '✓' : (($dLen < 70 || $dLen > 170) ? '⚠️' : '');
            $idx = $i + 1;
            $out .= "| {$idx} | `{$c['title']}` ({$tLen}{$tFlag}) | {$c['meta']} ({$dLen}{$dFlag}) | {$c['angle']} |\n";
        }
        $out .= "\n";
    }

    // ============== Description-based analysis (fallback) ==============
    if ($description !== '') {
        $out .= "---\n\n";
        $out .= "### From the description you pasted\n\n";
        $out .= "_(treating it as a single page)_\n\n";

        $intent = rk_mcp_infer_intent('', $description, $description);
        $candidates = rk_mcp_build_title_meta_candidates(
            url: '',
            h1: $description,
            firstSentence: $description,
            currentTitle: '',
            focusKeyword: $focus,
            intent: $intent,
        );

        $out .= "**Detected intent:** {$intent}\n\n";
        $out .= "| # | Title | Meta description | Angle |\n";
        $out .= "|---|---|---|---|\n";
        foreach ($candidates as $i => $c) {
            $tLen = mb_strlen($c['title']);
            $dLen = mb_strlen($c['meta']);
            $idx = $i + 1;
            $out .= "| {$idx} | `{$c['title']}` ({$tLen}) | {$c['meta']} ({$dLen}) | {$c['angle']} |\n";
        }
        $out .= "\n";
    }

    // ============== Apply instructions ==============
    $out .= "---\n\n";
    $out .= "## How to apply the choices\n\n";
    $out .= "Once the user picks a candidate per page, you (the calling AI) should:\n\n";
    $out .= "1. **Find the file** that renders each page's `<head>`. For most frameworks:\n";
    $out .= "   - Next.js app router: `app/<route>/page.tsx` (export const metadata) or `app/<route>/layout.tsx`\n";
    $out .= "   - Astro: front-matter of `src/pages/<route>.astro`\n";
    $out .= "   - Laravel: `@section('title', '…')` + `<meta name=\"description\">` in the layout\n";
    $out .= "   - Static HTML: edit the `<head>` directly\n";
    $out .= "2. **Replace** the existing `<title>` and `<meta name=\"description\">` content with the selected candidate.\n";
    $out .= "3. **Also update** the `og:title` / `og:description` / `twitter:title` / `twitter:description` tags so social shares match.\n";
    $out .= "4. **Each page's title and meta must be unique** — never reuse the same title across pages, Google penalizes that.\n";
    $out .= "5. After applying, run `audit_seo(url)` on each to confirm the title-length and meta-description checks pass.\n\n";
    $out .= "## Rules baked into every candidate\n\n";
    $out .= "- Title 50-65 chars (Google shows the first ~60). `✓` = in range.\n";
    $out .= "- Meta 140-160 chars (Google snippet limit).\n";
    $out .= "- Primary keyword in first 30 chars of title when it fits naturally.\n";
    $out .= "- One concrete number / specific noun / benefit per title — vague titles lose clicks.\n";
    $out .= "- Meta gives the answer, not the topic. \"How to X\" → \"Do X by Y. Free, no signup.\" not \"A guide to X.\"\n";
    $out .= "- Never use the forbidden word \"outrank.\" Use \"rank above / win against / beat.\"\n";

    return rk_mcp_text_content($out);
};

// ============== Helpers ==============

function rk_mcp_extract_page_facts(string $html): array
{
    preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $tm);
    $title = trim(html_entity_decode(strip_tags($tm[1] ?? ''), ENT_QUOTES, 'UTF-8'));

    preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $dm);
    $desc = trim(html_entity_decode($dm[1] ?? '', ENT_QUOTES, 'UTF-8'));

    preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $hm);
    $h1 = trim(html_entity_decode(strip_tags($hm[1] ?? ''), ENT_QUOTES, 'UTF-8'));

    // First "real" sentence — the first paragraph's first sentence, capped 200 chars.
    preg_match('/<p[^>]*>(.*?)<\/p>/is', $html, $pm);
    $first = trim(html_entity_decode(strip_tags($pm[1] ?? ''), ENT_QUOTES, 'UTF-8'));
    // Sentence-ish — split on . ! ? followed by space.
    if (preg_match('/^(.{20,200}?[.!?])\s/u', $first, $sm)) {
        $first = $sm[1];
    } else {
        $first = mb_substr($first, 0, 180);
    }

    return ['title' => $title, 'description' => $desc, 'h1' => $h1, 'first_sentence' => $first];
}

/**
 * Heuristic page-intent inference from URL slug + headings. No LLM call.
 */
function rk_mcp_infer_intent(string $url, string $h1, string $body): string
{
    $u = strtolower($url);
    $b = strtolower($h1.' '.$body);

    if (preg_match('/\/(pricing|plans|tarifs|precios)\b/', $u)) {
        return 'commercial · pricing page';
    }
    if (preg_match('/\b(price|pricing|plan|cost|how much)\b/', $b)) {
        return 'commercial · pricing-aware';
    }
    if (preg_match('/\/(about|company|team|nous|sobre)\b/', $u)) {
        return 'informational · about / brand';
    }
    if (preg_match('/\/(blog|post|article|news|guide|learn|aprender)\//', $u)) {
        return 'informational · article';
    }
    if (preg_match('/\b(what is|how to|why|guide|comment|qué es|cómo)\b/i', $b)) {
        return 'informational · how-to / what-is';
    }
    if (preg_match('/\/(login|signup|register|signin)\b/', $u)) {
        return 'transactional · auth';
    }
    if (preg_match('/\b(vs |comparison|alternative|review|best)\b/i', $b)) {
        return 'commercial · comparison / best-of';
    }
    if ($u === '' || preg_match('/\/$/', $u) || preg_match('/\/index\.(html|php)$/', $u)) {
        return 'navigational · homepage';
    }

    return 'informational · generic';
}

/**
 * Build 5 deterministic candidates from extracted facts. Each takes a
 * different "angle" so the user can pick by voice/audience without us
 * burning LLM tokens generating them.
 *
 * @return array<int, array{title:string, meta:string, angle:string}>
 */
function rk_mcp_build_title_meta_candidates(
    string $url,
    string $h1,
    string $firstSentence,
    string $currentTitle,
    string $focusKeyword,
    string $intent,
): array {
    // Derive a clean "topic" phrase from the h1, stripped of junk.
    $topic = $h1 !== '(no h1 found)' && $h1 !== '' ? $h1 : ($currentTitle ?: $firstSentence);
    $topic = trim(preg_replace('/[\s—\-|·]+/u', ' ', $topic));
    $topic = mb_substr($topic, 0, 60);

    // Brand suffix " | Brand" — only added when the focus keyword looks like a brand (one word, capitalized).
    $brand = '';
    if ($focusKeyword !== '' && preg_match('/^[A-Z][A-Za-z0-9.]{1,30}$/', $focusKeyword)) {
        $brand = " | {$focusKeyword}";
    }

    $shortHost = '';
    if ($url !== '') {
        $h = parse_url($url, PHP_URL_HOST) ?? '';
        $shortHost = preg_replace('/^www\./i', '', $h);
    }

    $candidates = [
        // 1. Plain — topic + brand
        [
            'title' => rk_mcp_truncate(rk_mcp_capitalize_phrase($topic).$brand, 65),
            'meta' => rk_mcp_truncate("{$firstSentence} Read the full guide and apply the steps in 5 minutes.", 160),
            'angle' => 'Direct & descriptive',
        ],
        // 2. Benefit-led — promises an outcome
        [
            'title' => rk_mcp_truncate(rk_mcp_benefit_title($topic, $intent).$brand, 65),
            'meta' => rk_mcp_truncate(rk_mcp_benefit_meta($topic, $firstSentence, $intent), 160),
            'angle' => 'Benefit-led — what the reader gets',
        ],
        // 3. Question-format — best for AEO citation
        [
            'title' => rk_mcp_truncate(rk_mcp_question_title($topic, $intent).$brand, 65),
            'meta' => rk_mcp_truncate(rk_mcp_question_meta($topic, $firstSentence), 160),
            'angle' => 'Question format — AEO / AI Overviews magnet',
        ],
        // 4. Numbered / specific
        [
            'title' => rk_mcp_truncate(rk_mcp_specific_title($topic, $intent).$brand, 65),
            'meta' => rk_mcp_truncate(rk_mcp_specific_meta($topic, $firstSentence, $intent), 160),
            'angle' => 'Specific number / concrete promise',
        ],
        // 5. Free-keyword-forced — if focus keyword present, lead with it
        [
            'title' => rk_mcp_truncate(rk_mcp_keyword_first_title($topic, $focusKeyword, $intent), 65),
            'meta' => rk_mcp_truncate(rk_mcp_keyword_first_meta($topic, $firstSentence, $focusKeyword), 160),
            'angle' => $focusKeyword !== '' ? "Keyword-first: '{$focusKeyword}' in position 1" : 'Keyword-first (set focus_keyword to customize)',
        ],
    ];

    return $candidates;
}

function rk_mcp_truncate(string $s, int $max): string
{
    $s = trim(preg_replace('/\s+/', ' ', $s));
    if (mb_strlen($s) <= $max) {
        // Strip any trailing dangling brand-separator that survived
        return rtrim($s, " .,;:—-|");
    }

    // Word-aware truncate, then strip dangling punctuation including `|`
    // so the brand suffix doesn't leave the title ending with a lone pipe.
    $cut = mb_substr($s, 0, $max);
    if (preg_match('/^(.+)\s\S*$/u', $cut, $m)) {
        $cut = $m[1];
    }

    return rtrim($cut, " .,;:—-|");
}

function rk_mcp_capitalize_phrase(string $s): string
{
    return mb_convert_case(trim($s), MB_CASE_TITLE, 'UTF-8');
}

function rk_mcp_benefit_title(string $topic, string $intent): string
{
    if (str_contains($intent, 'how-to') || str_contains($intent, 'guide')) {
        return "How to {$topic} (step-by-step)";
    }
    if (str_contains($intent, 'pricing')) {
        return "{$topic} pricing — pick a plan in 30 seconds";
    }
    if (str_contains($intent, 'comparison')) {
        return "{$topic}: the only comparison you need";
    }

    return "{$topic} — the practical guide";
}

function rk_mcp_benefit_meta(string $topic, string $first, string $intent): string
{
    $core = $first ?: ucfirst($topic).' explained simply';

    return "{$core} Get the playbook + the templates. Free, no signup.";
}

function rk_mcp_question_title(string $topic, string $intent): string
{
    $t = trim($topic);
    if (preg_match('/^(what|how|why|when|where) /i', $t)) {
        return $t.'?';
    }
    if (str_contains($intent, 'how-to')) {
        return "How to {$topic}?";
    }
    if (str_contains($intent, 'pricing')) {
        return "How much does {$topic} cost?";
    }

    return "What is {$topic}? (and why it matters in 2026)";
}

function rk_mcp_question_meta(string $topic, string $first): string
{
    $core = $first ?: ucfirst($topic).' explained.';

    return "Quick answer: {$core} Plus the long version with examples and the common mistakes to avoid.";
}

function rk_mcp_specific_title(string $topic, string $intent): string
{
    if (str_contains($intent, 'pricing')) {
        return "{$topic}: 4 plans from \$0 to \$199/mo";
    }
    if (str_contains($intent, 'comparison')) {
        return "{$topic}: 7 alternatives compared (2026)";
    }
    if (str_contains($intent, 'how-to')) {
        return "{$topic} in 5 steps (10 minutes)";
    }
    if (str_contains($intent, 'about')) {
        return "About us — built since 2009";
    }

    return "{$topic} — the 2026 update";
}

function rk_mcp_specific_meta(string $topic, string $first, string $intent): string
{
    $intro = $first ?: ucfirst($topic);

    return "{$intro} Including 5 examples, 3 common mistakes and the script we use ourselves.";
}

function rk_mcp_keyword_first_title(string $topic, string $kw, string $intent): string
{
    if ($kw === '') {
        return ucfirst($topic).' — what you need to know';
    }

    return "{$kw} — {$topic}";
}

function rk_mcp_keyword_first_meta(string $topic, string $first, string $kw): string
{
    if ($kw === '') {
        return $first.' Practical, no fluff.';
    }

    return "{$kw} done right — {$first} Practical examples, no fluff, free to apply.";
}
