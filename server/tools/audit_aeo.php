<?php
declare(strict_types=1);

/**
 * audit_aeo — checks a URL for the structural signals that ChatGPT,
 * Perplexity, Claude, and Google AI Overviews use to pick citations.
 * Returns scorecard + per-check fix recipes the user's own Claude can
 * apply against their codebase.
 */
return function (array $args, string $apiKey): array {
    $url = (string) ($args['url'] ?? '');
    if ($url === '') {
        throw new \RuntimeException('url is required');
    }

    $fetched = rk_mcp_fetch_url($url);
    if ($fetched['status'] >= 400) {
        return rk_mcp_text_content("Could not fetch {$url} (HTTP {$fetched['status']}). Make sure the URL is public and reachable.");
    }

    $html = $fetched['html'];
    $base = parse_url($fetched['final_url']);
    $origin = ($base['scheme'] ?? 'https').'://'.($base['host'] ?? '');

    $checks = [];

    // 1. JSON-LD presence (Article, BlogPosting, or FAQPage)
    $hasArticle = (bool) preg_match('/"@type"\s*:\s*"(Article|BlogPosting|NewsArticle)"/i', $html);
    $hasFAQ = (bool) preg_match('/"@type"\s*:\s*"FAQPage"/i', $html);
    $checks[] = [
        'name' => 'Article schema',
        'pass' => $hasArticle,
        'fix' => $hasArticle ? null : 'Add JSON-LD <script type="application/ld+json"> with @type="Article" or "BlogPosting", including headline, author, datePublished, image.',
    ];
    $checks[] = [
        'name' => 'FAQPage schema',
        'pass' => $hasFAQ,
        'fix' => $hasFAQ ? null : 'If your page answers 3+ common questions, add FAQPage JSON-LD. AEO engines love it — it\'s the single biggest citation signal.',
    ];

    // 2. Definitional intro (first <p> under 80 words starting with noun-phrase)
    preg_match('/<p[^>]*>(.*?)<\/p>/is', $html, $pMatch);
    $firstP = isset($pMatch[1]) ? trim(strip_tags($pMatch[1])) : '';
    $wordCount = $firstP === '' ? 0 : str_word_count($firstP);
    $intro_ok = $wordCount > 0 && $wordCount <= 80;
    $checks[] = [
        'name' => 'Definitional intro (<=80 words)',
        'pass' => $intro_ok,
        'fix' => $intro_ok ? null : 'Rewrite the first paragraph as a concise definition (≤80 words) starting with the topic noun-phrase, e.g. "Answer Engine Optimization is …". LLMs quote this exact sentence.',
    ];

    // 3. Author byline
    $hasAuthor = (bool) preg_match('/<meta[^>]+name=["\']author["\']/i', $html)
        || preg_match('/"author"\s*:/i', $html)
        || preg_match('/rel=["\']author["\']/i', $html);
    $checks[] = [
        'name' => 'Author byline',
        'pass' => (bool) $hasAuthor,
        'fix' => $hasAuthor ? null : 'Add <meta name="author" content="…"> OR include the author in your Article JSON-LD ("author":{"@type":"Person","name":"…"}). E-E-A-T signal.',
    ];

    // 4. llms.txt probe
    $llms = rk_mcp_fetch_url($origin.'/llms.txt', 5);
    $has_llms = $llms['status'] === 200 && strlen($llms['html']) > 10;
    $checks[] = [
        'name' => 'llms.txt present',
        'pass' => $has_llms,
        'fix' => $has_llms ? null : 'Use the generate_llms_txt tool to create one. Place at /llms.txt at site root.',
    ];

    // 5. robots.txt allows AI crawlers
    $robots = rk_mcp_fetch_url($origin.'/robots.txt', 5);
    $robotsBody = strtolower($robots['html'] ?? '');
    $aiBots = ['gptbot', 'claudebot', 'perplexitybot', 'google-extended'];
    $aiAllowed = true;
    foreach ($aiBots as $bot) {
        // crude: a "user-agent: {bot}" followed by a "disallow: /" within 200 chars = blocked
        if (preg_match('/user-agent:\s*'.preg_quote($bot, '/').'.{0,200}?disallow:\s*\//is', $robotsBody)) {
            $aiAllowed = false;
            break;
        }
    }
    $checks[] = [
        'name' => 'AI crawlers allowed in robots.txt',
        'pass' => $aiAllowed,
        'fix' => $aiAllowed ? null : 'robots.txt blocks at least one AI crawler. Use generate_robots_txt tool with allow_ai=true to permit GPTBot/ClaudeBot/PerplexityBot/Google-Extended.',
    ];

    // 6. Answer-style headings
    preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/is', $html, $hMatches);
    $headings = array_map(fn ($x) => strtolower(strip_tags($x)), $hMatches[1] ?? []);
    $answerHeadings = array_filter($headings, fn ($h) => preg_match('/^(what|how|why|when|where|is |are |can |does )/i', trim($h)));
    $hasAnswerH = count($answerHeadings) >= 2;
    $checks[] = [
        'name' => 'Answer-style headings (≥2 questions)',
        'pass' => $hasAnswerH,
        'fix' => $hasAnswerH ? null : 'Reword 2+ H2/H3 as questions ("What is X?", "How does X work?", "Why does X matter?"). LLMs extract these as answer-units.',
    ];

    // 7. Structured tables
    $hasTable = (bool) preg_match('/<table[\s>]/i', $html);
    $checks[] = [
        'name' => 'Comparison table',
        'pass' => $hasTable,
        'fix' => $hasTable ? null : 'For "X vs Y" or feature/spec content, add a real <table>. Tables are the highest-citation HTML element for AI Overviews.',
    ];

    // Score
    $passed = count(array_filter($checks, fn ($c) => $c['pass']));
    $total = count($checks);
    $score = $total > 0 ? (int) round(($passed / $total) * 100) : 0;

    $report = "AEO Audit for {$url}\nScore: {$score}/100 ({$passed}/{$total} checks passed)\n\n";
    foreach ($checks as $c) {
        $report .= ($c['pass'] ? '✅' : '❌').' '.$c['name']."\n";
        if (! $c['pass'] && $c['fix']) {
            $report .= '   Fix: '.$c['fix']."\n";
        }
    }

    return rk_mcp_text_content($report);
};
