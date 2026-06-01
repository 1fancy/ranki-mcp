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
<title>MCP for Cursor, Claude Code & Windsurf — Free SEO + AEO advisor | Ranki MCP</title>
<meta name="description" content="Free MCP server crafted by SEO pros for vibe-coders. Plugs into Cursor, Claude Code, Claude Desktop, Windsurf, ChatGPT Desktop, Claude.ai web — audits SEO + Answer Engine Optimization, generates sitemap.xml, llms.txt, robots.txt, finds keyword gaps. Uses your AI credits, never ours.">
<meta name="keywords" content="MCP server, Model Context Protocol, MCP for Cursor, MCP for Claude Code, MCP for Windsurf, MCP for ChatGPT, AEO MCP, SEO MCP, llms.txt generator, sitemap.xml generator, robots.txt generator, vibe coding SEO, Answer Engine Optimization, Claude SEO tool, Cursor SEO, ChatGPT citation, AI search optimization, vibe coded site SEO, get cited by ChatGPT, get cited by Perplexity, Google AI Overviews SEO, schema.org FAQPage">
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
<meta property="og:title" content="MCP for Cursor, Claude Code & Windsurf — Free SEO + AEO advisor">
<meta property="og:description" content="Drops into your IDE. Audits any URL for SEO + AEO, generates sitemap.xml, llms.txt, robots.txt. Built by SEO pros for vibe-coders. Uses your own AI credits — never ours.">
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
<meta name="twitter:title" content="MCP for Cursor, Claude Code & Windsurf — Free SEO + AEO advisor">
<meta name="twitter:description" content="Drops into your IDE. Audits SEO + AEO, generates sitemap.xml / llms.txt / robots.txt. Uses your AI credits, never ours.">
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
<link rel="preconnect" href="https://ranki.io">
<link rel="dns-prefetch" href="https://app.ranki.io">
<link rel="dns-prefetch" href="https://github.com">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Ranki Black — title font, served from ranki.io */
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
  --purple:#b794f6;
  --container:1180px;
}

*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
html,body{background:var(--bg);color:var(--ink);font-family:'Inter',system-ui,sans-serif;line-height:1.65;-webkit-font-smoothing:antialiased;font-feature-settings:'ss01','cv11';overflow-x:hidden}
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

/* ============== "FOR WHO" — vibe coder positioning ============== */
.for-who{display:grid;grid-template-columns:1fr;gap:1.2rem;max-width:980px;margin:0 auto}
@media (min-width:780px){.for-who{grid-template-columns:1fr 1fr 1fr}}
.for-row{padding:1.4rem 1.5rem;border-left:2px solid var(--orange);background:linear-gradient(90deg,var(--orange-soft) 0%,transparent 60%);border-radius:0 10px 10px 0}
.for-row strong{display:block;color:var(--ink);font-size:1rem;margin-bottom:.5rem;font-weight:700}
.for-row p{color:var(--ink-2);font-size:.9rem;line-height:1.6}

/* ============== TOOLS — terminal list ============== */
.tools-block{max-width:920px;margin:0 auto;border:1px solid var(--line);border-radius:14px;background:linear-gradient(180deg,rgba(13,15,20,.9),rgba(6,7,10,.95));overflow:hidden}
.tools-head{display:flex;align-items:center;gap:.7rem;padding:.85rem 1.2rem;background:rgba(0,0,0,.25);border-bottom:1px solid var(--line);font-family:'JetBrains Mono',monospace;font-size:.74rem;color:var(--ink-3);letter-spacing:.04em;text-transform:uppercase}
@media (min-width:768px){.tools-head{padding:.9rem 1.5rem;font-size:.78rem}}
.tools-head .count{margin-left:auto;color:var(--orange);font-weight:600}
.tool-row{display:grid;grid-template-columns:auto 1fr;gap:.9rem;padding:1rem 1.2rem;border-bottom:1px solid var(--line);align-items:start;transition:background .15s}
@media (min-width:560px){.tool-row{grid-template-columns:auto 1fr auto;gap:1.1rem;padding:1.15rem 1.5rem}}
.tool-row:last-child{border-bottom:none}
.tool-row:hover{background:rgba(247,144,108,.04)}
.tool-row .icon{width:28px;height:28px;border-radius:7px;background:var(--orange-soft);border:1px solid rgba(247,144,108,.25);display:flex;align-items:center;justify-content:center;color:var(--orange);font-family:'JetBrains Mono',monospace;font-weight:700;font-size:.74rem;flex-shrink:0;margin-top:.15rem}
.tool-row h3{font-family:'JetBrains Mono',monospace;font-weight:600;font-size:.92rem;color:var(--ink);margin-bottom:.3rem;letter-spacing:-.01em;word-break:break-word}
@media (min-width:560px){.tool-row h3{font-size:.98rem}}
.tool-row p{color:var(--ink-2);font-size:.86rem;line-height:1.55;margin:0}
@media (min-width:560px){.tool-row p{font-size:.9rem}}
.tool-row .tag{grid-column:2;justify-self:start;margin-top:.5rem;font-family:'JetBrains Mono',monospace;font-size:.66rem;padding:.18rem .5rem;border-radius:4px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;background:rgba(155,229,166,.1);color:var(--green);border:1px solid rgba(155,229,166,.2)}
@media (min-width:560px){.tool-row .tag{grid-column:auto;align-self:flex-start;margin-top:.2rem}}
.tool-row .tag.key{background:var(--orange-soft);color:var(--orange);border-color:rgba(247,144,108,.3)}

