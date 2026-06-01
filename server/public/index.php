<?php
/**
 * mcp.ranki.io — dual-purpose entry point.
 *
 * GET  → dark "MCP terminal" themed landing page (SEO + onboarding)
 * POST → delegates to /dispatch.php (JSON-RPC 2.0 server)
 */
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    require __DIR__.'/dispatch.php';
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ranki MCP — SEO + AEO advisor for Claude Code, Cursor, ChatGPT</title>
<meta name="description" content="Free MCP server that audits your site for SEO + AEO, generates sitemap.xml / llms.txt / robots.txt, and tells Claude / Cursor exactly what to fix — all using your own AI credits. Built for vibe-coders who want their site cited by ChatGPT, Claude, Perplexity, and Google AI Overviews.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://mcp.ranki.io/">
<meta name="theme-color" content="#0a0a0a">
<meta name="color-scheme" content="dark">

<meta property="og:type" content="website">
<meta property="og:title" content="Ranki MCP — SEO + AEO advisor for Claude Code, Cursor, ChatGPT">
<meta property="og:description" content="Free MCP server: audit SEO + AEO, generate sitemap.xml / llms.txt / robots.txt directly from your IDE.">
<meta property="og:url" content="https://mcp.ranki.io/">
<meta property="og:site_name" content="Ranki MCP">
<meta property="og:image" content="https://ranki.io/assets/images/favicon-512.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Ranki MCP — SEO + AEO advisor for Claude / Cursor">
<meta name="twitter:description" content="Free MCP server: audit SEO + AEO, generate sitemap.xml / llms.txt / robots.txt from your IDE.">

<link rel="icon" type="image/svg+xml" href="https://ranki.io/assets/svg/favicon.svg">
<link rel="icon" type="image/png" sizes="32x32" href="https://ranki.io/assets/images/favicon-32.png">
<link rel="apple-touch-icon" sizes="180x180" href="https://ranki.io/assets/images/favicon-180.png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Ranki Black — the brand title font, served from ranki.io so the MCP
   site looks like part of the same product. */
@font-face {
  font-family: 'Ranki Black';
  src: url('https://ranki.io/assets/fonts/ranki_black.woff') format('woff'),
       url('https://ranki.io/assets/fonts/ranki_black.ttf') format('truetype');
  font-weight: 900;
  font-display: swap;
}

:root {
  --bg:#06070a;
  --bg-2:#0d0f14;
  --bg-3:#13161d;
  --ink:#f4f5f7;
  --ink-2:#c4c7d0;
  --ink-3:#8b8f99;
  --ink-4:#5b6068;
  --orange:#f7906c;
  --orange-2:#ff7a3d;
  --orange-glow:rgba(247,144,108,.18);
  --orange-soft:rgba(247,144,108,.08);
  --line:rgba(255,255,255,.06);
  --line-2:rgba(255,255,255,.1);
  --line-3:rgba(255,255,255,.18);
  --green:#9be5a6;
  --container:1180px;
}

