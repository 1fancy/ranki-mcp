<?php
/**
 * mcp.ranki.io — dual-purpose entry point.
 *
 * GET  → marketing landing (mobile-first, SEO/AEO-hardened)
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
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

<!-- ============== PRIMARY SEO ============== -->
<title>Free SEO and AEO Audit Tool for Cursor, Claude Code & Windsurf — Ranki MCP</title>
<meta name="description" content="Free MCP server that turns Cursor, Claude Code, Windsurf and ChatGPT Desktop into a senior SEO and AEO consultant. Audits any URL, generates sitemap.xml, llms.txt, robots.txt and FAQPage schema, proposes title and meta candidates, finds keyword gaps.">
<meta name="keywords" content="MCP server, MCP for SEO, MCP for AEO, SEO MCP, AEO MCP, MCP for Cursor, MCP for Claude Code, MCP for Windsurf, MCP for ChatGPT Desktop, MCP for Lovable, MCP for v0, MCP server for vibe coding, vibe coder SEO tool, SEO tool for AI coding agents, AI coding SEO, Cursor SEO plugin, Claude Code SEO, Windsurf SEO, llms.txt generator, sitemap.xml generator, robots.txt generator, FAQPage schema generator, JSON-LD generator, Answer Engine Optimization MCP, Google AI Overviews SEO, get cited by ChatGPT, get cited by Perplexity, get cited by Claude, AI search optimization, vibe coded site SEO audit, free SEO audit tool, free AEO audit">
<meta name="author" content="Ranki.io · 1Fancy Inc.">
<meta name="creator" content="Younes Lamnabhi">
<meta name="publisher" content="Ranki.io">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<meta name="googlebot" content="index,follow">
<meta name="bingbot" content="index,follow">
<meta name="rating" content="General">
<meta name="referrer" content="strict-origin-when-cross-origin">
<meta name="generator" content="Ranki MCP">
<meta name="application-name" content="Ranki MCP">

<link rel="canonical" href="https://mcp.ranki.io/">
<link rel="alternate" hreflang="en" href="https://mcp.ranki.io/">
<link rel="alternate" hreflang="x-default" href="https://mcp.ranki.io/">

<!-- ============== OPEN GRAPH ============== -->
<meta property="og:type" content="website">
<meta property="og:title" content="SEO + AEO MCP Server, Skill & API for Cursor, Claude Code, Windsurf — Ranki MCP">
<meta property="og:description" content="Free SEO + AEO MCP server, Claude Skill and REST API. Audit sites + generate sitemap.xml, llms.txt, robots.txt straight from your IDE. Open source, MIT.">
<meta property="og:url" content="https://mcp.ranki.io/">
<meta property="og:site_name" content="Ranki MCP">
<meta property="og:image" content="https://ranki.io/assets/images/favicon-512.png">
<meta property="og:image:secure_url" content="https://ranki.io/assets/images/favicon-512.png">
<meta property="og:image:width" content="512">
<meta property="og:image:height" content="512">
<meta property="og:image:type" content="image/png">
<meta property="og:image:alt" content="Ranki MCP — SEO + AEO advisor for AI coding agents">
<meta property="og:locale" content="en_US">
<meta property="article:publisher" content="https://ranki.io">

<!-- ============== TWITTER / X ============== -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@rankiio">
<meta name="twitter:creator" content="@younes_lam">
<meta name="twitter:title" content="SEO + AEO MCP Server, Skill & API for Cursor, Claude Code, Windsurf">
<meta name="twitter:description" content="Free SEO + AEO MCP server, Claude Skill and REST API. Audit sites + generate sitemap.xml, llms.txt, robots.txt from your IDE.">
<meta name="twitter:image" content="https://ranki.io/assets/images/favicon-512.png">
<meta name="twitter:image:alt" content="Ranki MCP logo">

<!-- ============== FAVICONS (served from ranki.io for cross-property sharing) ============== -->
<link rel="icon" href="https://ranki.io/assets/images/favicon.ico" sizes="any">
<link rel="icon" type="image/svg+xml" href="https://ranki.io/assets/svg/favicon.svg">
<link rel="icon" type="image/png" sizes="32x32" href="https://ranki.io/assets/images/favicon-32.png">
<link rel="icon" type="image/png" sizes="16x16" href="https://ranki.io/assets/images/favicon-16.png">
<link rel="apple-touch-icon" sizes="180x180" href="https://ranki.io/assets/images/favicon-180.png">
<link rel="mask-icon" href="https://ranki.io/assets/svg/favicon.svg" color="#f7906c">
<link rel="manifest" href="/site.webmanifest">

<!-- ============== THEMING + PWA ============== -->
<meta name="theme-color" content="#06070a" media="(prefers-color-scheme: dark)">
<meta name="theme-color" content="#f7906c" media="(prefers-color-scheme: light)">
<meta name="color-scheme" content="dark">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Ranki MCP">
<meta name="msapplication-TileColor" content="#06070a">
<meta name="format-detection" content="telephone=no">

<!-- ============== RESOURCE HINTS (perf) ============== -->
<link rel="preconnect" href="https://ranki.io" crossorigin>
<link rel="dns-prefetch" href="https://app.ranki.io">
<link rel="dns-prefetch" href="https://github.com">
<!-- Preload the brand title font so headings render in Ranki Black on
     first paint. Served from /fonts/ on this origin (no CORS), so the
     browser actually uses it (cross-origin webfonts need ACAO headers
     and ranki.io doesn't ship them — self-hosting is the simpler fix). -->
<link rel="preload" href="/fonts/ranki_black.woff" as="font" type="font/woff" crossorigin>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Ranki Black — brand title font.
   Self-hosted under /fonts on this origin so browsers actually apply it
   (cross-origin webfont without an ACAO header gets silently dropped).
   font-display:swap → Inter shows for the first ~100ms then Ranki Black
   swaps in cleanly. The <link rel="preload"> above usually beats paint. */
@font-face {
  font-family: 'Ranki Black';
  src: url('/fonts/ranki_black.woff') format('woff'),
       url('/fonts/ranki_black.ttf') format('truetype');
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
  --purple:#b794f6;
  --container:1180px;
}

