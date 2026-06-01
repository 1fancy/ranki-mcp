<?php
declare(strict_types=1);

/**
 * explain_seo_terms — plain-English glossary of SEO + AEO + technical-SEO
 * terms vibe-coders run into when they start optimizing.
 *
 * The user's AI calls this when the user says "I don't know what these
 * acronyms mean" or "explain what AEO is." Returns a sorted list of 40
 * core terms with 1-2-sentence definitions + practical context.
 *
 * Optional arg: `category` (basics | aeo | technical | analytics | all).
 * Default: all.
 */
return function (array $args, string $apiKey): array {
    $cat = strtolower(trim((string) ($args['category'] ?? 'all')));

    $glossary = [
        // === BASICS ===
        ['name' => 'SEO', 'cat' => 'basics', 'def' => 'Search Engine Optimization — the practice of making pages easier for Google, Bing, and AI search engines to understand, rank, and cite. Covers technical, on-page, and off-page signals.'],
        ['name' => 'AEO', 'cat' => 'basics', 'def' => 'Answer Engine Optimization — making your site cite-able by ChatGPT, Claude, Perplexity, and Google AI Overviews. Different signals than classic SEO: FAQPage schema, definitional intros, author bylines, llms.txt presence.'],
        ['name' => 'GEO', 'cat' => 'basics', 'def' => 'Generative Engine Optimization — the same concept as AEO. Some practitioners prefer "GEO" because the AI engines are generative; the techniques are identical.'],
        ['name' => 'SERP', 'cat' => 'basics', 'def' => 'Search Engine Results Page — the page Google shows when someone searches. Made up of ads, blue links, featured snippets, knowledge panels, AI Overviews, and (sometimes) maps + images.'],
        ['name' => 'CTR', 'cat' => 'basics', 'def' => 'Click-Through Rate — the percentage of people who saw your result in the SERP and actually clicked it. Higher CTR = better title + meta description match to the searcher\'s intent.'],
        ['name' => 'Search Intent', 'cat' => 'basics', 'def' => 'What the user was actually trying to do when they searched. Buckets: informational ("what is X"), commercial ("best X for Y"), transactional ("buy X", "X not working"). Match your content to the intent or you don\'t rank.'],
        ['name' => 'Focus Keyword', 'cat' => 'basics', 'def' => 'The primary phrase a single page targets. Should appear in the title, h1, first paragraph, and at least one h2. One focus keyword per page — pages that try to rank for everything rank for nothing.'],
        ['name' => 'Long-Tail Keyword', 'cat' => 'basics', 'def' => 'A specific multi-word query with lower search volume but higher conversion intent. Example: "react native ios build failed code signing" instead of "react native". Lower competition, more buyer-shaped traffic.'],
        ['name' => 'Keyword Gap', 'cat' => 'basics', 'def' => 'A keyword your competitors rank for but you don\'t. Finding these is the fastest way to grow because demand is already proven.'],
        ['name' => 'Backlink', 'cat' => 'basics', 'def' => 'A link from another site to yours. Google treats them as votes — more high-authority backlinks = better rankings. Don\'t buy them; they\'ll get you penalized.'],

        // === AEO / AI search ===
        ['name' => 'FAQPage', 'cat' => 'aeo', 'def' => 'A JSON-LD schema type for Q&A content. The single highest-citation signal for ChatGPT and Perplexity. Wrap 3+ questions and answers on any page in FAQPage markup and AI search engines treat them as quotable answer-units.'],
        ['name' => 'Article schema', 'cat' => 'aeo', 'def' => 'JSON-LD that tells search engines a page is an article (not a product page, not a category page). Should include headline, author, datePublished, and image. AEO won\'t cite you without it.'],
        ['name' => 'Definitional Intro', 'cat' => 'aeo', 'def' => 'The first paragraph of any article should be ≤80 words and start with "X is …". LLMs lift this sentence verbatim as the answer. Without it your page gets paraphrased; with it you get cited.'],
        ['name' => 'llms.txt', 'cat' => 'aeo', 'def' => 'An emerging Markdown file at /llms.txt that tells AI crawlers what your site is about and how to cite you. Similar idea to robots.txt but for LLMs. Adoption growing fast since 2025.'],
        ['name' => 'GPTBot · ClaudeBot · PerplexityBot', 'cat' => 'aeo', 'def' => 'The crawler user-agents OpenAI, Anthropic, and Perplexity use. If your robots.txt blocks them (default for many CMS), your site will never appear in AI search citations. Allow them explicitly.'],
        ['name' => 'Google-Extended', 'cat' => 'aeo', 'def' => 'The user-agent token Google uses to decide whether to train Gemini on your content. Allowing it gets you into AI Overviews citations; blocking it keeps you out of training but also out of AI Overviews.'],
        ['name' => 'Answer-Style Heading', 'cat' => 'aeo', 'def' => 'An H2 or H3 phrased as a question ("What is X?", "How does Y work?", "Why does Z matter?"). AI search engines extract these as answer-units. Recommended: 2+ per page.'],
        ['name' => 'AI Overviews', 'cat' => 'aeo', 'def' => 'Google\'s AI-generated summaries that appear above the blue links for many searches. Stole ~30% of click-through traffic from classic SERPs by 2026. Optimizing for them is non-optional.'],
        ['name' => 'Citation', 'cat' => 'aeo', 'def' => 'When ChatGPT, Claude, Perplexity, or Google AI Overviews link to your site as a source for an answer. The AEO equivalent of "ranking in position 1" but more valuable — clicks from citations convert ~3-5× better than SERP clicks.'],

        // === TECHNICAL ===
        ['name' => 'robots.txt', 'cat' => 'technical', 'def' => 'A plain-text file at your site root that tells crawlers what they can and cannot access. Every public site needs one. Blocking AI crawlers here costs you AEO traffic.'],
        ['name' => 'sitemap.xml', 'cat' => 'technical', 'def' => 'An XML file listing every URL on your site that you want indexed. Submit to Google Search Console. Without it Google has to discover your pages by following links — slow and incomplete.'],
        ['name' => 'JSON-LD', 'cat' => 'technical', 'def' => 'JavaScript Object Notation for Linked Data — the format Google + AI engines parse to understand what each page IS (an article, a product, a recipe, an FAQ). Lives in a `<script type="application/ld+json">` tag in the page head.'],
        ['name' => 'Schema.org', 'cat' => 'technical', 'def' => 'The shared vocabulary used inside JSON-LD. Types include Article, FAQPage, Product, Organization, Breadcrumbs, HowTo, Recipe, Event. The contract every search engine reads.'],
        ['name' => 'Canonical Tag', 'cat' => 'technical', 'def' => 'A `<link rel="canonical">` in the head telling Google which URL is the "official" version when the same content exists at multiple URLs (e.g. `?utm=...` variants). Without it Google guesses, and may pick wrong.'],
        ['name' => 'Title Tag', 'cat' => 'technical', 'def' => 'The `<title>` element — what shows in browser tabs and as the blue clickable headline in Google. Sweet spot: 50-65 characters, primary keyword front-loaded.'],
        ['name' => 'Meta Description', 'cat' => 'technical', 'def' => 'The `<meta name="description">` in the head. Doesn\'t directly affect ranking, but Google often uses it as the snippet under your title in SERPs. Sweet spot: 140-160 characters.'],
        ['name' => 'H1', 'cat' => 'technical', 'def' => 'The single main heading on a page. There should be exactly one. Should contain the primary keyword. Visually it\'s the page\'s "title" the user reads first.'],
        ['name' => 'Alt Text', 'cat' => 'technical', 'def' => 'The `alt` attribute on `<img>` tags. Describes the image for screen readers and Google\'s image crawler. Required for accessibility; helps image search rankings.'],
        ['name' => 'OpenGraph', 'cat' => 'technical', 'def' => 'The `<meta property="og:*">` tags that control how your page renders when shared on Twitter, Facebook, LinkedIn, Slack, Discord. og:title, og:description, og:image, og:url, og:type are the must-haves.'],
        ['name' => 'Core Web Vitals', 'cat' => 'technical', 'def' => 'Google\'s perf scorecard. LCP (largest contentful paint, target <2.5s), INP (interaction-to-next-paint, target <200ms), CLS (cumulative layout shift, target <0.1). Soft ranking factor — bad scores demote you.'],
        ['name' => 'Lighthouse', 'cat' => 'technical', 'def' => 'Chrome DevTools tool that scores any page on Performance, Accessibility, Best Practices, and SEO. Free, baked into Chrome. Run it before deploying anything.'],
        ['name' => 'Mobile-First Indexing', 'cat' => 'technical', 'def' => 'Google indexes the mobile version of your site, not the desktop one. If your mobile site is missing content the desktop has, Google can\'t see that content. As of 2024 this applies to nearly every site.'],

        // === ANALYTICS / RANKING ===
        ['name' => 'GSC', 'cat' => 'analytics', 'def' => 'Google Search Console — Google\'s free tool showing which queries triggered your site in SERPs, your impressions/clicks/CTR, your indexed pages, technical errors. The single most important free SEO tool. Connect it day 1.'],
        ['name' => 'E-E-A-T', 'cat' => 'analytics', 'def' => 'Experience, Expertise, Authoritativeness, Trustworthiness — Google\'s framework for evaluating content quality. Author bylines, citations, "About" pages, expert credentials all feed E-E-A-T. Critical for YMYL topics (medical, financial).'],
        ['name' => 'YMYL', 'cat' => 'analytics', 'def' => 'Your Money or Your Life — Google\'s name for topics that could affect a user\'s health, finances, safety, or wellbeing. Held to higher E-E-A-T standards. Includes medical, legal, financial, insurance, government.'],
        ['name' => 'Featured Snippet', 'cat' => 'analytics', 'def' => '"Position 0" — the boxed answer Google sometimes shows above the regular blue links. Wins traffic but also AI Overviews increasingly replace these.'],
        ['name' => 'Knowledge Panel', 'cat' => 'analytics', 'def' => 'The boxed entity card on the right of Google SERPs (e.g. when you search a company name). Driven by structured data + Wikipedia + Knowledge Graph. Hard to influence directly.'],
        ['name' => 'Dwell Time', 'cat' => 'analytics', 'def' => 'How long a searcher stays on your page after clicking from Google before bouncing back. Long dwell = good content match; short dwell = bad. Google uses it as a quality signal.'],
        ['name' => 'Bounce Rate', 'cat' => 'analytics', 'def' => 'Percentage of visitors who view exactly one page then leave. High bounce = content didn\'t match intent. Watch this in GA4 / Plausible.'],
        ['name' => 'Indexable Page', 'cat' => 'analytics', 'def' => 'A page Google can crawl AND index (not blocked by robots.txt, not `<meta name="robots" content="noindex">`). Check yours in GSC → Pages → "Why pages aren\'t indexed."'],

        // === PENALTY / HYGIENE ===
        ['name' => 'Helpful Content Update', 'cat' => 'penalty', 'def' => 'Google\'s recurring algorithm update (first launched 2022, ongoing) that demotes sites where content reads like it was written for SEO rather than for humans. AI-generated content fails if it\'s not edited + fact-checked + personalized.'],
        ['name' => 'Core Update', 'cat' => 'penalty', 'def' => 'Google\'s broad algorithm overhauls, typically 3-4× per year. Can move rankings 10-50 positions overnight. No "fix" except improving content quality.'],
        ['name' => 'Manual Action', 'cat' => 'penalty', 'def' => 'A Google reviewer manually penalized your site (vs. an algorithm doing it). Shows in GSC under "Manual actions." Common causes: buying links, doorway pages, thin AI content. Removing the issue + filing reconsideration request is the path back.'],
        ['name' => 'Doorway Page', 'cat' => 'penalty', 'def' => 'Pages built solely to rank for keyword variations that funnel users to the same destination. Explicitly against Google guidelines. Common pitfall when AI-generating content at scale without editorial review.'],
        ['name' => 'Cloaking', 'cat' => 'penalty', 'def' => 'Serving different content to Google\'s crawler vs. real users. Auto-bans your site. Easy to do accidentally with bad geo-redirects or paywalls.'],
        ['name' => 'Toxic Backlinks', 'cat' => 'penalty', 'def' => 'Backlinks from spammy, irrelevant, or penalized sites — often pointing at you without you knowing. Can hurt rankings. Disavow them via Google Search Console\'s Disavow tool.'],
    ];

    // Filter by category if specified
    if (! in_array($cat, ['all', '', 'basics', 'aeo', 'technical', 'analytics', 'penalty'], true)) {
        $cat = 'all';
    }
    if ($cat !== 'all' && $cat !== '') {
        $glossary = array_values(array_filter($glossary, fn ($g) => $g['cat'] === $cat));
    }

    // Group by category for the output
    $byCat = [];
    foreach ($glossary as $g) {
        $byCat[$g['cat']][] = $g;
    }

    $catLabels = [
        'basics' => 'SEO + AEO basics',
        'aeo' => 'Answer Engine Optimization (AEO / AI search)',
        'technical' => 'Technical SEO',
        'analytics' => 'Analytics, ranking signals & E-E-A-T',
        'penalty' => 'Penalties, hygiene & what to avoid',
    ];
    $catOrder = ['basics', 'aeo', 'technical', 'analytics', 'penalty'];

    $out = "## SEO + AEO glossary — what every vibe-coder should know in 2026\n\n";
    $out .= "Below is the jargon you'll hit reading any SEO article, audit report, or Ranki MCP tool output. The Ranki MCP tools all reference these — when an audit says \"FAQPage schema missing\" or \"add a definitional intro,\" this is where to look.\n\n";

    foreach ($catOrder as $section) {
        if (! isset($byCat[$section])) {
            continue;
        }
        $out .= "### {$catLabels[$section]}\n\n";
        // Stable alphabetical inside each section
        usort($byCat[$section], fn ($a, $b) => strnatcasecmp($a['name'], $b['name']));
        foreach ($byCat[$section] as $g) {
            $out .= "**{$g['name']}** — {$g['def']}\n\n";
        }
    }

    $out .= "---\n\n";
    $out .= "## Next steps\n\n";
    $out .= "- See a term used in context? Call `audit_seo(url)` or `audit_aeo(url)` on any URL — each failed check explains which of the above terms it relates to.\n";
    $out .= "- Need an action plan? Call `seo_starter_kit(domain)` for the four-file SEO foundation, or `find_topic_ideas(url)` for content direction.\n";
    $out .= "- Want the long-form versions? Read https://ranki.io/learn/aeo-guide and https://ranki.io/learn/seo-guide-2026.\n";

    return rk_mcp_text_content($out);
};
