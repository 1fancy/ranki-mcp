<?php
/**
 * mcp.ranki.io — dual-purpose entry point.
 *
 * GET  → dark-themed HTML landing (SEO + onboarding for vibe-coders)
 * POST → JSON-RPC 2.0 MCP dispatcher (delegates to ../index.php)
 *
 * Apache/Nginx vhost: DocumentRoot = /www/wwwroot/mcp.ranki.io/public/
 * MCP source (registry, tools, lib) sits at /www/wwwroot/mcp.ranki.io/
 * outside the public dir so curious humans can't list the tool sources.
 */
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    require __DIR__.'/../index.php';
    exit;
}

// ---- Human landing page ----
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ranki MCP — SEO + AEO advisor for Claude Code, Cursor, ChatGPT Desktop</title>
<meta name="description" content="Free MCP server that audits your site for SEO + AEO, generates sitemap.xml / llms.txt / robots.txt, and feeds Ranki.io project data into Claude. Built for vibe-coders who want their site cited by ChatGPT, Claude, Perplexity, and Google AI Overviews.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://mcp.ranki.io/">
<meta name="theme-color" content="#0a0a0a">
<meta property="og:type" content="website">
<meta property="og:title" content="Ranki MCP — SEO + AEO advisor for Claude Code, Cursor, ChatGPT">
<meta property="og:description" content="Free MCP server that audits SEO + AEO, generates sitemap/llms.txt/robots.txt, and exposes your Ranki.io content to Claude.">
<meta property="og:url" content="https://mcp.ranki.io/">
<meta property="og:site_name" content="Ranki.io">
<meta property="og:image" content="https://ranki.io/assets/images/favicon-512.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Ranki MCP — SEO + AEO advisor for Claude / Cursor">
<meta name="twitter:description" content="Free MCP server: audit SEO + AEO, generate sitemap.xml / llms.txt / robots.txt from your IDE.">
<link rel="icon" href="https://ranki.io/assets/images/favicon-32.png" type="image/png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Ranki MCP",
  "url": "https://mcp.ranki.io/",
  "applicationCategory": "DeveloperApplication",
  "operatingSystem": "Web",
  "description": "Free MCP server that audits SEO + AEO and generates sitemap.xml, llms.txt, robots.txt for Claude Code, Cursor, ChatGPT Desktop.",
  "offers": {"@type":"Offer","price":"0","priceCurrency":"USD"}
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type":"Question","name":"What is the Ranki MCP server?","acceptedAnswer":{"@type":"Answer","text":"A Model Context Protocol server that gives Claude Code, Cursor, and ChatGPT Desktop the ability to audit your site for SEO and AEO, and to generate sitemap.xml, llms.txt, and robots.txt files inline as you code."}},
    {"@type":"Question","name":"Does it use my Claude credits or yours?","acceptedAnswer":{"@type":"Answer","text":"Yours. Ranki MCP returns advice + structure (checklists, generated files, fix recipes). Your Claude/Cursor evaluates them against your codebase using your own credits."}},
    {"@type":"Question","name":"Do I need a Ranki.io account?","acceptedAnswer":{"@type":"Answer","text":"No — the advisor tools (audit_aeo, audit_seo, generate_sitemap, generate_llms_txt, generate_robots_txt) work free, rate-limited to 5 calls per IP per day. Two tools (list_projects, get_article) require a free Ranki.io API key for unlimited use."}}
  ]
}
</script>
<style>
:root{
  --bg:#0a0a0a;
  --bg-2:#111;
  --bg-3:#181818;
  --ink:#f5f5f5;
  --ink-2:#bdbdbd;
  --ink-3:#888;
  --orange:#f7906c;
  --orange-2:#ff7a3d;
  --orange-glow:rgba(247,144,108,.15);
  --line:#222;
  --line-2:#2a2a2a;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{background:var(--bg);color:var(--ink);font-family:"Inter",system-ui,sans-serif;line-height:1.6;-webkit-font-smoothing:antialiased}
::selection{background:var(--orange);color:#000}
a{color:var(--orange);text-decoration:none}
a:hover{color:var(--orange-2)}
.container{max-width:1140px;margin:0 auto;padding:0 1.5rem}
.muted{color:var(--ink-2)}
.mono{font-family:"JetBrains Mono",monospace;font-size:.92em}

/* Header */
header{position:sticky;top:0;background:rgba(10,10,10,.85);backdrop-filter:blur(10px);border-bottom:1px solid var(--line);z-index:50}
.hdr{display:flex;align-items:center;justify-content:space-between;padding:1rem 0}
.logo{font-weight:800;font-size:1.15rem;letter-spacing:-.02em;color:var(--ink);display:flex;align-items:center;gap:.5rem}
.logo .dot{width:8px;height:8px;background:var(--orange);border-radius:50%;box-shadow:0 0 16px var(--orange-glow)}
.nav{display:flex;gap:1.8rem;align-items:center}
.nav a{color:var(--ink-2);font-size:.95rem;font-weight:500}
.nav a:hover{color:var(--ink)}
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.7rem 1.2rem;border-radius:8px;font-weight:600;font-size:.95rem;border:1px solid transparent;transition:all .15s}
.btn-primary{background:var(--orange);color:#000}
.btn-primary:hover{background:var(--orange-2);color:#000}
.btn-ghost{border-color:var(--line-2);color:var(--ink-2)}
.btn-ghost:hover{border-color:var(--orange);color:var(--ink)}

/* Hero */
.hero{padding:5rem 0 4rem;text-align:center;background:radial-gradient(ellipse 800px 400px at 50% 0%,var(--orange-glow),transparent)}
.eyebrow{display:inline-block;padding:.35rem .9rem;background:var(--bg-3);border:1px solid var(--line-2);border-radius:99px;color:var(--orange);font-size:.82rem;font-weight:600;letter-spacing:.02em;margin-bottom:1.5rem}
h1{font-size:clamp(2.2rem,5vw,3.6rem);font-weight:800;line-height:1.1;letter-spacing:-.03em;margin-bottom:1.25rem}
h1 .grad{background:linear-gradient(135deg,var(--orange) 0%,#fff 100%);-webkit-background-clip:text;background-clip:text;color:transparent}
.lede{font-size:1.18rem;color:var(--ink-2);max-width:720px;margin:0 auto 2.2rem}
.cta-row{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap}

/* Code blocks */
pre.code{background:#0d0d0d;border:1px solid var(--line-2);border-left:3px solid var(--orange);border-radius:10px;padding:1.1rem 1.3rem;font-family:"JetBrains Mono",monospace;font-size:.88rem;line-height:1.65;color:#d4d4d4;overflow-x:auto;text-align:left;position:relative}
pre.code .k{color:#c586c0}  /* keyword */
pre.code .s{color:#ce9178}  /* string */
pre.code .c{color:#6a9955}  /* comment */
pre.code .v{color:#9cdcfe}  /* variable/property */
pre.code .n{color:#b5cea8}  /* number */
.copy-btn{position:absolute;top:.6rem;right:.6rem;background:var(--bg-3);border:1px solid var(--line-2);color:var(--ink-3);padding:.3rem .65rem;border-radius:6px;font-size:.75rem;cursor:pointer;font-family:inherit}
.copy-btn:hover{color:var(--orange);border-color:var(--orange)}

/* Sections */
section{padding:4rem 0;border-top:1px solid var(--line)}
.section-head{text-align:center;margin-bottom:2.5rem}
.section-head h2{font-size:2rem;font-weight:700;letter-spacing:-.02em;margin-bottom:.7rem}
.section-head p{color:var(--ink-2);max-width:640px;margin:0 auto}

/* Tool grid */
.tool-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.2rem}
.tool-card{background:var(--bg-2);border:1px solid var(--line);border-radius:12px;padding:1.5rem;transition:all .15s}
.tool-card:hover{border-color:var(--orange);transform:translateY(-2px)}
.tool-card h3{display:flex;align-items:center;gap:.6rem;font-size:1.05rem;font-weight:700;margin-bottom:.7rem;font-family:"JetBrains Mono",monospace;color:var(--orange)}
.tool-card .tag{font-size:.7rem;background:var(--bg-3);color:var(--ink-3);padding:.18rem .55rem;border-radius:4px;text-transform:uppercase;letter-spacing:.05em;font-family:"Inter",sans-serif;font-weight:600}
.tool-card .tag.key{color:var(--orange);background:rgba(247,144,108,.1)}
.tool-card p{color:var(--ink-2);font-size:.92rem;line-height:1.6}

/* Install steps */
.install-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem}
@media (max-width:768px){.install-grid{grid-template-columns:1fr}}
.install-card{background:var(--bg-2);border:1px solid var(--line);border-radius:12px;overflow:hidden}
.install-card header{position:static;background:var(--bg-3);border:none;border-bottom:1px solid var(--line);padding:1rem 1.3rem;backdrop-filter:none}
.install-card header h3{font-size:.95rem;font-weight:600;color:var(--ink);display:flex;align-items:center;gap:.6rem}
.install-card .body{padding:1.3rem}

/* FAQ */
.faq{max-width:780px;margin:0 auto}
.faq-item{border-bottom:1px solid var(--line);padding:1.2rem 0}
.faq-q{font-weight:600;color:var(--ink);font-size:1.02rem;display:flex;justify-content:space-between;cursor:pointer}
.faq-a{color:var(--ink-2);margin-top:.8rem;font-size:.95rem;display:none}
.faq-item.open .faq-a{display:block}
.faq-item.open .faq-q::after{content:"−";color:var(--orange)}
.faq-q::after{content:"+";color:var(--orange);font-size:1.3rem;font-weight:400;line-height:1}

/* Footer */
footer{background:var(--bg-2);border-top:1px solid var(--line);padding:3rem 0 2rem;margin-top:2rem}
.foot{display:flex;flex-wrap:wrap;gap:2rem;justify-content:space-between;align-items:start}
.foot p{color:var(--ink-3);font-size:.88rem}
.foot a{color:var(--ink-2);font-size:.88rem;display:block;margin-bottom:.4rem}
</style>
</head>
<body>

<header>
  <div class="container hdr">
    <a href="/" class="logo"><span class="dot"></span> Ranki MCP</a>
    <nav class="nav">
      <a href="#tools">Tools</a>
      <a href="#install">Install</a>
      <a href="#faq">FAQ</a>
      <a href="https://ranki.io/developers" class="btn btn-ghost">Docs</a>
      <a href="https://app.ranki.io/developer" class="btn btn-primary">Get API key →</a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="container">
    <span class="eyebrow">Free · Open · MCP 2024-11-05</span>
    <h1>The <span class="grad">SEO + AEO advisor</span><br>for Claude Code & Cursor</h1>
    <p class="lede">Audit your vibe-coded site, generate sitemap.xml / llms.txt / robots.txt, and get cited by ChatGPT, Claude, Perplexity, and Google AI Overviews — all from your IDE. Uses <strong>your</strong> Claude credits, returns structure + advice.</p>
    <div class="cta-row">
      <a href="#install" class="btn btn-primary">Install in 30 seconds →</a>
      <a href="#tools" class="btn btn-ghost">See the 7 tools</a>
    </div>
  </div>
</section>

<section id="tools">
  <div class="container">
    <div class="section-head">
      <h2>7 tools, advisor-only</h2>
      <p>The MCP server returns checklists, generated files, and fix recipes. <strong>Your</strong> Claude evaluates them against your code — Ranki MCP never spends your AI credits.</p>
    </div>
    <div class="tool-grid">
      <div class="tool-card"><h3>audit_aeo <span class="tag">Free</span></h3><p>Checks FAQPage / Article schema, definitional intro, author byline, llms.txt presence, robots.txt AI-bot allowance, answer-style headings. Returns 8 checks + fix recipe per failure.</p></div>
      <div class="tool-card"><h3>audit_seo <span class="tag">Free</span></h3><p>Title/description length, H1 uniqueness, canonical, viewport, HTTPS, OpenGraph, image alt coverage, internal-link count, JSON-LD presence. 10 checks scored 0–100.</p></div>
      <div class="tool-card"><h3>generate_sitemap_xml <span class="tag">Free</span></h3><p>Pass URL list, get back a ready-to-deploy <span class="mono">sitemap.xml</span> with current lastmod. Submit to Google Search Console immediately.</p></div>
      <div class="tool-card"><h3>generate_llms_txt <span class="tag">Free</span></h3><p>Generate the emerging-standard <span class="mono">llms.txt</span> that tells LLMs what your site is, your key pages, and your crawl preferences.</p></div>
      <div class="tool-card"><h3>generate_robots_txt <span class="tag">Free</span></h3><p>Build a <span class="mono">robots.txt</span> that explicitly allows (or blocks) GPTBot, ClaudeBot, PerplexityBot, Google-Extended. Default: allow — you want AI citation traffic.</p></div>
      <div class="tool-card"><h3>list_projects <span class="tag key">Key required</span></h3><p>Pull the list of projects from your Ranki.io account into Claude. Lets you reason about your own content while you build.</p></div>
      <div class="tool-card"><h3>get_article <span class="tag key">Key required</span></h3><p>Fetch a single Ranki.io article by <span class="mono">nano_id</span> — title, HTML, focus keywords, TOC, image URLs, SEO score.</p></div>
    </div>
  </div>
</section>

<section id="install">
  <div class="container">
    <div class="section-head">
      <h2>Install in 30 seconds</h2>
      <p>Paste this into your client config. The advisor tools work without a key (5 calls/day per IP); add your Ranki.io key for unlimited use + the bridge tools.</p>
    </div>
    <div class="install-grid">
      <div class="install-card">
        <header><h3>Claude Desktop / Claude Code</h3></header>
        <div class="body">
          <p class="muted" style="margin-bottom:.7rem;font-size:.9rem;"><span class="mono">~/.claude/claude_desktop_config.json</span></p>
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
      <div class="install-card">
        <header><h3>Cursor</h3></header>
        <div class="body">
          <p class="muted" style="margin-bottom:.7rem;font-size:.9rem;"><span class="mono">.cursor/mcp.json</span> in your project</p>
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
    <p style="text-align:center;margin-top:2rem;color:var(--ink-3);font-size:.9rem;">No key yet? <a href="https://app.ranki.io/developer">Generate one free</a>.</p>
  </div>
</section>

<section id="faq">
  <div class="container">
    <div class="section-head"><h2>FAQ</h2></div>
    <div class="faq">
      <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Does Ranki MCP use my Claude credits or yours?</div><div class="faq-a"><strong>Yours.</strong> Ranki MCP returns advice + structure (checklists, fix recipes, generated files). Your Claude evaluates them against your code using your own credits. We never make LLM calls on your behalf.</div></div>
      <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">What does "AEO" mean?</div><div class="faq-a">Answer Engine Optimization — the structural signals (FAQPage schema, definitional intros, author bylines, llms.txt, table data) that ChatGPT, Claude, Perplexity, and Google AI Overviews use to pick which sites to cite in their answers. AEO is to AI search what SEO is to Google search.</div></div>
      <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Do I need a Ranki.io account?</div><div class="faq-a">No for advisor tools — they work free, rate-limited to 5 calls per IP per UTC day. Yes for <span class="mono">list_projects</span> and <span class="mono">get_article</span> (they read your private Ranki.io data).</div></div>
      <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">What's the difference between mcp.ranki.io and the REST API?</div><div class="faq-a">The REST API (<a href="https://app.ranki.io/api/v1/me">app.ranki.io/api/v1</a>) is for programmatic access to your Ranki.io data. MCP is the protocol layer Claude/Cursor speak — it wraps the REST API + adds the advisor tools.</div></div>
      <div class="faq-item"><div class="faq-q" onclick="this.parentNode.classList.toggle('open')">Is it open source?</div><div class="faq-a">The npx shim (<span class="mono">@ranki/mcp</span>) and tool definitions are. Server-side advisor logic is hosted at mcp.ranki.io.</div></div>
    </div>
  </div>
</section>

<footer>
  <div class="container foot">
    <div>
      <a href="/" class="logo" style="margin-bottom:.8rem"><span class="dot"></span> Ranki MCP</a>
      <p>Part of <a href="https://ranki.io">Ranki.io</a> — the AI SEO + AEO automation platform.</p>
    </div>
    <div>
      <a href="https://ranki.io/developers">Developer docs</a>
      <a href="https://app.ranki.io/developer">Get API key</a>
      <a href="https://github.com/ranki-io/mcp">GitHub (npx shim)</a>
      <a href="https://ranki.io/privacy">Privacy</a>
    </div>
  </div>
</footer>

<script>
function copyCode(btn){
  const code = btn.parentElement.innerText.replace(/^Copy\n/, '').trim();
  navigator.clipboard.writeText(code).then(() => {
    const original = btn.textContent;
    btn.textContent = 'Copied!';
    btn.style.color = 'var(--orange)';
    setTimeout(() => { btn.textContent = original; btn.style.color = ''; }, 1500);
  });
}
</script>

</body>
</html>