*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
html,body{background:var(--bg);color:var(--ink);font-family:'Inter',system-ui,sans-serif;line-height:1.65;-webkit-font-smoothing:antialiased;font-feature-settings:'ss01','cv11';overflow-x:hidden}
/* Signature: thin gradient bar across the very top of every page — design flourish that ties the dark palette to the orange brand. */
body::before{content:'';position:fixed;inset:0 0 auto 0;height:2px;background:linear-gradient(90deg,transparent 0%,var(--orange) 30%,var(--orange-2) 70%,transparent 100%);z-index:60;pointer-events:none}
::selection{background:var(--orange);color:#000}
a{color:var(--orange);text-decoration:none;transition:color .15s}
a:hover{color:var(--orange-2)}
img{max-width:100%;height:auto;display:block}

.container{max-width:var(--container);margin:0 auto;padding:0 1.25rem}
@media (min-width:768px){.container{padding:0 1.5rem}}
.mono{font-family:'JetBrains Mono',ui-monospace,monospace;font-size:.92em}
.muted{color:var(--ink-3)}

/* ============== ANIMATED BACKGROUND ============== */
.bg-grid{position:fixed;inset:0;pointer-events:none;z-index:0;background-image:radial-gradient(circle at 1px 1px,rgba(255,255,255,.04) 1px,transparent 0);background-size:32px 32px;mask-image:linear-gradient(to bottom,#000 0%,#000 50%,transparent 80%);-webkit-mask-image:linear-gradient(to bottom,#000 0%,#000 50%,transparent 80%)}
.bg-aurora{position:fixed;top:-200px;left:50%;transform:translateX(-50%);width:1400px;height:900px;pointer-events:none;z-index:0;
  background:
    radial-gradient(ellipse 600px 300px at 30% 30%,var(--orange-glow),transparent 70%),
    radial-gradient(ellipse 500px 400px at 70% 50%,rgba(247,122,61,.12),transparent 60%);
  filter:blur(40px);
  animation:aurora-drift 24s ease-in-out infinite;
  will-change:transform
}
@keyframes aurora-drift{0%,100%{transform:translateX(-50%) translateY(0) scale(1)}50%{transform:translateX(-50%) translateY(30px) scale(1.05)}}
/* Disable bg animations for users with reduced-motion */
@media (prefers-reduced-motion:reduce){.bg-aurora{animation:none}}

main, header, footer{position:relative;z-index:1}

/* ============== HEADER ============== */
.site-header{position:sticky;top:0;background:rgba(6,7,10,.82);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-bottom:1px solid var(--line);z-index:50}
.hdr{display:flex;align-items:center;justify-content:space-between;padding:.85rem 0;gap:1rem}

.logo{display:inline-flex;align-items:center;gap:.6rem;text-decoration:none;color:var(--ink);flex-shrink:0}
.logo img{height:22px;width:auto;filter:brightness(0) invert(1);opacity:.95}
.logo .pill{font-family:'JetBrains Mono',monospace;font-weight:600;font-size:.7rem;color:var(--orange);letter-spacing:.08em;padding:.22rem .55rem;background:var(--orange-soft);border-radius:99px;border:1px solid rgba(247,144,108,.3);text-transform:uppercase;line-height:1}
@media (min-width:768px){.logo img{height:24px}}

/* Desktop nav */
.nav{display:none;gap:1.4rem;align-items:center}
@media (min-width:880px){.nav{display:flex}}
.nav a{color:var(--ink-2);font-size:.92rem;font-weight:500}
.nav a:hover{color:var(--ink)}

/* Mobile menu button (hamburger) */
.mobile-toggle{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:transparent;border:1px solid var(--line-2);color:var(--ink);cursor:pointer;flex-shrink:0}
@media (min-width:880px){.mobile-toggle{display:none}}
.mobile-toggle:hover{border-color:var(--orange);color:var(--orange)}
.mobile-toggle svg{width:18px;height:18px}

/* Mobile menu drawer */
.mobile-menu{position:fixed;inset:0;background:rgba(6,7,10,.96);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);z-index:100;display:none;flex-direction:column;padding:1.25rem;overflow-y:auto}
.mobile-menu.is-open{display:flex}
.mobile-menu-head{display:flex;align-items:center;justify-content:space-between;padding-bottom:1rem;border-bottom:1px solid var(--line);margin-bottom:1.5rem}
.mobile-menu nav{display:flex;flex-direction:column;gap:.25rem;flex:1}
.mobile-menu nav a{padding:1rem 1.1rem;color:var(--ink);font-size:1.1rem;font-weight:600;border-radius:10px;display:flex;align-items:center;justify-content:space-between;border:1px solid transparent}
.mobile-menu nav a:hover{background:var(--bg-3);border-color:var(--line-2)}
.mobile-menu nav a::after{content:'→';color:var(--ink-4);font-weight:400}
.mobile-menu .footer-cta{padding-top:1rem;border-top:1px solid var(--line);margin-top:.5rem}
.mobile-menu .footer-cta .btn{width:100%;justify-content:center;padding:1rem;font-size:1rem}

.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.1rem;border-radius:8px;font-weight:600;font-size:.9rem;border:1px solid transparent;transition:all .15s;font-family:inherit;cursor:pointer;line-height:1;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,var(--orange) 0%,var(--orange-2) 100%);color:#fff !important;box-shadow:0 4px 18px -4px rgba(247,144,108,.5)}
.btn-primary:hover{background:linear-gradient(135deg,var(--orange-2) 0%,#e8651f 100%);color:#fff !important;box-shadow:0 6px 24px -4px rgba(247,144,108,.7);transform:translateY(-1px)}
.btn-ghost{border-color:var(--line-2);color:#fff !important;background:transparent}
.btn-ghost:hover{border-color:var(--orange);color:#fff !important;background:var(--orange-soft)}

/* ============== HERO ============== */
.hero{padding:3rem 0 4rem;text-align:center;position:relative}
@media (min-width:768px){.hero{padding:5rem 0 5rem}}

.eyebrow{display:inline-flex;align-items:center;gap:.5rem;padding:.4rem 1rem;background:var(--bg-3);border:1px solid var(--line-2);border-radius:99px;color:var(--ink-2);font-size:.78rem;font-weight:500;margin-bottom:1.4rem}
.eyebrow .live-dot{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 8px var(--green);animation:pulse 2.4s ease-in-out infinite;flex-shrink:0}
@keyframes pulse{0%,100%{opacity:.5;transform:scale(.85)}50%{opacity:1;transform:scale(1)}}
@media (prefers-reduced-motion:reduce){.live-dot{animation:none}}

h1{font-family:'Ranki Black','Inter',sans-serif;font-size:clamp(2rem,7.5vw,4.4rem);font-weight:900;line-height:1.05;letter-spacing:-.025em;margin-bottom:1.2rem}
h1 .accent{background:linear-gradient(180deg,var(--orange) 30%,#fff 130%);-webkit-background-clip:text;background-clip:text;color:transparent}

.lede{font-size:clamp(1rem,2.5vw,1.22rem);color:var(--ink-2);max-width:680px;margin:0 auto 2rem;line-height:1.55;padding:0 .5rem}
.cta-row{display:flex;gap:.7rem;justify-content:center;flex-wrap:wrap;margin-bottom:2.5rem;padding:0 .5rem}
.btn-xl{padding:.85rem 1.4rem;font-size:.95rem;border-radius:10px}
@media (min-width:768px){.btn-xl{padding:.95rem 1.6rem;font-size:1rem}}

/* Compatibility row — tiny trust signal under CTA */
.compat-row{display:flex;justify-content:center;align-items:center;gap:1.2rem;flex-wrap:wrap;color:var(--ink-3);font-size:.78rem;margin-bottom:3rem;padding:0 .5rem}
.compat-row .compat-label{text-transform:uppercase;letter-spacing:.08em;font-weight:600;color:var(--ink-4);font-size:.72rem}
.compat-row span{white-space:nowrap}
.compat-row strong{color:var(--ink-2);font-weight:500}

/* Hero terminal */
.hero-term{max-width:780px;margin:0 auto;background:linear-gradient(180deg,rgba(13,15,20,.95),rgba(13,15,20,.7));border:1px solid var(--line-2);border-radius:14px;overflow:hidden;box-shadow:0 30px 80px -30px rgba(247,144,108,.25),0 0 0 1px var(--line);backdrop-filter:blur(20px)}
.hero-term-bar{display:flex;align-items:center;gap:.45rem;padding:.55rem .85rem;background:rgba(0,0,0,.25);border-bottom:1px solid var(--line)}
.hero-term-bar .d{width:10px;height:10px;border-radius:50%;background:var(--line-3)}
.hero-term-bar .d.r{background:#ff5f56}.hero-term-bar .d.y{background:#ffbd2e}.hero-term-bar .d.g{background:#27c93f}
.hero-term-bar .where{margin-left:auto;font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--ink-3)}
.hero-term-body{padding:1.1rem 1.2rem;font-family:'JetBrains Mono',monospace;font-size:.78rem;line-height:1.85;text-align:left;color:var(--ink-2);min-height:200px}
@media (min-width:768px){.hero-term-body{padding:1.4rem 1.5rem;font-size:.88rem}}
.hero-term-body .p{color:var(--orange)}
.hero-term-body .you{color:var(--ink)}
.hero-term-body .ai{color:var(--ink-2)}
.hero-term-body .tag{color:#94d2ff}
.hero-term-body .ok{color:var(--green)}
.hero-term-body .dim{color:var(--ink-3)}
.hero-term-body .cursor{display:inline-block;width:8px;height:1em;background:var(--orange);vertical-align:-2px;margin-left:2px;animation:blink 1s steps(2,end) infinite}
@keyframes blink{50%{opacity:0}}
@media (prefers-reduced-motion:reduce){.cursor{animation:none}}

/* ============== SECTIONS ============== */
section{padding:3.5rem 0;position:relative}
@media (min-width:768px){section{padding:5rem 0}}
.section-head{text-align:center;margin-bottom:2.2rem;max-width:680px;margin-left:auto;margin-right:auto;padding:0 .5rem}
@media (min-width:768px){.section-head{margin-bottom:3rem}}
.section-head h2{font-family:'Ranki Black','Inter',sans-serif;font-size:clamp(1.7rem,5vw,2.6rem);font-weight:900;letter-spacing:-.02em;line-height:1.15;margin-bottom:.8rem}
.section-head p{color:var(--ink-2);font-size:1rem;line-height:1.6}

/* ============== "FOR WHO" — narrative steps, not cards ============== */
.for-narrative{max-width:780px;margin:0 auto;display:flex;flex-direction:column;gap:0}
.for-step{display:grid;grid-template-columns:auto 1fr;gap:1.5rem;padding:2rem 0;border-bottom:1px dashed var(--line-2);align-items:start;position:relative}
.for-step:last-child{border-bottom:0}
.for-step .n{font-family:'Ranki Black','Inter',sans-serif;font-size:clamp(2.8rem,7vw,4rem);font-weight:900;line-height:.9;color:transparent;-webkit-text-stroke:1.5px var(--orange);background:linear-gradient(180deg,var(--orange) 0%,transparent 70%);-webkit-background-clip:text;background-clip:text;letter-spacing:-.03em;flex-shrink:0;min-width:3rem}
.for-step h3{font-family:'Ranki Black','Inter',sans-serif;font-size:clamp(1.25rem,3vw,1.55rem);font-weight:900;letter-spacing:-.015em;color:var(--ink);margin-bottom:.6rem;line-height:1.2}
.for-step p{color:var(--ink-2);font-size:.97rem;line-height:1.65}
.for-step p strong{color:var(--ink)}
.for-step .ref{display:inline-block;font-family:'JetBrains Mono',monospace;font-size:.78rem;color:var(--orange);padding:.2rem .55rem;background:var(--orange-soft);border:1px solid rgba(247,144,108,.25);border-radius:5px;margin-top:.85rem;letter-spacing:-.005em}
@media (max-width:560px){.for-step{grid-template-columns:1fr;gap:.6rem;padding:1.6rem 0}.for-step .n{font-size:2.6rem}}

/* ============== TOOLS — grouped category view ============== */
.tools-wrap{max-width:980px;margin:0 auto}
.tools-cats{display:flex;align-items:center;justify-content:center;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;padding:0 1rem}
.tools-cat-pill{font-family:'JetBrains Mono',monospace;font-size:.72rem;font-weight:600;padding:.4rem .85rem;border-radius:99px;background:var(--bg-3);border:1px solid var(--line-2);color:var(--ink-3);letter-spacing:.02em;text-transform:uppercase;display:inline-flex;align-items:center;gap:.45rem}
.tools-cat-pill .dot{width:6px;height:6px;border-radius:50%;background:var(--green);flex-shrink:0}
.tools-cat-pill .dot.key{background:var(--orange)}

.tool-cat{margin-bottom:2.5rem}
.tool-cat-head{display:flex;align-items:baseline;justify-content:space-between;gap:1rem;margin-bottom:1rem;padding-bottom:.7rem;border-bottom:1px solid var(--line)}
.tool-cat-head h3{font-family:'Ranki Black','Inter',sans-serif;font-size:1.15rem;font-weight:900;letter-spacing:-.01em;color:var(--ink)}
.tool-cat-head .badge{font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--ink-3);text-transform:uppercase;letter-spacing:.06em}

.tools-grid{display:grid;grid-template-columns:1fr;gap:.8rem}
@media (min-width:680px){.tools-grid{grid-template-columns:1fr 1fr}}
.tool-cell{padding:1rem 1.15rem;border-radius:10px;background:rgba(13,15,20,.5);border:1px solid var(--line);transition:all .15s;position:relative}
.tool-cell:hover{border-color:var(--orange);background:rgba(247,144,108,.04);transform:translateY(-1px)}
.tool-cell h4{font-family:'JetBrains Mono',monospace;font-weight:600;font-size:.9rem;color:var(--orange);margin-bottom:.45rem;letter-spacing:-.01em;word-break:break-word}
.tool-cell p{color:var(--ink-2);font-size:.86rem;line-height:1.55}
.tool-cell .key-flag{position:absolute;top:.6rem;right:.7rem;font-family:'JetBrains Mono',monospace;font-size:.62rem;padding:.12rem .4rem;border-radius:3px;background:rgba(247,144,108,.12);color:var(--orange);border:1px solid rgba(247,144,108,.3);letter-spacing:.04em;text-transform:uppercase;font-weight:600}

/* ============== INSTALL ============== */
.install-grid{display:grid;grid-template-columns:1fr;gap:1rem;max-width:980px;margin:0 auto}
@media (min-width:780px){.install-grid{grid-template-columns:1fr 1fr}}
.install-block{border:1px solid var(--line);border-radius:12px;overflow:hidden;background:rgba(13,15,20,.6);backdrop-filter:blur(8px)}
.install-head{padding:.8rem 1.1rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:.55rem;font-size:.82rem;font-weight:600;color:var(--ink);background:rgba(0,0,0,.2);line-height:1.35}
.install-head svg{flex-shrink:0}
.install-head span{min-width:0;word-break:normal;overflow-wrap:break-word}
@media (min-width:560px){.install-head{padding:.85rem 1.2rem;font-size:.92rem}}
.install-body{padding:1rem 1.1rem}
@media (min-width:560px){.install-body{padding:1.2rem}}
.install-body p.path{font-family:'JetBrains Mono',monospace;font-size:.72rem;color:var(--ink-3);margin-bottom:.7rem;word-break:break-all}

pre.code{background:#020306;border:1px solid var(--line);border-left:3px solid var(--orange);border-radius:8px;padding:.85rem 1rem;font-family:'JetBrains Mono',monospace;font-size:.74rem;line-height:1.7;color:#d4d4d4;overflow-x:auto;text-align:left;position:relative;margin:0;-webkit-overflow-scrolling:touch}
@media (min-width:560px){pre.code{padding:1rem 1.2rem;font-size:.82rem}}
pre.code .k{color:#e8e8e8}
pre.code .s{color:var(--orange)}
pre.code .v{color:#94d2ff}
pre.code .c{color:var(--ink-4)}
.copy-btn{position:absolute;top:.5rem;right:.55rem;background:var(--bg-3);border:1px solid var(--line-2);color:var(--ink-3);padding:.28rem .6rem;border-radius:5px;font-size:.7rem;font-family:'JetBrains Mono',monospace;font-weight:600;cursor:pointer;letter-spacing:.04em;text-transform:uppercase;transition:all .15s}
.copy-btn:hover{color:var(--orange);border-color:var(--orange)}

/* ============== SKILL section (new) ============== */
.skill-block{max-width:920px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:2rem;padding:2rem;border:1px solid var(--line);border-radius:16px;background:linear-gradient(135deg,rgba(183,148,246,.04) 0%,rgba(13,15,20,.6) 50%,rgba(247,144,108,.04) 100%)}
@media (min-width:780px){.skill-block{grid-template-columns:1.1fr 1fr;padding:2.5rem;gap:2.5rem}}
.skill-block h3{font-family:'Ranki Black','Inter',sans-serif;font-size:1.5rem;font-weight:900;letter-spacing:-.015em;line-height:1.2;margin-bottom:.8rem}
.skill-block p{color:var(--ink-2);font-size:.95rem;line-height:1.65;margin-bottom:1.1rem}
.skill-block ul{list-style:none;padding:0;margin:1rem 0 1.5rem;display:flex;flex-direction:column;gap:.5rem}
.skill-block ul li{color:var(--ink-2);font-size:.9rem;padding-left:1.4rem;position:relative;line-height:1.55}
.skill-block ul li::before{content:'→';color:var(--orange);position:absolute;left:0;top:0;font-weight:600}
/* Skill install tabs — column needs to fill the parent grid cell so the
   right side doesn't collapse to the height of one short snippet. */
.skill-block > div:nth-child(2){display:flex;flex-direction:column}
.skill-tabs{display:flex;flex-direction:column;flex:1;min-height:0}
.skill-tabs-list{display:flex;flex-wrap:wrap;gap:.35rem;margin-bottom:.9rem;border-bottom:1px solid var(--line);flex-shrink:0}
.skill-tabs-list button{background:transparent;border:0;color:var(--ink-3);font-family:'JetBrains Mono',monospace;font-size:.72rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;padding:.55rem .85rem;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;transition:color .15s,border-color .15s;white-space:nowrap}
.skill-tabs-list button:hover{color:var(--ink-2)}
.skill-tabs-list button[aria-selected="true"]{color:var(--orange);border-bottom-color:var(--orange)}
.skill-tab-panel{display:none;flex:1;flex-direction:column}
.skill-tab-panel.active{display:flex}
.skill-tab-panel pre{margin:0;flex:1;display:flex;flex-direction:column;min-height:0}
.skill-tab-panel pre code,.skill-tab-panel pre > *:not(.copy-btn){display:block}
@media (min-width:780px){.skill-tab-panel pre{min-height:320px}}

/* ============== "WHY" section ============== */
.why-flow{max-width:880px;margin:0 auto;padding:1.5rem;background:linear-gradient(180deg,rgba(13,15,20,.6),transparent);border:1px solid var(--line);border-radius:16px}
@media (min-width:768px){.why-flow{padding:2.5rem}}
.why-flow svg{width:100%;height:auto;display:block}
.flow-label{font-family:'JetBrains Mono',monospace;font-size:.78rem;letter-spacing:.04em;text-transform:uppercase}

/* ============== FAQ ============== */
.faq{max-width:760px;margin:0 auto}
.faq-item{border-bottom:1px solid var(--line);padding:1.2rem 0}
.faq-q{font-weight:600;color:var(--ink);font-size:.98rem;display:flex;justify-content:space-between;align-items:center;cursor:pointer;gap:1rem;letter-spacing:-.005em;line-height:1.5}
@media (min-width:560px){.faq-q{font-size:1.02rem}}
.faq-q::after{content:'+';color:var(--orange);font-size:1.5rem;font-weight:300;line-height:1;flex-shrink:0}
.faq-a{color:var(--ink-2);margin-top:1rem;font-size:.92rem;line-height:1.7;display:none}
@media (min-width:560px){.faq-a{font-size:.95rem}}
.faq-item.open .faq-q::after{content:'−'}
.faq-item.open .faq-a{display:block}
.faq-a code, .faq-a .mono{background:var(--bg-3);padding:.08rem .3rem;border-radius:3px;font-size:.85em;color:var(--orange)}

/* ============== FOOTER ============== */
footer{padding:3rem 0 2rem;border-top:1px solid var(--line);margin-top:3rem}
@media (min-width:768px){footer{padding:4rem 0 2.5rem;margin-top:4rem}}
.foot{display:grid;grid-template-columns:1fr;gap:2rem}
@media (min-width:560px){.foot{grid-template-columns:1fr 1fr}}
@media (min-width:780px){.foot{grid-template-columns:1.4fr 1fr 1fr 1fr}}
.foot h4{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink-3);margin-bottom:.9rem}
.foot a{display:block;color:var(--ink-2);font-size:.86rem;margin-bottom:.5rem}
.foot a:hover{color:var(--ink)}
.foot p.tag{color:var(--ink-3);font-size:.82rem;line-height:1.6;max-width:300px;margin-top:.8rem}
.foot-bottom{margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;color:var(--ink-4);font-size:.78rem;flex-wrap:wrap;gap:1rem}
</style>

<script type="application/ld+json">
{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Ranki MCP","alternateName":["Ranki MCP Server","MCP for Cursor","MCP for Claude Code","MCP for Windsurf"],"url":"https://mcp.ranki.io/","applicationCategory":"DeveloperApplication","operatingSystem":"Cross-platform — Web, macOS, Linux, Windows","description":"Free Model Context Protocol server that audits SEO + AEO and generates sitemap.xml, llms.txt, robots.txt directly from Claude Code, Claude Desktop, Cursor, Windsurf, ChatGPT Desktop, and any other MCP-capable client.","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"},"creator":{"@type":"Organization","name":"Ranki.io","url":"https://ranki.io"},"sameAs":["https://github.com/1fancy/seo-aeo-audit-mcp-ranki","https://github.com/1fancy/ranki-seo-skills","https://ranki.io"]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"HowTo","name":"How to install Ranki MCP in Claude Code, Cursor, or Windsurf","description":"Add 22 SEO + AEO + speed + image-optimization tools to any MCP-capable AI coding agent in 30 seconds.","totalTime":"PT30S","step":[{"@type":"HowToStep","position":1,"name":"Run the installer","text":"In a terminal, run: npx @ranki.io/cli install. The CLI auto-detects Claude Code, Claude Desktop, Cursor, Windsurf or ChatGPT Desktop and writes the right MCP config for that editor.","url":"https://www.npmjs.com/package/@ranki.io/cli"},{"@type":"HowToStep","position":2,"name":"(Optional) Add a Ranki.io API key","text":"15 of 22 tools work with no key (rate-limited 5 calls per IP per day). For the 7 paid bridge tools that read your real Google Search Console keywords, rank tracking, AI citations and article library, paste your Ranki.io key when the installer asks (or set RANKI_API_KEY in your shell).","url":"https://app.ranki.io/developer"},{"@type":"HowToStep","position":3,"name":"Restart the client and try a tool","text":"Ask the agent: 'audit my site speed and fix the slow images'. The agent calls audit_speed, then optimize_images, then converts the actual image files in your repo to AVIF and WebP, rewrites every <img> tag as a responsive <picture> with srcset and alt, commits, and re-runs audit_speed to confirm the score moved."}]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[
  {"@type":"Question","name":"What is an MCP server, and why do I need one for SEO?","acceptedAnswer":{"@type":"Answer","text":"MCP (Model Context Protocol) is the standard Anthropic introduced in late 2024 for connecting AI agents like Claude Code, Cursor, and Windsurf to external tools. An MCP server adds capabilities your agent doesn't have on its own. Ranki MCP adds 22 tools: SEO + AEO auditing, Core Web Vitals measurement via Google PageSpeed Insights, image optimization (the agent converts the actual files), sitemap and llms.txt generation, keyword-gap analysis, topic discovery, plus paid bridge tools that read your Google Search Console keywords, rank tracking and AI citations from Ranki.io."}},
  {"@type":"Question","name":"Is Ranki MCP compatible with Cursor, Windsurf, Claude Code, and ChatGPT Desktop?","acceptedAnswer":{"@type":"Answer","text":"Yes. Ranki MCP supports both transport modes. Stdio transport (used by Claude Desktop, Claude Code, ChatGPT Desktop) installs via npx -y @ranki.io/mcp. HTTP transport (used by Cursor, Windsurf, Claude.ai web, and most newer clients) points directly at https://mcp.ranki.io. Same tools, same data, different wire format. The npx @ranki.io/cli install command handles every editor automatically."}},
  {"@type":"Question","name":"Does Ranki MCP cost anything?","acceptedAnswer":{"@type":"Answer","text":"15 of 22 tools are free, rate-limited to 5 calls per IP per day. Those cover SEO audits, AEO audits, PageSpeed Insights, image optimization recipes, sitemap / robots / llms.txt generation, hidden-page classification, title and meta proposals, topic ideas and keyword gaps. The 7 paid bridge tools require a Ranki.io API key (paid plan): list_projects, list_articles, get_article, get_account, list_rank_tracking, list_gsc_keywords, ai_visibility. Get one at app.ranki.io/developer."}},
  {"@type":"Question","name":"Does the MCP actually fix my code, or just generate reports?","acceptedAnswer":{"@type":"Answer","text":"The MCP returns deterministic prescriptions (fix recipes, exact commands, ready-to-paste HTML). Your agent (Claude Code, Cursor, Windsurf) reads them and writes the changes into your repo: converts PNGs to AVIF and WebP with sharp-cli, rewrites every <img> as a responsive <picture> with srcset, adds alt text, drops in JSON-LD schema, generates llms.txt, removes hidden pages from sitemap.xml. Then re-runs the audit to confirm the score moved. All in one IDE session."}},
  {"@type":"Question","name":"How does Ranki MCP help me get cited by ChatGPT and Google AI Overviews?","acceptedAnswer":{"@type":"Answer","text":"The audit_aeo tool checks the eight structural signals AI search engines use to pick citations: FAQPage JSON-LD, Article schema, definitional intros under 80 words, author bylines, llms.txt presence, robots.txt allowing GPTBot and ClaudeBot and PerplexityBot, answer-style H2 headings, and comparison tables. For each failing check it returns a copy-pasteable fix recipe your AI applies to your code."}},
  {"@type":"Question","name":"Was Ranki MCP built by SEO professionals?","acceptedAnswer":{"@type":"Answer","text":"Yes. Ranki MCP is the dev surface of Ranki.io, an AI SEO + AEO automation platform built by Younes Lamnabhi (SEO professional since 2009). The advisor tools encode 17 years of on-page SEO and three years of AEO experience. The product itself audits sites for thousands of paying users every day."}},
  {"@type":"Question","name":"Can I see the source code? Is it open source?","acceptedAnswer":{"@type":"Answer","text":"Yes. Both repositories are MIT-licensed and live at github.com/1fancy/seo-aeo-audit-mcp-ranki (PHP server plus the @ranki.io/mcp npx shim) and github.com/1fancy/ranki-seo-skills (the companion Claude Skill plus Cursor and Windsurf rule files)."}},
  {"@type":"Question","name":"What is the Ranki Skill and how does it relate to the MCP server?","acceptedAnswer":{"@type":"Answer","text":"The MCP server gives your AI new tools. The Skill (a Markdown file) gives your AI a playbook — when to call which tool, in what order, how to interpret the results, and how to apply fixes to your codebase. Together they turn Claude or Cursor into a senior SEO consultant with a written runbook. Install the Skill at github.com/1fancy/ranki-seo-skills."}}
]}
</script>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-aurora"></div>

<header class="site-header">
  <div class="container hdr">
    <a href="/" class="logo" aria-label="Ranki MCP home">
      <img src="https://ranki.io/assets/images/ranki-logo-90h.png" alt="Ranki" width="78" height="22">
      <span class="pill">MCP</span>
    </a>
    <nav class="nav">
      <a href="#tools">Tools</a>
      <a href="#install">Install</a>
      <a href="#skill">Skill</a>
      <a href="#why">Why</a>
      <a href="#faq">FAQ</a>
      <a href="https://github.com/1fancy/seo-aeo-audit-mcp-ranki" target="_blank" rel="noopener">GitHub</a>
      <a href="https://app.ranki.io/developer" class="btn btn-primary">Get API key →</a>
    </nav>
    <button class="mobile-toggle" id="mobileToggle" aria-label="Open menu" aria-controls="mobileMenu" aria-expanded="false">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    </button>
  </div>
</header>

<!-- Mobile menu drawer -->
<div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true" aria-label="Menu">
  <div class="mobile-menu-head">
    <a href="/" class="logo">
      <img src="https://ranki.io/assets/images/ranki-logo-90h.png" alt="Ranki" width="78" height="22">
      <span class="pill">MCP</span>
    </a>
    <button class="mobile-toggle" id="mobileClose" aria-label="Close menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M6 18L18 6"/></svg>
    </button>
  </div>
  <nav>
    <a href="#tools">Tools</a>
    <a href="#install">Install</a>
    <a href="#skill">Skill</a>
    <a href="#why">How the fix loop works</a>
    <a href="#faq">FAQ</a>
    <a href="https://github.com/1fancy/seo-aeo-audit-mcp-ranki" target="_blank" rel="noopener">MCP source · GitHub</a>
    <a href="https://github.com/1fancy/ranki-seo-skills" target="_blank" rel="noopener">Skill source · GitHub</a>
    <a href="https://ranki.io/developers" target="_blank" rel="noopener">Developer docs</a>
  </nav>
  <div class="footer-cta">
    <a href="https://app.ranki.io/developer" class="btn btn-primary">Get free API key →</a>
  </div>
</div>

<main>

  <section class="hero">
    <div class="container">
      <div class="eyebrow"><span class="live-dot"></span> Free SEO audit tool · MCP server · 22 SEO tools</div>
      <h1>Free <span class="accent">SEO and AEO audit tool</span><br>for vibe coders</h1>
      <p class="lede">Rank on Google. Get cited by ChatGPT, Claude and Perplexity. Pass Core Web Vitals. Fix the slow images, the missing schema and the broken meta &mdash; all from inside Claude Code, Cursor, Windsurf or ChatGPT Desktop, in minutes, without learning SEO. One install. 22 tools. Free.</p>
      <div class="cta-row">
        <a href="#install" class="btn btn-primary btn-xl">Install in 30 seconds →</a>
        <a href="#tools" class="btn btn-ghost btn-xl">See the 22 tools</a>
      </div>

      <div class="compat-row">
        <span class="compat-label">Works with</span>
        <span><strong>Claude Code</strong></span>
        <span><strong>Claude Desktop</strong></span>
        <span><strong>Cursor</strong></span>
        <span><strong>Windsurf</strong></span>
        <span><strong>ChatGPT Desktop</strong></span>
        <span><strong>Claude.ai web</strong></span>
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

  <section id="for-who" style="padding-top:1rem">
    <div class="container">
      <div class="section-head">
        <h2>SEO changes every quarter. We track it so you don't have to.</h2>
        <p>If you've shipped a Next.js, Astro, Lovable, v0, or Bolt.new site this year without ever opening Google Search Console — this is for you. New rules drop monthly. Penalties get triggered quietly. Most vibe-coded sites are missing 70% of the signals AI search engines look for.</p>
      </div>
      <div class="for-narrative">
        <div class="for-step">
          <div class="n">01</div>
          <div>
            <h3>You shipped a site. It's invisible.</h3>
            <p>Beautiful UI, Lighthouse green, zero organic traffic. ChatGPT, Claude, and Perplexity never mention your page — because it has no <strong>FAQPage schema</strong>, no <span class="mono">llms.txt</span>, no author byline, and the H2 headings aren't phrased as questions. Your site is technically online but invisible to the engines that decide who gets cited.</p>
            <span class="ref">→ audit_aeo finds all 8 missing signals in 5 seconds</span>
          </div>
        </div>
        <div class="for-step">
          <div class="n">02</div>
          <div>
            <h3>You haven't tracked the updates. Nobody has.</h3>
            <p>Google's <strong>Helpful Content update</strong> demotes AI-only content. <span class="mono">llms.txt</span> became a real standard in 2025. <span class="mono">Google-Extended</span> opts you in or out of AI Overviews. Backlink rules changed (again). None of this was a thing when you learned web dev — and yes, three of those changed last quarter too.</p>
            <span class="ref">→ explain_seo_terms translates every word in an audit</span>
          </div>
        </div>
        <div class="for-step">
          <div class="n">03</div>
          <div>
            <h3>Penalties happen quietly. You'd never know.</h3>
            <p>Doorway pages, cloaking, paid backlinks, scaled AI content — sites get demoted overnight by Google's spam systems. Most owners never check Search Console until traffic vanishes. <strong>Knowing the jargon is the first defense.</strong> Your AI applies the fixes once it knows what to look for.</p>
            <span class="ref">→ seo_starter_kit ships the 4 baseline files most sites lack</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="tools">
    <div class="container">
      <div class="section-head">
        <h2>22 tools. One job: rank you.</h2>
        <p>From "I just shipped my site" to ranking on Google and getting cited by AI search &mdash; without you ever opening a 40-page audit PDF. Each tool returns exactly what your agent needs to fix the issue and move on.</p>
        <div class="tools-cats" style="margin-top:1.4rem">
          <span class="tools-cat-pill"><span class="dot"></span> 15 free</span>
          <span class="tools-cat-pill"><span class="dot key"></span> 7 with API key</span>
          <span class="tools-cat-pill" style="background:transparent;color:var(--ink-3)">all open source</span>
        </div>
      </div>

      <div class="tools-wrap">

        <div class="tool-cat">
          <div class="tool-cat-head"><h3>Discovery &mdash; what should I do next?</h3><span class="badge">3 tools · free</span></div>
          <div class="tools-grid">
            <div class="tool-cell"><h4>seo_starter_kit(domain)</h4><p>You shipped a site. We hand back the exact <span class="mono">robots.txt</span>, <span class="mono">sitemap.xml</span>, <span class="mono">llms.txt</span> and JSON-LD templates &mdash; plus deploy order. Your agent writes the files into your repo.</p></div>
            <div class="tool-cell"><h4>find_topic_ideas(url)</h4><p>You don't know what to blog about. We sniff your niche and tell your agent how to generate 15 topics across informational, commercial and transactional intent &mdash; with prioritization.</p></div>
            <div class="tool-cell"><h4>find_keyword_gap(url, competitors[])</h4><p>You suspect competitors are taking your keywords. We return the gap-analysis methodology &mdash; your agent walks the user through it.</p></div>
          </div>
        </div>

        <div class="tool-cat">
          <div class="tool-cat-head"><h3>Diagnose &mdash; what's broken right now?</h3><span class="badge">3 tools · free</span></div>
          <div class="tools-grid">
            <div class="tool-cell"><h4>audit_aeo(url)</h4><p>The 8 signals ChatGPT, Claude, Perplexity and Google AI Overviews use to pick citations. Each failing check ships with a copy-pasteable fix recipe.</p></div>
            <div class="tool-cell"><h4>audit_seo(url)</h4><p>On-page SEO scorecard. Ten checks scored 0&ndash;100 &mdash; title length, meta description, H1, canonical, viewport, HTTPS, OG, alt coverage, internal links, JSON-LD.</p></div>
            <div class="tool-cell"><h4>audit_hidden_pages(urls[])</h4><p>Classifies every URL as <span class="mono">robots-disallow</span>, <span class="mono">noindex</span>, <span class="mono">keep</span> or <span class="mono">unsure</span> &mdash; catches admin pages, search results and checkouts that shouldn't be in Google.</p></div>
          </div>
        </div>

        <div class="tool-cat">
          <div class="tool-cat-head"><h3>Speed &amp; images &mdash; this is the part nothing else does</h3><span class="badge">3 tools · free</span></div>
          <div class="tools-grid">
            <div class="tool-cell"><h4>audit_speed(url, strategy)</h4><p>Real Lighthouse scores and Core Web Vitals via Google PageSpeed Insights. Returns LCP / CLS / INP, image opportunities with bytes saved per file, render-blocking JS / CSS, failing on-page SEO audits.</p></div>
            <div class="tool-cell"><h4>audit_core_web_vitals(url)</h4><p>One paragraph per metric &mdash; with the literal fix recipe. <em>&ldquo;LCP element is hero.png at 2.4&nbsp;MB, convert to WebP saves 1.8&nbsp;MB &rarr; -1.1s LCP.&rdquo;</em> Picks the LCP element URL so the agent knows which file to optimize.</p></div>
            <div class="tool-cell"><h4>optimize_images(images[])</h4><p>For each image: target format (AVIF + WebP), responsive 1&times;/2&times; widths, alt suggestion, the literal sharp-cli / cwebp / avifenc command, and a ready-to-paste <span class="mono">&lt;picture&gt;</span> block with <span class="mono">srcset</span>. Your agent converts the files locally and rewrites the markup.</p></div>
          </div>
        </div>

        <div class="tool-cat">
          <div class="tool-cat-head"><h3>Generate &mdash; give me the file to deploy</h3><span class="badge">3 tools · free</span></div>
          <div class="tools-grid">
            <div class="tool-cell"><h4>generate_sitemap_xml(urls[])</h4><p>Pass your URL list, get back a deploy-ready sitemap with current lastmod. Submit to Google Search Console immediately.</p></div>
            <div class="tool-cell"><h4>generate_llms_txt(...)</h4><p>The emerging <span class="mono">llms.txt</span> standard for telling LLMs what your site is about and how to cite you. Single highest-signal AEO file most sites are missing.</p></div>
            <div class="tool-cell"><h4>generate_robots_txt(...)</h4><p>Build a <span class="mono">robots.txt</span> that explicitly allows GPTBot, ClaudeBot, PerplexityBot, Google-Extended. Default: allow &mdash; you want the citation traffic.</p></div>
          </div>
        </div>

        <div class="tool-cat">
          <div class="tool-cat-head"><h3>Content &amp; install</h3><span class="badge">3 tools · free</span></div>
          <div class="tools-grid">
            <div class="tool-cell"><h4>propose_titles_metas(urls[])</h4><p>For each page, 5 title + meta-description candidates across 5 angles (descriptive, benefit-led, question, specific, keyword-first). User picks, agent applies. Length-validated.</p></div>
            <div class="tool-cell"><h4>explain_seo_terms(category?)</h4><p>Reference glossary of 40+ SEO + AEO terms &mdash; what every word in an audit means. Categories: basics, AEO, technical, analytics, penalties.</p></div>
            <div class="tool-cell"><h4>install_skill(agent?)</h4><p>The exact install commands for the Ranki SEO Skill across Claude Code, Claude Desktop, Cursor, Windsurf, Claude.ai web Projects, or generic <span class="mono">AGENTS.md</span>.</p></div>
          </div>
        </div>

        <div class="tool-cat">
          <div class="tool-cat-head"><h3>Your Ranki.io account &mdash; real ranking data, inside your IDE</h3><span class="badge">7 tools · API key</span></div>
          <div class="tools-grid">
            <div class="tool-cell"><span class="key-flag">Key</span><h4>get_account()</h4><p>Whoami for your API key. Returns name, email, plan, daily/monthly limits, current usage. Best first call after pasting a key.</p></div>
            <div class="tool-cell"><span class="key-flag">Key</span><h4>list_projects()</h4><p>List the projects in your Ranki.io account &mdash; pulls your content pipeline into the same Claude or Cursor conversation.</p></div>
            <div class="tool-cell"><span class="key-flag">Key</span><h4>list_articles(project_id)</h4><p>Paginated article index for one project &mdash; nano_id, title, status, language, focus keywords, TOC outline, word count, SEO score. Pick one, then call <span class="mono">get_article</span>.</p></div>
            <div class="tool-cell"><span class="key-flag">Key</span><h4>get_article(article_id)</h4><p>Fetch a single Ranki.io article &mdash; title, HTML, focus keywords, TOC, embedded image URLs, SEO score.</p></div>
            <div class="tool-cell"><span class="key-flag">Key</span><h4>list_rank_tracking(project_id)</h4><p>The 28-day Google Search Console summary for one project: totals, top 20 keywords by clicks, top 20 opportunity keywords (position &gt; 10 with impressions &mdash; the easy wins).</p></div>
            <div class="tool-cell"><span class="key-flag">Key</span><h4>list_gsc_keywords(project_id)</h4><p>Full paginated GSC keyword list. Sortable by clicks / impressions / position / CTR with impression floors. Use when the summary isn't enough.</p></div>
            <div class="tool-cell"><span class="key-flag">Key</span><h4>ai_visibility(project_id)</h4><p>Recorded AI-citation snapshots &mdash; which of your tracked topics appeared in ChatGPT, Claude, Perplexity and Google AI Overview SERPs at capture time. Decides what to upgrade for AEO.</p></div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section id="install">
    <div class="container">
      <div class="section-head">
        <h2>Install in 30 seconds</h2>
        <p>One CLI command auto-detects your editor and writes the right config. Or paste the JSON snippet for your client below. The 15 free tools work without a key (5 calls per IP per day). A paid Ranki.io key lifts that to 500 calls/day and unlocks the 7 bridge tools that read your GSC keywords, rank tracking, AI citations and article library.</p>
      </div>

      <div style="max-width:980px;margin:0 auto 2rem;padding:1.4rem 1.6rem;border:1px solid var(--line);border-radius:12px;background:linear-gradient(135deg,rgba(247,144,108,.06),rgba(13,15,20,.6))">
        <div style="display:flex;align-items:center;gap:.6rem;font-size:.78rem;color:var(--ink-3);text-transform:uppercase;letter-spacing:.06em;font-family:'JetBrains Mono',monospace;margin-bottom:.7rem">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
          One command, every editor
        </div>
        <pre class="code" style="margin:0"><button class="copy-btn" onclick="copyCode(this)">Copy</button>npx @ranki.io/cli install
<span class="c"># later, to refresh the Skill file:</span>
npx @ranki.io/cli update
<span class="c"># diagnose your setup:</span>
RANKI_API_KEY=rk_live_... npx @ranki.io/cli check</pre>
        <p style="margin-top:.8rem;color:var(--ink-3);font-size:.84rem">Detects Claude Code, Claude Desktop, Cursor, Windsurf and ChatGPT Desktop, then writes the MCP config and downloads the Skill into the right path. Source: <a href="https://github.com/1fancy/seo-aeo-audit-mcp-ranki/tree/main/cli">github.com/1fancy/seo-aeo-audit-mcp-ranki/tree/main/cli</a>.</p>
      </div>

      <div style="max-width:980px;margin:0 auto 2rem;padding:1.1rem 1.4rem;border:1px solid var(--line);border-radius:12px;background:rgba(13,15,20,.5)">
        <div style="display:flex;align-items:center;gap:.6rem;font-size:.72rem;color:var(--ink-3);text-transform:uppercase;letter-spacing:.06em;font-family:'JetBrains Mono',monospace;margin-bottom:.55rem">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          Two open-source reference implementations
        </div>
        <p style="margin:0;color:var(--ink-2);font-size:.92rem;line-height:1.6">Both expose the same 22 tools with the same JSON output, the same SSRF guard and the same rate limits. Pick whichever fits your stack.</p>
        <div style="display:grid;grid-template-columns:1fr;gap:.6rem;margin-top:.9rem;font-size:.86rem">
          <div style="padding:.7rem .9rem;border:1px solid var(--line);border-radius:8px;background:rgba(13,15,20,.4)"><strong style="color:var(--ink);font-family:'JetBrains Mono',monospace;font-size:.78rem">PHP 8.4</strong> &nbsp;&middot;&nbsp; production deployment behind <span class="mono">mcp.ranki.io</span>, hosted by us, zero deps. <a href="https://github.com/1fancy/seo-aeo-audit-mcp-ranki/tree/main/server">View source &rarr;</a></div>
          <div style="padding:.7rem .9rem;border:1px solid var(--line);border-radius:8px;background:rgba(13,15,20,.4)"><strong style="color:var(--ink);font-family:'JetBrains Mono',monospace;font-size:.78rem">Node / TypeScript</strong> &nbsp;&middot;&nbsp; <span class="mono">npx -y @ranki.io/mcp-ts</span> for native local install (stdio) or <span class="mono">--serve</span> for self-hosted HTTP. <a href="https://github.com/1fancy/seo-aeo-audit-mcp-ranki/tree/main/ts-server">View source &rarr;</a></div>
        </div>
      </div>

      <p style="text-align:center;margin:0 auto 1.6rem;max-width:680px;color:var(--ink-3);font-size:.88rem">Or paste the right snippet manually:</p>

      <div class="install-grid">
        <div class="install-block">
          <div class="install-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            <span>Stdio · Claude Desktop · Code · ChatGPT Desktop</span>
          </div>
          <div class="install-body">
            <p class="path">~/.claude/claude_desktop_config.json</p>
            <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="k">{</span>
  <span class="v">"mcpServers"</span><span class="k">:</span> <span class="k">{</span>
    <span class="v">"ranki"</span><span class="k">:</span> <span class="k">{</span>
      <span class="v">"command"</span><span class="k">:</span> <span class="s">"npx"</span>,
      <span class="v">"args"</span><span class="k">:</span> <span class="k">[</span><span class="s">"-y"</span>, <span class="s">"@ranki.io/mcp"</span><span class="k">]</span>,
      <span class="v">"env"</span><span class="k">:</span> <span class="k">{</span> <span class="v">"RANKI_API_KEY"</span><span class="k">:</span> <span class="s">"YOUR_KEY"</span> <span class="k">}</span>
    <span class="k">}</span>
  <span class="k">}</span>
<span class="k">}</span></pre>
          </div>
        </div>

        <div class="install-block">
          <div class="install-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            <span>HTTP · Cursor · Windsurf</span>
          </div>
          <div class="install-body">
            <p class="path">.cursor/mcp.json (or .windsurf/mcp.json)</p>
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

        <div class="install-block">
          <div class="install-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            <span>Claude.ai web · Custom Connector</span>
          </div>
          <div class="install-body">
            <p class="path">claude.ai → Settings → Connectors → Add custom connector</p>
            <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c">Name:           </span>Ranki
<span class="c">URL:            </span>https://mcp.ranki.io
<span class="c">Authentication: </span>Header
<span class="c">Header name:    </span>X-API-Key
<span class="c">Header value:   </span>YOUR_KEY</pre>
            <p style="color:var(--ink-3);font-size:.8rem;margin-top:.7rem;line-height:1.5">Requires Claude.ai Pro, Team, or Enterprise. After saving, open a new chat — Ranki appears under the connector picker (🔌 icon).</p>
          </div>
        </div>

        <div class="install-block">
          <div class="install-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
            <span>Terminal · one-liner installers</span>
          </div>
          <div class="install-body">
            <p class="path">Claude Code CLI</p>
            <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button>claude mcp add ranki \
  -e RANKI_API_KEY=YOUR_KEY \
  -- npx -y @ranki.io/mcp</pre>
            <p class="path" style="margin-top:1rem">Cursor / Windsurf · write the config file</p>
            <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button>mkdir -p .cursor
cat &gt; .cursor/mcp.json &lt;&lt;'EOF'
{
  "mcpServers": {
    "ranki": {
      "url": "https://mcp.ranki.io",
      "headers": { "X-API-Key": "YOUR_KEY" }
    }
  }
}
EOF</pre>
          </div>
        </div>
      </div>

      <p style="text-align:center;margin-top:1.5rem;color:var(--ink-3);font-size:.86rem;padding:0 1rem">
        After installing, your AI's first tool call should be <span class="mono">get_account</span> — it confirms your key works and shows your plan + limits. If the key is wrong, you'll get a precise error with a fix link.
      </p>
    </div>
  </section>

  <section id="skill">
    <div class="container">
      <div class="section-head">
        <h2>And the Skill — a written playbook for your AI</h2>
        <p>Tools give your AI new capabilities. The Skill gives it a runbook — when to call which tool, in what order, how to interpret the output, where in your codebase the fix typically lives.</p>
      </div>

      <div class="skill-block">
        <div>
          <h3>One Markdown file. Auto-activates on SEO/AEO prompts.</h3>
          <p>Drop the SKILL.md into <span class="mono">~/.claude/skills/ranki-seo/</span> for Claude Code, or use the matching <span class="mono">.cursorrules</span> / <span class="mono">.windsurfrules</span> / <span class="mono">AGENTS.md</span> for other agents.</p>
          <ul>
            <li>Auto-activates when you mention SEO, AEO, sitemap, llms.txt, ranking, "ChatGPT isn't citing my docs," and 20+ other vibe-coder phrases</li>
            <li>Five pre-built activation patterns: starter-kit flow, AEO-citation flow, topic-discovery, keyword-gap, perf</li>
            <li>Hard constraints baked in — never recommend the forbidden word "outrank," never push the upgrade on trivial fixes</li>
            <li>MIT licensed, customizable per project</li>
          </ul>
          <div class="cta-row" style="justify-content:flex-start;margin-bottom:0;padding:0">
            <a href="https://github.com/1fancy/ranki-seo-skills" class="btn btn-primary" target="_blank" rel="noopener">View on GitHub →</a>
            <a href="https://ranki.io/developers/skill" class="btn btn-ghost" target="_blank" rel="noopener">Install guide</a>
          </div>
        </div>
        <div>
          <div class="skill-tabs" role="tablist" aria-label="Pick your AI editor">
            <div class="skill-tabs-list">
              <button role="tab" aria-selected="true" data-skill-tab="cli" onclick="rkSkillTab(this)">One-line CLI</button>
              <button role="tab" aria-selected="false" data-skill-tab="claude-code" onclick="rkSkillTab(this)">Claude Code</button>
              <button role="tab" aria-selected="false" data-skill-tab="claude-desktop" onclick="rkSkillTab(this)">Claude Desktop</button>
              <button role="tab" aria-selected="false" data-skill-tab="cursor" onclick="rkSkillTab(this)">Cursor</button>
              <button role="tab" aria-selected="false" data-skill-tab="windsurf" onclick="rkSkillTab(this)">Windsurf</button>
              <button role="tab" aria-selected="false" data-skill-tab="claude-web" onclick="rkSkillTab(this)">Claude.ai web</button>
              <button role="tab" aria-selected="false" data-skill-tab="agents" onclick="rkSkillTab(this)">AGENTS.md</button>
            </div>

            <div class="skill-tab-panel active" data-skill-panel="cli">
              <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># Installs MCP + Skill for whichever editor you have</span>
npx @ranki.io/cli install</pre>
            </div>

            <div class="skill-tab-panel" data-skill-panel="claude-code">
              <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># User-level Skill (works in every project)</span>
mkdir -p ~/.claude/skills/ranki-seo
curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/SKILL.md \
  -o ~/.claude/skills/ranki-seo/SKILL.md

<span class="c"># Then restart Claude Code.</span></pre>
            </div>

            <div class="skill-tab-panel" data-skill-panel="claude-desktop">
              <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># Same file path as Claude Code &mdash; user-level Skill</span>
mkdir -p ~/.claude/skills/ranki-seo
curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/SKILL.md \
  -o ~/.claude/skills/ranki-seo/SKILL.md

<span class="c"># Restart Claude Desktop.</span></pre>
            </div>

            <div class="skill-tab-panel" data-skill-panel="cursor">
              <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># Project-level rule file (run in your repo root)</span>
curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/.cursorrules \
  -o .cursorrules

<span class="c"># Cursor picks it up automatically &mdash; no restart needed.</span></pre>
            </div>

            <div class="skill-tab-panel" data-skill-panel="windsurf">
              <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># Project-level rule file (run in your repo root)</span>
curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/.windsurfrules \
  -o .windsurfrules</pre>
            </div>

            <div class="skill-tab-panel" data-skill-panel="claude-web">
              <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># No file install &mdash; uses Claude.ai Projects</span>
<span class="c"># 1. claude.ai &rarr; Projects &rarr; New project</span>
<span class="c"># 2. Custom instructions:</span>
<span class="c">#    paste the body of SKILL.md (skip the YAML frontmatter)</span>
<span class="c"># 3. Every chat in the Project auto-loads it.</span>

<span class="c"># Get the body here:</span>
<span class="c"># https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/SKILL.md</span></pre>
            </div>

            <div class="skill-tab-panel" data-skill-panel="agents">
              <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># Generic agent file (Continue.dev, Zed AI, OpenAI Codex, custom MCP)</span>
curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/AGENTS.md \
  -o AGENTS.md</pre>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="why">
    <div class="container">
      <div class="section-head">
        <h2>Other SEO tools send you a 40-page PDF. We ship the fix.</h2>
        <p>You ask. The agent diagnoses, fixes your code, and proves the score moved &mdash; in one conversation. Lighthouse goes from 42 to 96. AEO goes from 38 to 96. Slow pages get fast. ChatGPT and Claude start citing you within weeks.</p>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;max-width:1000px;margin:1rem auto 0;padding:0 1rem">
        <div style="padding:1.3rem;border:1px solid var(--line);border-radius:10px;background:rgba(247,144,108,.03)">
          <div style="font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--orange);letter-spacing:.08em;margin-bottom:.6rem">STEP 1 · YOU ASK</div>
          <p style="color:var(--ink-2);font-size:.92rem;line-height:1.6"><strong style="color:var(--ink)">&ldquo;Audit my site and fix what&rsquo;s broken.&rdquo;</strong> Plain English. No SEO knowledge required. Works on any URL.</p>
        </div>
        <div style="padding:1.3rem;border:1px solid var(--line);border-radius:10px;background:rgba(247,144,108,.03)">
          <div style="font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--orange);letter-spacing:.08em;margin-bottom:.6rem">STEP 2 · AGENT DIAGNOSES</div>
          <p style="color:var(--ink-2);font-size:.92rem;line-height:1.6"><strong style="color:var(--ink)">Finds the real problems.</strong> The slow images. The missing schema. The pages Google hates. The H2s that ChatGPT can&rsquo;t cite.</p>
        </div>
        <div style="padding:1.3rem;border:1px solid var(--line);border-radius:10px;background:rgba(247,144,108,.03)">
          <div style="font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--orange);letter-spacing:.08em;margin-bottom:.6rem">STEP 3 · AGENT FIXES</div>
          <p style="color:var(--ink-2);font-size:.92rem;line-height:1.6"><strong style="color:var(--ink)">Writes the changes into your code.</strong> Compresses your images. Adds the missing meta. Generates the files. Commits.</p>
        </div>
        <div style="padding:1.3rem;border:1px solid var(--line);border-radius:10px;background:rgba(247,144,108,.03)">
          <div style="font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--orange);letter-spacing:.08em;margin-bottom:.6rem">STEP 4 · YOU RANK</div>
          <p style="color:var(--ink-2);font-size:.92rem;line-height:1.6"><strong style="color:var(--ink)">Lighthouse green. AEO 96/100.</strong> Deploy and ChatGPT, Claude, Perplexity and Google start citing you within weeks.</p>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:.5rem;max-width:880px;margin:2.5rem auto 0;padding:0 1rem">
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">Same answer, every time.</strong>Audits return identical results for the same site &mdash; no AI guessing, no hallucinated &ldquo;you should probably&rdquo;.</p>
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">Open source forever.</strong>MIT licensed. Self-host the whole thing if you want. Stop using us tomorrow and the fixes still work.</p>
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">Your real ranking data.</strong>Paid Ranki.io accounts plug in your Google Search Console keywords, AI citations and rank tracking &mdash; right in your IDE.</p>
      </div>
    </div>
  </section>

  <section id="faq">
    <div class="container">
      <div class="section-head"><h2>Real questions, real answers</h2><p>What vibe-coders actually search for before installing an MCP server.</p></div>
      <div class="faq">
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">What is an MCP server, and why do I need one for SEO?</div><div class="faq-a">MCP (Model Context Protocol) is the standard <a href="https://modelcontextprotocol.io" target="_blank" rel="noopener">Anthropic introduced in late 2024</a> for connecting AI agents to external tools. Ranki MCP adds SEO + AEO auditing, sitemap and <span class="mono">llms.txt</span> generation, keyword-gap analysis, and topic discovery — so your AI can fix your vibe-coded site without you having to learn SEO terminology.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Does this work with Cursor? Windsurf? Claude.ai web? Lovable? v0? Bolt.new?</div><div class="faq-a"><strong style="color:var(--ink)">Cursor and Windsurf:</strong> yes, native HTTP MCP support — point them at <span class="mono">https://mcp.ranki.io</span> with an <span class="mono">X-API-Key</span> header.<br><br><strong style="color:var(--ink)">Claude Code, Claude Desktop, ChatGPT Desktop:</strong> yes — stdio MCP via <span class="mono">npx -y @ranki.io/mcp</span>.<br><br><strong style="color:var(--ink)">Claude.ai web (Pro / Team / Enterprise):</strong> yes — add as a Custom Connector under Settings → Connectors. Same URL + header.<br><br><strong style="color:var(--ink)">Lovable, v0, Bolt.new:</strong> those generate code but don't expose MCP configs in their UI yet. Workaround — install Ranki MCP in your Claude Code or Cursor session, then ask it to refactor the Lovable / v0 / Bolt output. Same result.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">How do I know my API key is set up correctly?</div><div class="faq-a">Ask your AI to call <span class="mono">get_account</span> — it's the whoami of Ranki MCP. If your key works, you get back your name, email, plan, daily and monthly limits, and current usage. If the key is wrong, missing, or revoked, you get a precise error message telling you exactly which line in the MCP config to fix.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Can I use the Skill on Claude.ai web?</div><div class="faq-a">Claude.ai web doesn't load <span class="mono">SKILL.md</span> files directly the way Claude Code does. But two workarounds: (1) open <strong>Claude.ai Projects</strong> → create a new Project → paste the body of <span class="mono">SKILL.md</span> into the custom instructions — every chat in that Project auto-loads it. (2) The Custom Connector already exposes the Skill's tool selection logic via the tool descriptions themselves, so Claude.ai picks the right tool for the right prompt without the Skill file. Less rich, but works in zero seconds.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Does Ranki MCP use my Claude credits or yours?</div><div class="faq-a"><strong style="color:var(--ink)">Yours.</strong> The MCP server returns structured advice (checklists, fix recipes, generated files). Your AI evaluates them against your code using your own credits. We never make LLM calls on your behalf — that's why the advisor tools can run free.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Was Ranki MCP built by actual SEO professionals?</div><div class="faq-a">Yes. The product is the developer surface of <a href="https://ranki.io" target="_blank" rel="noopener">Ranki.io</a>, an AI SEO + AEO automation platform built by Younes Lamnabhi (in SEO since 2009 — 17 years of on-page work + 3 years of AEO research). The audit logic encodes patterns we use daily for thousands of paying users.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">What does AEO mean? Why should I care?</div><div class="faq-a"><strong style="color:var(--ink)">Answer Engine Optimization</strong> — the structural signals (FAQPage schema, definitional intros, author bylines, <span class="mono">llms.txt</span>, comparison tables) that ChatGPT, Claude, Perplexity, and Google AI Overviews use to pick which sites to cite. In 2026, AEO is the fastest-growing search channel and most sites have zero coverage. Audit yours with <span class="mono">audit_aeo</span> in 5 seconds.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Will I need to learn how to read SEO reports?</div><div class="faq-a">No. The advisor tools return advice in a format your AI parses — checklists with pass/fail and copy-pasteable fix recipes. Your AI applies the fixes; you read the diff if you want to.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Do I need a Ranki.io account?</div><div class="faq-a">No, for the 15 free tools — they work without a key, rate-limited to 5 calls per IP per UTC day. Yes for the 7 paid bridge tools (<span class="mono">list_projects</span>, <span class="mono">list_articles</span>, <span class="mono">get_article</span>, <span class="mono">get_account</span>, <span class="mono">list_rank_tracking</span>, <span class="mono">list_gsc_keywords</span>, <span class="mono">ai_visibility</span>) which read your real Google Search Console data, AI citations and article library from your Ranki.io account. <a href="https://app.ranki.io/developer">Get a key →</a></div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Is the source code open?</div><div class="faq-a">Yes — MIT license. PHP server and npx shim at <a href="https://github.com/1fancy/seo-aeo-audit-mcp-ranki" target="_blank" rel="noopener">github.com/1fancy/seo-aeo-audit-mcp-ranki</a>. The companion Claude / Cursor / Windsurf Skill bundle at <a href="https://github.com/1fancy/ranki-seo-skills" target="_blank" rel="noopener">github.com/1fancy/ranki-seo-skills</a>.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">How is this different from Surfer SEO, Frase, SEMrush, Ahrefs?</div><div class="faq-a">Those are SaaS dashboards — you log in, paste a URL, get a report, switch back to your IDE, copy/paste the recommendations into your code. Ranki MCP lives <em>inside</em> your IDE. Your AI calls the tools inline, applies the fixes to your files, re-runs the audit. Different shape, different price (free), different audience (devs vibe-coding, not SEO professionals running campaigns).</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Will Google penalize sites optimized this way?</div><div class="faq-a">No. Every check Ranki MCP runs is documented in <a href="https://developers.google.com/search/docs" target="_blank" rel="noopener">Google's own search docs</a> or the schema.org spec. We don't generate doorway pages, keyword-stuffed content, or anything that violates anti-spam guidelines. We're the opposite of "black-hat" — we make sites <em>more</em> readable to Google + AI.</div></div>
      </div>
    </div>
  </section>

</main>

<footer>
  <div class="container">
    <div class="foot">
      <div>
        <a href="/" class="logo" style="margin-bottom:1rem">
          <img src="https://ranki.io/assets/images/ranki-logo-90h.png" alt="Ranki" width="78" height="22">
          <span class="pill">MCP</span>
        </a>
        <p class="tag">Part of <a href="https://ranki.io" style="color:var(--ink-2);text-decoration:underline">Ranki.io</a> — the AI SEO + AEO automation platform crafted by SEO pros for the people shipping sites with AI.</p>
      </div>
      <div>
        <h4>MCP server</h4>
        <a href="#install">Install</a>
        <a href="#tools">22 tools</a>
        <a href="https://github.com/1fancy/seo-aeo-audit-mcp-ranki" target="_blank" rel="noopener">Source · MIT</a>
        <a href="https://www.npmjs.com/package/@ranki.io/mcp" target="_blank" rel="noopener">npm @ranki.io/mcp</a>
      </div>
      <div>
        <h4>Skill bundle</h4>
        <a href="#skill">What it does</a>
        <a href="https://github.com/1fancy/ranki-seo-skills" target="_blank" rel="noopener">Source · MIT</a>
        <a href="https://ranki.io/developers/skill" target="_blank" rel="noopener">Install guide</a>
      </div>
      <div>
        <h4>Resources</h4>
        <a href="https://app.ranki.io/developer">Free API key</a>
        <a href="https://ranki.io/learn/aeo-guide">AEO guide</a>
        <a href="https://ranki.io/learn/seo-guide-2026">SEO guide 2026</a>
        <a href="https://ranki.io/privacy">Privacy</a>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© <?= date('Y') ?> Ranki.io. MIT licensed. Built in the open.</span>
      <span class="mono">mcp.ranki.io · v0.2</span>
    </div>
  </div>
</footer>

<script>
// ============== Mobile menu ==============
const toggleBtn = document.getElementById('mobileToggle');
const closeBtn = document.getElementById('mobileClose');
const mobileMenu = document.getElementById('mobileMenu');
function openMenu(){ mobileMenu.classList.add('is-open'); toggleBtn.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; }
function closeMenu(){ mobileMenu.classList.remove('is-open'); toggleBtn.setAttribute('aria-expanded','false'); document.body.style.overflow=''; }
toggleBtn.addEventListener('click', openMenu);
closeBtn.addEventListener('click', closeMenu);
// Close on link click (so anchor scroll works)
mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
// Close on Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) closeMenu(); });

// ============== Animated hero terminal ==============
const termLines = [
  ['<span class="p">›</span> <span class="you">audit my site speed and fix the slow images</span>', 350],
  ['', 700],
  ['<span class="ai">Calling <span class="tag">ranki.audit_speed</span><span class="dim">(url="myapp.dev")</span>…</span>', 400],
  ['<span class="dim">  → Lighthouse: 42 perf · LCP 4.8s · 3 images blocking the paint.</span>', 600],
  ['', 200],
  ['<span class="ai">Calling <span class="tag">ranki.optimize_images</span><span class="dim">([hero.png, banner.jpg, card.png])</span>…</span>', 400],
  ['<span class="dim">  → 3 files, recipes returned. Converting locally…</span>', 400],
  ['<span class="ai">Running sharp-cli → hero.avif, hero.webp, hero@2x.avif…</span>', 280],
  ['<span class="ai">Editing app/page.tsx — rewriting &lt;img&gt; as &lt;picture srcset&gt;, adding alt…</span>', 320],
  ['', 200],
  ['<span class="ai">Re-running <span class="tag">ranki.audit_speed</span>…</span>', 350],
  ['<span class="ok">  ✓ Lighthouse: 96 perf · LCP 1.4s. Commit and deploy.</span>', 1200],
];
const term = document.getElementById('termBody');
let lineIdx = 0;
function nextLine(){
  if (lineIdx >= termLines.length) {
    // End of the script — leave the final state visible with a blinking
    // prompt + cursor, so the terminal looks alive but doesn't restart.
    term.innerHTML += '<br><span class="p">›</span> <span class="cursor"></span>';
    term.scrollTop = term.scrollHeight;
    return;
  }
  const [html, delay] = termLines[lineIdx++];
  term.innerHTML += (html === '' ? '<br>' : html + '<br>');
  term.scrollTop = term.scrollHeight;
  setTimeout(nextLine, delay);
}
term.innerHTML = '<span class="p">›</span> <span class="cursor"></span>';
setTimeout(() => { term.innerHTML = ''; nextLine(); }, 800);

// ============== Skill install tabs ==============
function rkSkillTab(btn){
  const target = btn.dataset.skillTab;
  const container = btn.closest('.skill-tabs');
  if (!container) return;
  container.querySelectorAll('[role="tab"]').forEach(b => {
    b.setAttribute('aria-selected', b === btn ? 'true' : 'false');
  });
  container.querySelectorAll('.skill-tab-panel').forEach(p => {
    p.classList.toggle('active', p.dataset.skillPanel === target);
  });
}

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