/* ============== INSTALL ============== */
.install-grid{display:grid;grid-template-columns:1fr;gap:1rem;max-width:980px;margin:0 auto}
@media (min-width:780px){.install-grid{grid-template-columns:1fr 1fr}}
.install-block{border:1px solid var(--line);border-radius:12px;overflow:hidden;background:rgba(13,15,20,.6);backdrop-filter:blur(8px)}
.install-head{padding:.8rem 1.1rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:.55rem;font-size:.85rem;font-weight:600;color:var(--ink);background:rgba(0,0,0,.2);flex-wrap:wrap}
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
{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Ranki MCP","alternateName":["Ranki MCP Server","MCP for Cursor","MCP for Claude Code","MCP for Windsurf"],"url":"https://mcp.ranki.io/","applicationCategory":"DeveloperApplication","operatingSystem":"Cross-platform — Web, macOS, Linux, Windows","description":"Free Model Context Protocol server that audits SEO + AEO and generates sitemap.xml, llms.txt, robots.txt directly from Claude Code, Claude Desktop, Cursor, Windsurf, ChatGPT Desktop, and any other MCP-capable client.","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"},"creator":{"@type":"Organization","name":"Ranki.io","url":"https://ranki.io"},"sameAs":["https://github.com/1fancy/ranki-mcp","https://github.com/1fancy/ranki-seo-skills","https://ranki.io"]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"HowTo","name":"How to install Ranki MCP in Claude Code, Cursor, or Windsurf","description":"Add SEO + AEO advisor tools to any MCP-capable AI coding agent in 30 seconds.","totalTime":"PT30S","step":[{"@type":"HowToStep","position":1,"name":"Get a free API key","text":"Sign in at app.ranki.io and copy your key from the Developer page.","url":"https://app.ranki.io/developer"},{"@type":"HowToStep","position":2,"name":"Paste the MCP snippet into your client config","text":"For stdio clients (Claude Desktop, Claude Code, ChatGPT Desktop): use the npx -y @ranki/mcp command in your MCP config. For HTTP clients (Cursor, Windsurf, Claude.ai web): point at https://mcp.ranki.io with X-API-Key header."},{"@type":"HowToStep","position":3,"name":"Restart the client and try a tool","text":"Ask your AI: 'audit my site for AEO and fix it'. Your AI will call audit_aeo, return a scorecard, and apply the fixes to your repo using your own AI credits."}]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[
  {"@type":"Question","name":"What is an MCP server, and why do I need one for SEO?","acceptedAnswer":{"@type":"Answer","text":"MCP (Model Context Protocol) is the standard Anthropic introduced in late 2024 for connecting AI agents like Claude Code, Cursor, and Windsurf to external tools. An MCP server adds capabilities your AI doesn't have on its own. Ranki MCP adds SEO + AEO auditing, sitemap and llms.txt generation, keyword-gap analysis, and topic discovery — so your AI can fix your vibe-coded site without you having to learn SEO."}},
  {"@type":"Question","name":"Is Ranki MCP compatible with Cursor, Windsurf, Claude Code, and ChatGPT Desktop?","acceptedAnswer":{"@type":"Answer","text":"Yes. Ranki MCP supports both transport modes. Stdio transport (used by Claude Desktop, Claude Code, ChatGPT Desktop) installs via npx -y @ranki/mcp. HTTP transport (used by Cursor, Windsurf, Claude.ai web, and most newer clients) points directly at https://mcp.ranki.io. Same tools, same data, different wire format."}},
  {"@type":"Question","name":"Does Ranki MCP cost anything?","acceptedAnswer":{"@type":"Answer","text":"The eight advisor tools (audit_aeo, audit_seo, generate_sitemap_xml, generate_llms_txt, generate_robots_txt, seo_starter_kit, find_topic_ideas, find_keyword_gap) are free, rate-limited to five calls per IP per day. Two bridge tools (list_projects, get_article) require a free Ranki.io API key. The only LLM cost is the one your existing Claude or Cursor subscription already covers."}},
  {"@type":"Question","name":"Will Ranki MCP burn my Claude credits or my Cursor budget?","acceptedAnswer":{"@type":"Answer","text":"No more than usual. Ranki MCP returns deterministic data — checklists, generated files, fix recipes. It never calls an LLM. Your Claude or Cursor reads the response and decides what to do with it, using your existing subscription. So adding Ranki MCP costs you exactly zero new tokens."}},
  {"@type":"Question","name":"How does Ranki MCP help me get cited by ChatGPT and Google AI Overviews?","acceptedAnswer":{"@type":"Answer","text":"The audit_aeo tool checks the eight structural signals AI search engines use to pick citations: FAQPage JSON-LD, Article schema, definitional intros under 80 words, author bylines, llms.txt presence, robots.txt allowing GPTBot and ClaudeBot and PerplexityBot, answer-style H2 headings, and comparison tables. For each failing check it returns a copy-pasteable fix recipe your AI applies to your code."}},
  {"@type":"Question","name":"Was Ranki MCP built by SEO professionals?","acceptedAnswer":{"@type":"Answer","text":"Yes. Ranki MCP is the dev surface of Ranki.io, an AI SEO + AEO automation platform built by Younes Lamnabhi (SEO professional since 2009). The advisor tools encode 17 years of on-page SEO and three years of AEO experience. The product itself audits sites for thousands of paying users every day."}},
  {"@type":"Question","name":"Can I see the source code? Is it open source?","acceptedAnswer":{"@type":"Answer","text":"Yes. Both repositories are MIT-licensed and live at github.com/1fancy/ranki-mcp (PHP server plus the @ranki/mcp npx shim) and github.com/1fancy/ranki-seo-skills (the companion Claude Skill plus Cursor and Windsurf rule files)."}},
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
      <a href="https://github.com/1fancy/ranki-mcp" target="_blank" rel="noopener">GitHub</a>
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
    <a href="#why">Why advisor-only</a>
    <a href="#faq">FAQ</a>
    <a href="https://github.com/1fancy/ranki-mcp" target="_blank" rel="noopener">MCP source · GitHub</a>
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
      <div class="eyebrow"><span class="live-dot"></span> Crafted by SEO pros · For vibe-coders</div>
      <h1>SEO + AEO advisor<br>built for <span class="accent">Cursor, Claude & Windsurf</span></h1>
      <p class="lede">You ship sites with AI. They look amazing. Nobody finds them. Ranki MCP fixes that — your AI editor calls our tools, gets back the exact `robots.txt`, `sitemap.xml`, `llms.txt`, FAQPage schema and rewritten paragraphs your site needs, then applies them to your repo using <strong>your</strong> AI credits, never ours.</p>
      <div class="cta-row">
        <a href="#install" class="btn btn-primary btn-xl">Install in 30 seconds →</a>
        <a href="#tools" class="btn btn-ghost btn-xl">See the 10 tools</a>
      </div>

      <div class="compat-row">
        <span class="compat-label">Works with</span>
        <span><strong>Claude Code</strong></span>
        <span><strong>Claude Desktop</strong></span>
        <span><strong>Cursor</strong></span>
        <span><strong>Windsurf</strong></span>
        <span><strong>ChatGPT Desktop</strong></span>
        <span><strong>any MCP client</strong></span>
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
        <h2>For developers who code first, learn SEO never</h2>
        <p>If you've shipped a Next.js, Astro, Lovable, v0, or Bolt.new site this year without ever opening Google Search Console — this is for you.</p>
      </div>
      <div class="for-who">
        <div class="for-row">
          <strong>You vibe-coded a SaaS</strong>
          <p>It runs. It's pretty. Lighthouse score is fine. But ChatGPT, Claude, and Perplexity never mention it because it has no FAQPage schema, no <span class="mono">llms.txt</span>, and the H2s aren't questions.</p>
        </div>
        <div class="for-row">
          <strong>Your blog is empty</strong>
          <p>You know you "should write content." You don't know which 15 topics, in what order, for which search intent. <span class="mono">find_topic_ideas</span> hands your AI a structured brief and the prioritization criteria.</p>
        </div>
        <div class="for-row">
          <strong>Competitors are eating your lunch</strong>
          <p>They're 18 months older. You can write fresher + better. You just need to know WHICH keywords. <span class="mono">find_keyword_gap</span> tells your AI exactly how to find them.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="tools">
    <div class="container">
      <div class="section-head">
        <h2>Ten tools your AI can hold</h2>
        <p>The MCP server returns checklists, ready-to-deploy files, and fix recipes. <strong>Your</strong> AI evaluates them against your code. We never run on your tokens — that's why the advisor tools stay free.</p>
      </div>

      <div class="tools-block">
        <div class="tools-head"><span>tools/list</span><span class="count">10 tools</span></div>
        <div class="tool-row"><div class="icon">SK</div><div><h3>seo_starter_kit(domain)</h3><p>You shipped a site. We hand back the exact <span class="mono">robots.txt</span>, <span class="mono">sitemap.xml</span>, <span class="mono">llms.txt</span>, and JSON-LD structured data — plus the deploy order. Your AI writes the files into your repo.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">TI</div><div><h3>find_topic_ideas(url)</h3><p>You don't know what to blog about. We sniff your niche, return a structured brief, and tell your AI how to generate 15 topics across informational, commercial, and transactional intent — with prioritization criteria.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">KG</div><div><h3>find_keyword_gap(url, competitors[])</h3><p>You suspect competitors are stealing your keywords. We return the gap-analysis methodology — your AI walks the user through it. If no competitors given, your AI asks the user first.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">AE</div><div><h3>audit_aeo(url)</h3><p>The eight signals ChatGPT, Claude, Perplexity, and Google AI Overviews use to pick citations — FAQPage / Article JSON-LD, definitional intro, author byline, <span class="mono">llms.txt</span>, robots.txt AI allowance, answer-style headings, tables — each with a copy-pasteable fix.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">AS</div><div><h3>audit_seo(url)</h3><p>On-page SEO scorecard. Ten checks scored 0-100 — title length, meta description, H1 uniqueness, canonical, viewport, HTTPS, OpenGraph, image alt coverage, internal links, JSON-LD presence.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">SM</div><div><h3>generate_sitemap_xml(urls[])</h3><p>Pass your URL list, get back a deploy-ready sitemap with current lastmod. Submit to Google Search Console immediately.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">LT</div><div><h3>generate_llms_txt(site_name, summary, key_pages)</h3><p>The emerging <span class="mono">llms.txt</span> standard for telling LLMs what your site is about and how to cite you. The single highest-signal AEO file most sites are missing.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">RT</div><div><h3>generate_robots_txt(sitemap_url, allow_ai, disallow_paths)</h3><p>Build a <span class="mono">robots.txt</span> that explicitly allows or blocks GPTBot, ClaudeBot, PerplexityBot, Google-Extended, ChatGPT-User, anthropic-ai. Default: allow — you want AI citation traffic.</p></div><span class="tag">Free</span></div>
        <div class="tool-row"><div class="icon">LP</div><div><h3>list_projects()</h3><p>List the projects in your Ranki.io account. Pulls your own automated-content pipeline into the same Claude or Cursor conversation where you're vibe-coding.</p></div><span class="tag key">Key</span></div>
        <div class="tool-row"><div class="icon">GA</div><div><h3>get_article(article_id)</h3><p>Fetch a single Ranki.io article by nano_id — title, full HTML, focus keywords, TOC, embedded image URLs, SEO score.</p></div><span class="tag key">Key</span></div>
      </div>
    </div>
  </section>

  <section id="install">
    <div class="container">
      <div class="section-head">
        <h2>Install in 30 seconds</h2>
        <p>Pick your client. The advisor tools work without a key (five calls per IP per day). Add a free Ranki.io key to remove the limit and unlock the bridge tools.</p>
      </div>

      <div class="install-grid">
        <div class="install-block">
          <div class="install-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            Stdio · Claude Desktop · Code · ChatGPT Desktop
          </div>
          <div class="install-body">
            <p class="path">~/.claude/claude_desktop_config.json</p>
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
            HTTP · Cursor · Windsurf
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
            Claude.ai web · Custom Connector
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
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
            One-line curl · Claude Code · Cursor · Windsurf
          </div>
          <div class="install-body">
            <p class="path">Paste in your terminal (replace YOUR_KEY)</p>
            <pre class="code"><button class="copy-btn" onclick="copyCode(this)"><span class="c"># Claude Code (CLI)</span>
claude mcp add ranki \
  -e RANKI_API_KEY=YOUR_KEY \
  -- npx -y @ranki/mcp

<span class="c"># Cursor (writes .cursor/mcp.json in cwd)</span>
mkdir -p .cursor && cat > .cursor/mcp.json &lt;&lt;EOF
{"mcpServers":{"ranki":{"url":"https://mcp.ranki.io",
"headers":{"X-API-Key":"YOUR_KEY"}}}}
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
          <pre class="code"><button class="copy-btn" onclick="copyCode(this)">Copy</button><span class="c"># Claude Code · Claude Desktop (user-level)</span>
mkdir -p ~/.claude/skills/ranki-seo &amp;&amp; \
curl -fsSL https://raw.githubusercontent.com/\
1fancy/ranki-seo-skills/main/skills/\
ranki-seo/SKILL.md \
  -o ~/.claude/skills/ranki-seo/SKILL.md

<span class="c"># Cursor (project-level rule file)</span>
curl -fsSL https://raw.githubusercontent.com/\
1fancy/ranki-seo-skills/main/skills/\
ranki-seo/.cursorrules -o .cursorrules

<span class="c"># Windsurf (project-level rule file)</span>
curl -fsSL https://raw.githubusercontent.com/\
1fancy/ranki-seo-skills/main/skills/\
ranki-seo/.windsurfrules -o .windsurfrules

<span class="c"># Claude.ai web — Projects (no file install)</span>
<span class="c"># Open claude.ai → Projects → New project</span>
<span class="c"># Custom instructions: paste the body of</span>
<span class="c"># SKILL.md (skip the YAML frontmatter).</span>
<span class="c"># Every chat in the Project auto-loads it.</span></pre>
        </div>
      </div>
    </div>
  </section>

  <section id="why">
    <div class="container">
      <div class="section-head">
        <h2>Why "advisor only" matters</h2>
        <p>Every other SEO-and-AI tool calls OpenAI or Anthropic with <em>your</em> data, then charges you their token cost plus a margin. Ranki MCP doesn't.</p>
      </div>

      <div class="why-flow">
        <svg viewBox="0 0 800 220" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ranki MCP returns structured advice — your Claude executes against your code using your own credits">
          <defs>
            <linearGradient id="dataFlow" x1="0" x2="1">
              <stop offset="0%" stop-color="rgba(247,144,108,0)"/>
              <stop offset="50%" stop-color="rgba(247,144,108,0.9)"/>
              <stop offset="100%" stop-color="rgba(247,144,108,0)"/>
            </linearGradient>
          </defs>
          <g transform="translate(40,60)">
            <rect width="180" height="100" rx="10" fill="rgba(148,210,255,.06)" stroke="rgba(148,210,255,.4)" stroke-width="1.5"/>
            <text x="90" y="38" text-anchor="middle" fill="#94d2ff" font-family="JetBrains Mono,monospace" font-size="13" font-weight="600">Your AI</text>
            <text x="90" y="58" text-anchor="middle" fill="#94d2ff" font-family="Inter,sans-serif" font-size="11" opacity=".8">Cursor · Claude · Windsurf</text>
            <text x="90" y="82" text-anchor="middle" fill="#94d2ff" font-family="JetBrains Mono,monospace" font-size="10" opacity=".6">pays in YOUR credits</text>
          </g>
          <g transform="translate(580,60)">
            <rect width="180" height="100" rx="10" fill="rgba(247,144,108,.08)" stroke="rgba(247,144,108,.5)" stroke-width="1.5"/>
            <text x="90" y="38" text-anchor="middle" fill="#f7906c" font-family="Ranki Black,Inter" font-size="14" font-weight="900">Ranki MCP</text>
            <text x="90" y="58" text-anchor="middle" fill="#f7906c" font-family="Inter,sans-serif" font-size="11" opacity=".85">mcp.ranki.io</text>
            <text x="90" y="82" text-anchor="middle" fill="#f7906c" font-family="JetBrains Mono,monospace" font-size="10" opacity=".7">advice + files only</text>
          </g>
          <line x1="225" y1="100" x2="575" y2="100" stroke="url(#dataFlow)" stroke-width="2" stroke-dasharray="6 4">
            <animate attributeName="stroke-dashoffset" from="0" to="-20" dur="1.4s" repeatCount="indefinite"/>
          </line>
          <text x="400" y="92" text-anchor="middle" fill="#f7906c" font-family="JetBrains Mono,monospace" font-size="10" font-weight="600">tools/call</text>
          <line x1="575" y1="120" x2="225" y2="120" stroke="url(#dataFlow)" stroke-width="2" stroke-dasharray="6 4">
            <animate attributeName="stroke-dashoffset" from="0" to="20" dur="1.4s" repeatCount="indefinite"/>
          </line>
          <text x="400" y="138" text-anchor="middle" fill="#9be5a6" font-family="JetBrains Mono,monospace" font-size="10" font-weight="600">structured advice</text>
          <g transform="translate(310,180)">
            <rect width="180" height="32" rx="6" fill="rgba(255,255,255,.04)" stroke="rgba(255,255,255,.2)" stroke-width="1"/>
            <text x="90" y="21" text-anchor="middle" fill="#fff" font-family="JetBrains Mono,monospace" font-size="11" opacity=".75">your codebase</text>
          </g>
          <line x1="130" y1="160" x2="310" y2="195" stroke="rgba(148,210,255,.3)" stroke-width="1.4" stroke-dasharray="3 3"/>
          <text x="190" y="178" fill="#94d2ff" font-family="JetBrains Mono,monospace" font-size="9" opacity=".55">edits</text>
        </svg>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:.5rem;max-width:880px;margin:2.5rem auto 0;padding:0 1rem">
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">No vendor lock-in.</strong>Stop using us tomorrow — your code is yours, the advice is deterministic.</p>
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">No opaque AI bills.</strong>You pay Claude or Cursor. We never run on your tokens.</p>
        <p style="padding:1.1rem 1.3rem;border-left:2px solid var(--orange);color:var(--ink-2);font-size:.92rem;line-height:1.65"><strong style="color:var(--ink);display:block;margin-bottom:.3rem">No hallucination from us.</strong>Our tools return deterministic data — your AI does the inference, against your real code.</p>
      </div>
    </div>
  </section>

  <section id="faq">
    <div class="container">
      <div class="section-head"><h2>Real questions, real answers</h2><p>What vibe-coders actually search for before installing an MCP server.</p></div>
      <div class="faq">
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">What is an MCP server, and why do I need one for SEO?</div><div class="faq-a">MCP (Model Context Protocol) is the standard <a href="https://modelcontextprotocol.io" target="_blank" rel="noopener">Anthropic introduced in late 2024</a> for connecting AI agents to external tools. Ranki MCP adds SEO + AEO auditing, sitemap and <span class="mono">llms.txt</span> generation, keyword-gap analysis, and topic discovery — so your AI can fix your vibe-coded site without you having to learn SEO terminology.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Does this work with Cursor? Windsurf? Claude.ai web? Lovable? v0? Bolt.new?</div><div class="faq-a"><strong style="color:var(--ink)">Cursor and Windsurf:</strong> yes, native HTTP MCP support — point them at <span class="mono">https://mcp.ranki.io</span> with an <span class="mono">X-API-Key</span> header.<br><br><strong style="color:var(--ink)">Claude Code, Claude Desktop, ChatGPT Desktop:</strong> yes — stdio MCP via <span class="mono">npx -y @ranki/mcp</span>.<br><br><strong style="color:var(--ink)">Claude.ai web (Pro / Team / Enterprise):</strong> yes — add as a Custom Connector under Settings → Connectors. Same URL + header.<br><br><strong style="color:var(--ink)">Lovable, v0, Bolt.new:</strong> those generate code but don't expose MCP configs in their UI yet. Workaround — install Ranki MCP in your Claude Code or Cursor session, then ask it to refactor the Lovable / v0 / Bolt output. Same result.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">How do I know my API key is set up correctly?</div><div class="faq-a">Ask your AI to call <span class="mono">get_account</span> — it's the whoami of Ranki MCP. If your key works, you get back your name, email, plan, daily and monthly limits, and current usage. If the key is wrong, missing, or revoked, you get a precise error message telling you exactly which line in the MCP config to fix.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Can I use the Skill on Claude.ai web?</div><div class="faq-a">Claude.ai web doesn't load <span class="mono">SKILL.md</span> files directly the way Claude Code does. But two workarounds: (1) open <strong>Claude.ai Projects</strong> → create a new Project → paste the body of <span class="mono">SKILL.md</span> into the custom instructions — every chat in that Project auto-loads it. (2) The Custom Connector already exposes the Skill's tool selection logic via the tool descriptions themselves, so Claude.ai picks the right tool for the right prompt without the Skill file. Less rich, but works in zero seconds.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Does Ranki MCP use my Claude credits or yours?</div><div class="faq-a"><strong style="color:var(--ink)">Yours.</strong> The MCP server returns structured advice (checklists, fix recipes, generated files). Your AI evaluates them against your code using your own credits. We never make LLM calls on your behalf — that's why the advisor tools can run free.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Was Ranki MCP built by actual SEO professionals?</div><div class="faq-a">Yes. The product is the developer surface of <a href="https://ranki.io" target="_blank" rel="noopener">Ranki.io</a>, an AI SEO + AEO automation platform built by Younes Lamnabhi (in SEO since 2009 — 17 years of on-page work + 3 years of AEO research). The audit logic encodes patterns we use daily for thousands of paying users.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">What does AEO mean? Why should I care?</div><div class="faq-a"><strong style="color:var(--ink)">Answer Engine Optimization</strong> — the structural signals (FAQPage schema, definitional intros, author bylines, <span class="mono">llms.txt</span>, comparison tables) that ChatGPT, Claude, Perplexity, and Google AI Overviews use to pick which sites to cite. In 2026, AEO is the fastest-growing search channel and most sites have zero coverage. Audit yours with <span class="mono">audit_aeo</span> in 5 seconds.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Will I need to learn how to read SEO reports?</div><div class="faq-a">No. The advisor tools return advice in a format your AI parses — checklists with pass/fail and copy-pasteable fix recipes. Your AI applies the fixes; you read the diff if you want to.</div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Do I need a Ranki.io account?</div><div class="faq-a">No, for the eight advisor tools — they work free, rate-limited to five calls per IP per UTC day. Yes, for <span class="mono">list_projects</span> and <span class="mono">get_article</span>, which need a free key to read your private Ranki.io data. <a href="https://app.ranki.io/developer">Generate a key →</a></div></div>
        <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Is the source code open?</div><div class="faq-a">Yes — MIT license. PHP server and npx shim at <a href="https://github.com/1fancy/ranki-mcp" target="_blank" rel="noopener">github.com/1fancy/ranki-mcp</a>. The companion Claude / Cursor / Windsurf Skill bundle at <a href="https://github.com/1fancy/ranki-seo-skills" target="_blank" rel="noopener">github.com/1fancy/ranki-seo-skills</a>.</div></div>
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
        <a href="#tools">10 tools</a>
        <a href="https://github.com/1fancy/ranki-mcp" target="_blank" rel="noopener">Source · MIT</a>
        <a href="https://www.npmjs.com/package/@ranki/mcp" target="_blank" rel="noopener">npm @ranki/mcp</a>
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
    term.innerHTML = '';
    lineIdx = 0;
    setTimeout(nextLine, 1800);
    return;
  }
  const [html, delay] = termLines[lineIdx++];
  term.innerHTML += (html === '' ? '<br>' : html + '<br>');
  term.scrollTop = term.scrollHeight;
  setTimeout(nextLine, delay);
}
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