*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
html,body{background:var(--bg);color:var(--ink);font-family:'Inter',system-ui,sans-serif;line-height:1.65;-webkit-font-smoothing:antialiased;font-feature-settings:'ss01','cv11'}
::selection{background:var(--orange);color:#000}
a{color:var(--orange);text-decoration:none;transition:color .15s}
a:hover{color:var(--orange-2)}
img{max-width:100%;height:auto;display:block}

.container{max-width:var(--container);margin:0 auto;padding:0 1.5rem}
.mono{font-family:'JetBrains Mono',ui-monospace,monospace;font-size:.92em}
.muted{color:var(--ink-3)}

/* ============== ANIMATED BACKGROUND ==============
   A subtle MCP "data flow" pattern: faint dots forming a grid, with a
   slow-pulsing orange aurora over the hero. Pure CSS — no canvas, no
   perf cost. The dots fade out by 60vh so they don't fight content. */
.bg-grid{position:fixed;inset:0;pointer-events:none;z-index:0;background-image:radial-gradient(circle at 1px 1px,rgba(255,255,255,.04) 1px,transparent 0);background-size:32px 32px;mask-image:linear-gradient(to bottom,#000 0%,#000 50%,transparent 80%);-webkit-mask-image:linear-gradient(to bottom,#000 0%,#000 50%,transparent 80%)}
.bg-aurora{position:fixed;top:-200px;left:50%;transform:translateX(-50%);width:1400px;height:900px;pointer-events:none;z-index:0;
  background:
    radial-gradient(ellipse 600px 300px at 30% 30%,var(--orange-glow),transparent 70%),
    radial-gradient(ellipse 500px 400px at 70% 50%,rgba(247,122,61,.12),transparent 60%);
  filter:blur(40px);
  animation:aurora-drift 24s ease-in-out infinite;
}
@keyframes aurora-drift{0%,100%{transform:translateX(-50%) translateY(0) scale(1)}50%{transform:translateX(-50%) translateY(30px) scale(1.05)}}

main, header, footer{position:relative;z-index:1}

/* ============== HEADER ============== */
.site-header{position:sticky;top:0;background:rgba(6,7,10,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-bottom:1px solid var(--line);z-index:50}
.hdr{display:flex;align-items:center;justify-content:space-between;padding:.95rem 0;gap:1rem}

/* Logo: white Ranki wordmark (PNG inverted with filter) + "MCP" pill */
.logo{display:inline-flex;align-items:center;gap:.6rem;text-decoration:none;color:var(--ink)}
.logo img{height:24px;width:auto;
  /* brightness(0) invert(1) flips the orange+black logo to pure white.
     The user explicitly asked for the logo to be white on the dark bg —
     accepted that "Ranki" loses its orange in exchange for legibility. */
  filter:brightness(0) invert(1);
  opacity:.95
}
.logo .pill{font-family:'JetBrains Mono',monospace;font-weight:600;font-size:.7rem;color:var(--orange);letter-spacing:.08em;padding:.22rem .55rem;background:var(--orange-soft);border-radius:99px;border:1px solid rgba(247,144,108,.3);text-transform:uppercase;line-height:1}

.nav{display:flex;gap:1.6rem;align-items:center}
.nav a{color:var(--ink-2);font-size:.92rem;font-weight:500}
.nav a:hover{color:var(--ink)}
@media (max-width:880px){.nav a:not(.btn){display:none}}

.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.1rem;border-radius:8px;font-weight:600;font-size:.9rem;border:1px solid transparent;transition:all .15s;font-family:inherit;cursor:pointer;line-height:1}
/* Primary CTA: orange gradient bg, white text (per user requirement —
   stays readable against the orange and matches the rest of the site). */
.btn-primary{background:linear-gradient(135deg,var(--orange) 0%,var(--orange-2) 100%);color:#fff !important;box-shadow:0 4px 18px -4px rgba(247,144,108,.5)}
.btn-primary:hover{background:linear-gradient(135deg,var(--orange-2) 0%,#e8651f 100%);color:#fff !important;box-shadow:0 6px 24px -4px rgba(247,144,108,.7);transform:translateY(-1px)}
.btn-ghost{border-color:var(--line-2);color:#fff !important;background:transparent}
.btn-ghost:hover{border-color:var(--orange);color:#fff !important;background:var(--orange-soft)}

/* ============== HERO ============== */
.hero{padding:6rem 0 5rem;text-align:center;position:relative}
.eyebrow{display:inline-flex;align-items:center;gap:.5rem;padding:.4rem 1rem;background:var(--bg-3);border:1px solid var(--line-2);border-radius:99px;color:var(--ink-2);font-size:.82rem;font-weight:500;letter-spacing:0;margin-bottom:1.5rem}
.eyebrow .live-dot{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 8px var(--green);animation:pulse 2.4s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:.5;transform:scale(.85)}50%{opacity:1;transform:scale(1)}}

h1{font-family:'Ranki Black','Inter',sans-serif;font-size:clamp(2.4rem,6.5vw,4.4rem);font-weight:900;line-height:1.04;letter-spacing:-.025em;margin-bottom:1.5rem}
h1 .accent{background:linear-gradient(180deg,var(--orange) 30%,#fff 130%);-webkit-background-clip:text;background-clip:text;color:transparent}
.lede{font-size:clamp(1.05rem,2vw,1.22rem);color:var(--ink-2);max-width:680px;margin:0 auto 2.2rem;line-height:1.55}
.cta-row{display:flex;gap:.9rem;justify-content:center;flex-wrap:wrap;margin-bottom:3rem}
.btn-xl{padding:.95rem 1.6rem;font-size:1rem;border-radius:10px}

/* Hero terminal — a real-looking REPL block, glassmorphic */
.hero-term{max-width:780px;margin:0 auto;background:linear-gradient(180deg,rgba(13,15,20,.95),rgba(13,15,20,.7));border:1px solid var(--line-2);border-radius:14px;overflow:hidden;box-shadow:0 30px 80px -30px rgba(247,144,108,.25),0 0 0 1px var(--line);backdrop-filter:blur(20px)}
.hero-term-bar{display:flex;align-items:center;gap:.45rem;padding:.6rem 1rem;background:rgba(0,0,0,.25);border-bottom:1px solid var(--line)}
.hero-term-bar .d{width:11px;height:11px;border-radius:50%;background:var(--line-3)}
.hero-term-bar .d.r{background:#ff5f56}.hero-term-bar .d.y{background:#ffbd2e}.hero-term-bar .d.g{background:#27c93f}
.hero-term-bar .where{margin-left:auto;font-family:'JetBrains Mono',monospace;font-size:.72rem;color:var(--ink-3)}
.hero-term-body{padding:1.4rem 1.5rem;font-family:'JetBrains Mono',monospace;font-size:.88rem;line-height:1.85;text-align:left;color:var(--ink-2);min-height:200px}
.hero-term-body .p{color:var(--orange)}
.hero-term-body .you{color:var(--ink)}
.hero-term-body .ai{color:var(--ink-2)}
.hero-term-body .tag{color:#94d2ff}
.hero-term-body .ok{color:var(--green)}
.hero-term-body .dim{color:var(--ink-3)}
.hero-term-body .cursor{display:inline-block;width:9px;height:1em;background:var(--orange);vertical-align:-2px;margin-left:2px;animation:blink 1s steps(2,end) infinite}
@keyframes blink{50%{opacity:0}}

/* ============== SECTIONS ============== */
section{padding:5rem 0;position:relative}
.section-head{text-align:center;margin-bottom:3rem;max-width:680px;margin-left:auto;margin-right:auto}
.section-head h2{font-family:'Ranki Black','Inter',sans-serif;font-size:clamp(1.8rem,4vw,2.6rem);font-weight:900;letter-spacing:-.02em;line-height:1.15;margin-bottom:.8rem}
.section-head p{color:var(--ink-2);font-size:1.02rem;line-height:1.6}

/* ============== TOOLS — terminal list, NOT cards ============== */
.tools-block{max-width:920px;margin:0 auto;border:1px solid var(--line);border-radius:14px;background:linear-gradient(180deg,rgba(13,15,20,.9),rgba(6,7,10,.95));overflow:hidden}
.tools-head{display:flex;align-items:center;gap:.7rem;padding:.9rem 1.5rem;background:rgba(0,0,0,.25);border-bottom:1px solid var(--line);font-family:'JetBrains Mono',monospace;font-size:.78rem;color:var(--ink-3);letter-spacing:.04em;text-transform:uppercase}
.tools-head .count{margin-left:auto;color:var(--orange);font-weight:600}
.tool-row{display:grid;grid-template-columns:auto 1fr auto;gap:1.1rem;padding:1.15rem 1.5rem;border-bottom:1px solid var(--line);align-items:start;transition:background .15s}
.tool-row:last-child{border-bottom:none}
.tool-row:hover{background:rgba(247,144,108,.04)}
.tool-row .icon{width:28px;height:28px;border-radius:7px;background:var(--orange-soft);border:1px solid rgba(247,144,108,.25);display:flex;align-items:center;justify-content:center;color:var(--orange);font-family:'JetBrains Mono',monospace;font-weight:700;font-size:.78rem;flex-shrink:0;margin-top:.15rem}
.tool-row h3{font-family:'JetBrains Mono',monospace;font-weight:600;font-size:.98rem;color:var(--ink);margin-bottom:.3rem;letter-spacing:-.01em}
.tool-row p{color:var(--ink-2);font-size:.9rem;line-height:1.55;margin:0}
.tool-row .tag{font-family:'JetBrains Mono',monospace;font-size:.68rem;padding:.2rem .55rem;border-radius:4px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;align-self:flex-start;margin-top:.2rem;background:rgba(155,229,166,.1);color:var(--green);border:1px solid rgba(155,229,166,.2)}
.tool-row .tag.key{background:var(--orange-soft);color:var(--orange);border-color:rgba(247,144,108,.3)}

/* ============== INSTALL ============== */
.install-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;max-width:980px;margin:0 auto}
@media (max-width:780px){.install-grid{grid-template-columns:1fr}}
.install-block{border:1px solid var(--line);border-radius:12px;overflow:hidden;background:rgba(13,15,20,.6);backdrop-filter:blur(8px)}
.install-head{padding:.85rem 1.2rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:.55rem;font-size:.92rem;font-weight:600;color:var(--ink);background:rgba(0,0,0,.2)}
.install-body{padding:1.2rem}
.install-body p.path{font-family:'JetBrains Mono',monospace;font-size:.78rem;color:var(--ink-3);margin-bottom:.7rem}

pre.code{background:#020306;border:1px solid var(--line);border-left:3px solid var(--orange);border-radius:8px;padding:1rem 1.2rem;font-family:'JetBrains Mono',monospace;font-size:.82rem;line-height:1.7;color:#d4d4d4;overflow-x:auto;text-align:left;position:relative;margin:0}
pre.code .k{color:#e8e8e8}
pre.code .s{color:var(--orange)}
pre.code .v{color:#94d2ff}
pre.code .c{color:var(--ink-4)}
.copy-btn{position:absolute;top:.5rem;right:.55rem;background:var(--bg-3);border:1px solid var(--line-2);color:var(--ink-3);padding:.28rem .6rem;border-radius:5px;font-size:.7rem;font-family:'JetBrains Mono',monospace;font-weight:600;cursor:pointer;letter-spacing:.04em;text-transform:uppercase;transition:all .15s}
.copy-btn:hover{color:var(--orange);border-color:var(--orange)}

/* ============== "WHY" section — animated SVG flow diagram ============== */
.why-flow{max-width:880px;margin:0 auto;padding:2.5rem;background:linear-gradient(180deg,rgba(13,15,20,.6),transparent);border:1px solid var(--line);border-radius:16px}
.why-flow svg{width:100%;height:auto;display:block}
.flow-label{font-family:'JetBrains Mono',monospace;font-size:.78rem;letter-spacing:.04em;text-transform:uppercase}

/* ============== FAQ accordion ============== */
.faq{max-width:760px;margin:0 auto}
.faq-item{border-bottom:1px solid var(--line);padding:1.3rem 0}
.faq-q{font-weight:600;color:var(--ink);font-size:1.02rem;display:flex;justify-content:space-between;align-items:center;cursor:pointer;gap:1rem;letter-spacing:-.005em;line-height:1.5}
.faq-q::after{content:'+';color:var(--orange);font-size:1.5rem;font-weight:300;line-height:1;flex-shrink:0}
.faq-a{color:var(--ink-2);margin-top:1rem;font-size:.96rem;line-height:1.7;display:none}
.faq-item.open .faq-q::after{content:'−'}
.faq-item.open .faq-a{display:block}

/* ============== FOOTER ============== */
footer{padding:4rem 0 2.5rem;border-top:1px solid var(--line);margin-top:3rem}
.foot{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:2rem}
@media (max-width:680px){.foot{grid-template-columns:1fr 1fr}}
.foot h4{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink-3);margin-bottom:.9rem}
.foot a{display:block;color:var(--ink-2);font-size:.88rem;margin-bottom:.55rem}
.foot a:hover{color:var(--ink)}
.foot p.tag{color:var(--ink-3);font-size:.82rem;line-height:1.6;max-width:300px;margin-top:.8rem}
.foot-bottom{margin-top:3rem;padding-top:1.5rem;border-top:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;color:var(--ink-4);font-size:.8rem;flex-wrap:wrap;gap:1rem}

/* JSON-LD machine-readable */
</style>

<script type="application/ld+json">
{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Ranki MCP","url":"https://mcp.ranki.io/","applicationCategory":"DeveloperApplication","operatingSystem":"Web","description":"Free Model Context Protocol server that audits SEO + AEO and generates sitemap.xml, llms.txt, robots.txt directly from Claude Code, Cursor, ChatGPT Desktop.","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"}}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[
  {"@type":"Question","name":"What is the Ranki MCP server?","acceptedAnswer":{"@type":"Answer","text":"A Model Context Protocol server that gives Claude Code, Claude Desktop, Cursor, and ChatGPT Desktop the ability to audit any URL for SEO and AEO, generate sitemap.xml / llms.txt / robots.txt files, and read your Ranki.io projects — inline as you code."}},
  {"@type":"Question","name":"Does Ranki MCP use my Claude credits or yours?","acceptedAnswer":{"@type":"Answer","text":"Yours. Ranki MCP only returns structured advice and ready-to-deploy files. Your Claude / Cursor evaluates them against your codebase using your own credits. We never spend a single LLM token on your behalf."}},
  {"@type":"Question","name":"Do I need a Ranki.io account?","acceptedAnswer":{"@type":"Answer","text":"No for the eight advisor tools — they work free, rate-limited to five calls per IP per day. Two tools (list_projects, get_article) require a free Ranki.io API key for unlimited use and to read your private project data."}},
  {"@type":"Question","name":"Is it open source?","acceptedAnswer":{"@type":"Answer","text":"Yes — MIT license. Full source for both the PHP server and the npx shim is at github.com/1fancy/ranki-mcp."}}
]}
</script>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-aurora"></div>

<header class="site-header">
  <div class="container hdr">
    <a href="/" class="logo" aria-label="Ranki MCP home">
      <img src="https://ranki.io/assets/images/ranki-logo-90h.png" alt="Ranki" width="78" height="24">
      <span class="pill">MCP</span>
    </a>
    <nav class="nav">
      <a href="#tools">Tools</a>
      <a href="#install">Install</a>
      <a href="#why">Why</a>
      <a href="#faq">FAQ</a>
      <a href="https://ranki.io/developers" target="_blank" rel="noopener">Docs</a>
      <a href="https://app.ranki.io/developer" class="btn btn-primary">Get API key →</a>
    </nav>
  </div>
</header>

<main>

  <section class="hero">
    <div class="container">
      <div class="eyebrow"><span class="live-dot"></span> Live · MCP 2024-11-05 · 10 tools online</div>
      <h1>SEO + AEO advisor<br>for <span class="accent">Claude, Cursor & ChatGPT</span></h1>
      <p class="lede">A Model Context Protocol server that audits your site, generates the SEO files you forgot, and tells your AI exactly what to fix — without spending a single one of our tokens. Built for the vibe-coders shipping faster than they can spell <span class="mono">sitemap.xml</span>.</p>
      <div class="cta-row">
        <a href="#install" class="btn btn-primary btn-xl">Install in 30 seconds →</a>
        <a href="#tools" class="btn btn-ghost btn-xl">See the 10 tools</a>
      </div>

      <div class="hero-term" aria-hidden="true">
        <div class="hero-term-bar">
          <span class="d r"></span><span class="d y"></span><span class="d g"></span>
          <span class="where">claude · vibe-coded-site.dev</span>
        </div>
        <div class="hero-term-body" id="termBody"></div>
      </div>
    </div>
  </section>

  <section id="tools">
    <div class="container">
      <div class="section-head">
        <h2>Ten tools your AI can hold</h2>
        <p>The MCP server returns checklists, generated files, and fix recipes. <strong>Your</strong> Claude evaluates them against your code — Ranki MCP never spends an AI token of ours, so we run the advisor tools for free.</p>
      </div>

      <div class="tools-block">
        <div class="tools-head"><span>tools/list</span><span class="count">10 tools</span></div>
        <div class="tool-row">
          <div class="icon">SK</div>
          <div><h3>seo_starter_kit(domain)</h3><p>You shipped a site. We return the exact robots.txt, sitemap.xml, llms.txt, and JSON-LD structured data — plus the deploy order. Your AI writes the files into your repo.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">TI</div>
          <div><h3>find_topic_ideas(url)</h3><p>You don't know what to blog about. We sniff your niche, return a structured brief, and tell your AI how to generate 15 topics across informational, commercial, and transactional intent — with prioritization criteria.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">KG</div>
          <div><h3>find_keyword_gap(url, competitors[])</h3><p>You suspect competitors are stealing keywords. We return the gap-analysis methodology — your AI walks the user through it. If no competitors given, your AI asks the user first.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">AE</div>
          <div><h3>audit_aeo(url)</h3><p>The signals ChatGPT, Claude, Perplexity, and Google AI Overviews use to pick citations. Eight checks — FAQPage / Article JSON-LD, definitional intro, author byline, llms.txt, robots.txt AI allowance, answer-style headings, tables — each with a copy-pasteable fix recipe.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">AS</div>
          <div><h3>audit_seo(url)</h3><p>On-page SEO scorecard. Ten checks scored 0-100 — title length, meta description, H1 uniqueness, canonical, viewport, HTTPS, OpenGraph, image alt coverage, internal links, JSON-LD presence.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">SM</div>
          <div><h3>generate_sitemap_xml(urls[])</h3><p>Pass your URL list, get back a deploy-ready sitemap with current lastmod. Submit to Google Search Console immediately.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">LT</div>
          <div><h3>generate_llms_txt(site_name, summary, key_pages)</h3><p>The emerging llms.txt standard for telling LLMs what your site is about and how to cite you. The single highest-signal AEO file most sites are missing.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">RT</div>
          <div><h3>generate_robots_txt(sitemap_url, allow_ai, disallow_paths)</h3><p>Build a robots.txt that explicitly allows or blocks GPTBot, ClaudeBot, PerplexityBot, Google-Extended, ChatGPT-User, anthropic-ai. Default: allow — you want AI citation traffic.</p></div>
          <span class="tag">Free</span>
        </div>
        <div class="tool-row">
          <div class="icon">LP</div>
          <div><h3>list_projects()</h3><p>List the projects in your Ranki.io account. Pulls your own automated-content pipeline into the same Claude conversation where you're vibe-coding.</p></div>
          <span class="tag key">Key</span>
        </div>
        <div class="tool-row">
          <div class="icon">GA</div>
          <div><h3>get_article(article_id)</h3><p>Fetch a single Ranki.io article by nano_id — title, full HTML, focus keywords, TOC, embedded image URLs, SEO score.</p></div>
          <span class="tag key">Key</span>
        </div>
      </div>
    </div>
  </section>

  <section id="install">
    <div class="container">
      <div class="section-head">
        <h2>Install in 30 seconds</h2>
        <p>Pick your client. The advisor tools work without a key (five calls per IP per day). Add your Ranki.io key to remove the limit and unlock the bridge tools.</p>
      </div>

      <div class="install-grid">
        <div class="install-block">
          <div class="install-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            Claude Desktop &middot; Claude Code &middot; ChatGPT Desktop
          </div>
          <div class="install-body">
            <p class="path">~/.claude/claude_desktop_config.json (stdio via npx)</p>
            <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="k">{</span>
  <span class="v">"mcpServers"</span><span class="k">:</span> <span class="k">{</span>
    <span class="v">"ranki"</span><span class="k">:</span> <span class="k">{</span>
      <span class="v">"command"</span><span class="k">:</span> <span class="s">"npx"</span>,
      <span class="v">"args"</span><span class="k">:</span> <span class="k">[</span><span class="s">"-y"</span>, <span class="s">"@ranki/mcp"</span><span class="k">]</span>,
      <span class="v">"env"</span><span class="k">:</span> <span class="k">{</span> <span class="v">"RANKI_API_KEY"</span><span class="k">:</span> <span class="s">"YOUR_KEY"</span> <span class="k">}</span>
    <span class="k">}</span>
  <span class="k">}</span>
<span class="k">}</span></pre>
          </div>
        </div>

        <div class="install-block">
          <div class="install-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            Cursor &middot; Claude.ai web &middot; raw HTTP
          </div>
          <div class="install-body">
            <p class="path">.cursor/mcp.json (HTTP transport, no install)</p>
            <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="k">{</span>
  <span class="v">"mcpServers"</span><span class="k">:</span> <span class="k">{</span>
    <span class="v">"ranki"</span><span class="k">:</span> <span class="k">{</span>
      <span class="v">"url"</span><span class="k">:</span> <span class="s">"https://mcp.ranki.io"</span>,
      <span class="v">"headers"</span><span class="k">:</span> <span class="k">{</span>
        <span class="v">"X-API-Key"</span><span class="k">:</span> <span class="s">"YOUR_KEY"</span>
      <span class="k">}</span>
    <span class="k">}</span>
  <span class="k">}</span>
<span class="k">}</span></pre>
          </div>
        </div>
      </div>

      <p style="text-align:center;margin-top:1.5rem;color:var(--ink-3);font-size:.9rem">
        Six install paths total (Desktop, Code CLI, Claude.ai web, Cursor, ChatGPT Desktop, curl) — full snippets at
        <a href="https://app.ranki.io/developer">app.ranki.io/developer</a> after you grab a key.
      </p>
    </div>
  </section>

  <section id="why">
    <div class="container">
      <div class="section-head">
        <h2>Why "advisor only" matters</h2>
        <p>Every other SEO-and-AI tool calls OpenAI or Anthropic with <em>your</em> data, then charges you their token cost plus a margin. Ranki MCP doesn't.</p>
      </div>

      <div class="why-flow">
        <svg viewBox="0 0 800 220" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ranki MCP returns structured advice — your Claude executes the advice against your code using your own credits">
          <defs>
            <linearGradient id="dataFlow" x1="0" x2="1">
              <stop offset="0%" stop-color="rgba(247,144,108,0)"/>
              <stop offset="50%" stop-color="rgba(247,144,108,0.9)"/>
              <stop offset="100%" stop-color="rgba(247,144,108,0)"/>
            </linearGradient>
            <linearGradient id="userColor" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#94d2ff"/>
              <stop offset="100%" stop-color="#5fb7f6"/>
            </linearGradient>
          </defs>

          <!-- Your Claude box -->
          <g transform="translate(40,60)">
            <rect width="180" height="100" rx="10" fill="rgba(148,210,255,.06)" stroke="rgba(148,210,255,.4)" stroke-width="1.5"/>
            <text x="90" y="38" text-anchor="middle" fill="#94d2ff" font-family="JetBrains Mono,monospace" font-size="13" font-weight="600">Your Claude</text>
            <text x="90" y="58" text-anchor="middle" fill="#94d2ff" font-family="Inter,sans-serif" font-size="11" opacity=".8">Cursor &middot; Desktop &middot; CLI</text>
            <text x="90" y="82" text-anchor="middle" fill="#94d2ff" font-family="JetBrains Mono,monospace" font-size="10" opacity=".6">pays in YOUR credits</text>
          </g>

          <!-- Ranki MCP box -->
          <g transform="translate(580,60)">
            <rect width="180" height="100" rx="10" fill="rgba(247,144,108,.08)" stroke="rgba(247,144,108,.5)" stroke-width="1.5"/>
            <text x="90" y="38" text-anchor="middle" fill="#f7906c" font-family="Ranki Black,Inter" font-size="14" font-weight="900">Ranki MCP</text>
            <text x="90" y="58" text-anchor="middle" fill="#f7906c" font-family="Inter,sans-serif" font-size="11" opacity=".85">mcp.ranki.io</text>
            <text x="90" y="82" text-anchor="middle" fill="#f7906c" font-family="JetBrains Mono,monospace" font-size="10" opacity=".7">advice + files only</text>
          </g>

          <!-- Outgoing arrow + label -->
          <line x1="225" y1="100" x2="575" y2="100" stroke="url(#dataFlow)" stroke-width="2" stroke-dasharray="6 4">
            <animate attributeName="stroke-dashoffset" from="0" to="-20" dur="1.4s" repeatCount="indefinite"/>
          </line>
          <text x="400" y="92" text-anchor="middle" fill="#f7906c" class="flow-label" font-family="JetBrains Mono,monospace" font-size="10" font-weight="600">tools/call</text>

          <!-- Incoming arrow -->
          <line x1="575" y1="120" x2="225" y2="120" stroke="url(#dataFlow)" stroke-width="2" stroke-dasharray="6 4">
            <animate attributeName="stroke-dashoffset" from="0" to="20" dur="1.4s" repeatCount="indefinite"/>
          </line>
          <text x="400" y="138" text-anchor="middle" fill="#9be5a6" class="flow-label" font-family="JetBrains Mono,monospace" font-size="10" font-weight="600">structured advice</text>

          <!-- Your code box -->
          <g transform="translate(310,180)">
            <rect width="180" height="32" rx="6" fill="rgba(255,255,255,.04)" stroke="rgba(255,255,255,.2)" stroke-width="1"/>
            <text x="90" y="21" text-anchor="middle" fill="#fff" font-family="JetBrains Mono,monospace" font-size="11" opacity=".75">your codebase</text>
          </g>
          <line x1="130" y1="160" x2="310" y2="195" stroke="rgba(148,210,255,.3)" stroke-width="1.4" stroke-dasharray="3 3"/>
          <text x="190" y="178" fill="#94d2ff" font-family="JetBrains Mono,monospace" font-size="9" opacity=".55">edits</text>
        </svg>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.5rem;max-width:880px;margin:2.5rem auto 0">
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">No vendor lock-in.</strong>Stop using us tomorrow — your code is yours, deterministic advice is yours.</p>
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">No opaque AI bills.</strong>You pay your Claude or Cursor subscription. We never run on your tokens.</p>
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">No hallucination from us.</strong>Our tools return deterministic data; your AI does the inference, against your real code.</p>
      </div>
    </div>
  </section>

  <section id="faq">
    <div class="container">
      <div class="section-head"><h2>Real questions, real answers</h2></div>
      <div class="faq">
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Does Ranki MCP use my Claude credits or yours?</div><div class="faq-a"><strong style="color:var(--ink)">Yours.</strong> The MCP server returns structured advice (checklists, fix recipes, generated files). Your Claude / Cursor evaluates them against your code using your own credits. We never make LLM calls on your behalf — that's why the advisor tools can run free.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">What does AEO mean? Why should I care?</div><div class="faq-a"><strong style="color:var(--ink)">Answer Engine Optimization</strong> — the structural signals (FAQPage schema, definitional intros, author bylines, <span class="mono">llms.txt</span>, comparison tables) that ChatGPT, Claude, Perplexity, and Google AI Overviews use to pick which sites to cite in their answers. AEO is to AI search what SEO is to Google's blue links. In 2026, AEO is the fastest-growing traffic channel — and most sites have zero coverage.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Do I need a Ranki.io account?</div><div class="faq-a">No, for the eight advisor tools — they work free, rate-limited to five calls per IP per UTC day. Yes, for <span class="mono">list_projects</span> and <span class="mono">get_article</span>, which need a free key to read your private Ranki.io data.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Stdio vs HTTP — which transport do I use?</div><div class="faq-a">Claude Desktop, Claude Code, and ChatGPT Desktop speak stdio — use the <span class="mono">npx -y @ranki/mcp</span> snippet. Cursor and Claude.ai web speak HTTP — point them at <span class="mono">https://mcp.ranki.io</span> directly.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Is it open source?</div><div class="faq-a">Yes — MIT license. PHP server and npx shim at <a href="https://github.com/1fancy/ranki-mcp">github.com/1fancy/ranki-mcp</a>. The companion Claude Skill is at <a href="https://github.com/1fancy/ranki-seo-skills">github.com/1fancy/ranki-seo-skills</a>.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">How is this different from Surfer / Frase / SEMrush?</div><div class="faq-a">Those are SaaS dashboards — you log in, paste a URL, get a report, switch back to your IDE. Ranki MCP lives <em>inside</em> your IDE. Your AI calls the tools inline while writing code, applies the fixes to your files, re-checks the score. Different shape, different price (free), different audience (vibe-coders, not SEO professionals).</div></div>
      </div>
    </div>
  </section>

</main>

<footer>
  <div class="container">
    <div class="foot">
      <div>
        <a href="/" class="logo" style="margin-bottom:1rem">
          <img src="https://ranki.io/assets/images/ranki-logo-90h.png" alt="Ranki" width="78" height="24">
          <span class="pill">MCP</span>
        </a>
        <p class="tag">Part of <a href="https://ranki.io" style="color:var(--ink-2);text-decoration:underline">Ranki.io</a> — the AI SEO and AEO automation platform for founders, agencies, and creators.</p>
      </div>
      <div>
        <h4>Build with us</h4>
        <a href="https://ranki.io/developers">Developer docs</a>
        <a href="https://app.ranki.io/developer">Get free API key</a>
        <a href="https://github.com/1fancy/ranki-mcp">GitHub · MCP server</a>
        <a href="https://github.com/1fancy/ranki-seo-skills">GitHub · Claude Skill</a>
      </div>
      <div>
        <h4>Resources</h4>
        <a href="https://ranki.io/learn/aeo-guide">AEO guide</a>
        <a href="https://ranki.io/learn/seo-guide-2026">SEO guide 2026</a>
        <a href="https://ranki.io/tools/aeo-audit">Free AEO audit</a>
        <a href="https://ranki.io/privacy">Privacy</a>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© <?= date('Y') ?> Ranki.io. MIT licensed. Built in the open.</span>
      <span class="mono">mcp.ranki.io · v0.1</span>
    </div>
  </div>
</footer>

<script>
// ============== Animated hero terminal ==============
// Types out a realistic conversation in the terminal block. Pure JS, no
// dependency. Pauses briefly between lines so the user can read.
const termLines = [
  ['<span class="p">›</span> <span class="you">audit my site for AEO and fix it</span>', 350],
  ['', 700],
  ['<span class="ai">Calling <span class="tag">ranki.audit_aeo</span><span class="dim">(url="vibe-coded-site.dev")</span>…</span>', 400],
  ['<span class="dim">  → Score: 38/100. Failing: no FAQPage schema, no llms.txt,</span>', 250],
  ['<span class="dim">    robots.txt blocks ClaudeBot, no answer-style H2s.</span>', 600],
  ['', 200],
  ['<span class="ai">Editing public/robots.txt…</span>', 280],
  ['<span class="ai">Editing app/layout.tsx — adding FAQPage JSON-LD…</span>', 280],
  ['<span class="ai">Writing public/llms.txt via <span class="tag">ranki.generate_llms_txt</span>…</span>', 280],
  ['', 200],
  ['<span class="ai">Re-running audit…</span>', 350],
  ['<span class="ok">  ✓ Score: 88/100. Re-deploy and AI search will pick it up.</span>', 1200],
];
const term = document.getElementById('termBody');
let lineIdx = 0;
function nextLine(){
  if (lineIdx >= termLines.length) {
    // restart loop
    term.innerHTML = '';
    lineIdx = 0;
    setTimeout(nextLine, 1800);
    return;
  }
  const [html, delay] = termLines[lineIdx++];
  if (html === '') {
    term.innerHTML += '<br>';
  } else {
    term.innerHTML += html + '<br>';
  }
  // Auto-scroll inside the box if needed (it shouldn't, max-height set)
  term.scrollTop = term.scrollHeight;
  setTimeout(nextLine, delay);
}
// Show prompt cursor immediately, start typing after 600ms
term.innerHTML = '<span class="p">›</span> <span class="cursor"></span>';
setTimeout(() => { term.innerHTML = ''; nextLine(); }, 800);

// ============== Copy-to-clipboard ==============
function copyCode(btn){
  const code = btn.parentElement.innerText.replace(/^Copy\n?/, '').trim();
  navigator.clipboard.writeText(code).then(() => {
    const orig = btn.textContent;
    btn.textContent = 'Copied!';
    btn.style.color = 'var(--orange)';
    btn.style.borderColor = 'var(--orange)';
    setTimeout(() => { btn.textContent = orig; btn.style.color = ''; btn.style.borderColor = ''; }, 1400);
  });
}
</script>

</body>
</html>
