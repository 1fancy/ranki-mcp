# Ranki MCP — the SEO + AEO advisor for vibe-coders

> **Free MCP server that audits your site for SEO + Answer Engine Optimization, generates `sitemap.xml` / `llms.txt` / `robots.txt`, finds keyword gaps, and tells your Claude / Cursor / ChatGPT Desktop exactly what to fix — all using your own AI credits.**

[![MCP 2024-11-05](https://img.shields.io/badge/MCP-2024--11--05-orange)](https://modelcontextprotocol.io)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![npm @ranki/mcp](https://img.shields.io/npm/v/@ranki/mcp.svg?label=%40ranki%2Fmcp)](https://www.npmjs.com/package/@ranki/mcp)
[![mcp.ranki.io](https://img.shields.io/badge/live-mcp.ranki.io-black)](https://mcp.ranki.io)

This is the open-source repository for the **Ranki.io Model Context Protocol server**. It plugs into Claude Code, Claude Desktop, Cursor, and any other MCP-capable client, adding 10 tools that diagnose SEO and AEO problems on any site and tell your AI exactly how to fix them — without spending a single token from us.

🚀 **Try it in 30 seconds:** add the snippet from [Install](#install) to your Claude config, then ask Claude *"audit my site for AEO and fix it"*.

---

## Table of contents

- [Why Ranki MCP exists](#why-ranki-mcp-exists)
- [What it does that nothing else does](#what-it-does-that-nothing-else-does)
- [The 10 MCP tools](#the-10-mcp-tools)
- [Install](#install)
- [How vibe-coders use it](#how-vibe-coders-use-it)
- [Architecture](#architecture)
- [Why "advisor only" matters](#why-advisor-only-matters)
- [SEO vs AEO — what's the difference?](#seo-vs-aeo--whats-the-difference)
- [llms.txt — the emerging AI-search standard](#llmstxt--the-emerging-ai-search-standard)
- [Self-hosting](#self-hosting)
- [Contributing](#contributing)
- [FAQ](#faq)
- [License](#license)

---

## Why Ranki MCP exists

In 2026, two things are true:

1. **You can vibe-code an entire SaaS in a weekend** with Claude Code or Cursor.
2. **Nobody you shipped that SaaS to will ever find it** because you have zero SEO, no AEO structure, no `llms.txt`, no `sitemap.xml`, and Google + ChatGPT can't tell what your site is about.

Most SEO tools are made for SEO professionals — they assume you already know what a focus keyword is, what schema markup means, why FAQPage JSON-LD matters for citation in AI Overviews, and what an "answer-style heading" is.

**Ranki MCP flips that.** It assumes you know how to vibe-code but don't know SEO. So instead of giving you a 50-page audit, it gives your AI (Claude, Cursor, whoever you vibe-code with) a structured playbook — a brief that says "here's what to fix, here's the file content to write, here's how to verify it worked." Your AI does the actual work, in your codebase, using your credits.

The result: a vibe-coded site goes from invisible to indexed-and-cited in the same session you finished shipping it.

---

## What it does that nothing else does

| | Traditional SEO tools | LLM-based SEO chatbots | **Ranki MCP** |
|---|---|---|---|
| **Designed for** | SEO professionals | General-purpose chat | Developers vibe-coding |
| **Where it runs** | Web dashboard | Their UI | Your IDE / Claude |
| **Who pays for the AI** | n/a (no AI) | The tool's vendor | **You** (your Claude credits) |
| **Output** | Reports + recommendations | Long explanations | **Structured playbooks** your AI executes |
| **Acts on your code** | No | No | **Yes** (via your AI agent) |
| **Cost** | $99-$799/mo | $20-$200/mo | **Free** (5 advisor calls/IP/day, unlimited with [free Ranki.io account](https://app.ranki.io/developer)) |

---

## The 10 MCP tools

### Discovery (for "I don't know where to start")

#### `seo_starter_kit(domain)`
You shipped a site. What SEO files do you need? This tool returns the exact contents of `robots.txt`, `sitemap.xml`, `llms.txt`, and JSON-LD structured data — plus a deployment checklist. Your AI applies them to your repo.

#### `find_topic_ideas(url)`
You have a site but don't know what to write about. This tool sniffs your homepage's niche, then returns a structured brief telling your AI how to generate 15 article topics across informational / commercial / transactional intent, with prioritization criteria.

#### `find_keyword_gap(url, competitors[])`
You think competitors are stealing keywords from you but don't know which. This tool returns the methodology for keyword-gap analysis — your AI walks the user through it. Pass competitor URLs to get specific guidance, or omit them to have your AI ask the user first.

### Auditing (for "what's broken on this page?")

#### `audit_aeo(url)`
Scorecard for **Answer Engine Optimization** — the signals ChatGPT, Claude, Perplexity, and Google AI Overviews use to pick which sites to cite. 8 checks: FAQPage / Article JSON-LD, definitional intro (≤80 words, "X is" pattern), author byline, `llms.txt` presence, `robots.txt` allowing GPTBot/ClaudeBot/PerplexityBot, answer-style H2/H3 headings, comparison tables. Each failed check includes a copy-pasteable fix recipe.

#### `audit_seo(url)`
On-page **SEO scorecard** — 10 checks scored 0-100. Title length (30-70 chars), meta description (70-165), H1 uniqueness, canonical, viewport meta, HTTPS, OpenGraph completeness, image alt coverage (≥90%), internal links (≥3), JSON-LD presence.

### File generation (for "give me the file to deploy")

#### `generate_sitemap_xml(urls[], changefreq)`
Build a ready-to-deploy `sitemap.xml` from a URL list. Validates URLs, sets current `lastmod`, applies a sensible default `changefreq`. Submit to Google Search Console immediately.

#### `generate_llms_txt(site_name, summary, key_pages[])`
Generate the emerging `llms.txt` standard — the way you tell ChatGPT / Claude / Perplexity what your site is about, what your key pages are, and how to cite you.

#### `generate_robots_txt(sitemap_url, allow_ai, disallow_paths[])`
Build a `robots.txt` that explicitly allows or blocks AI crawlers (`GPTBot`, `ChatGPT-User`, `ClaudeBot`, `anthropic-ai`, `PerplexityBot`, `Google-Extended`). Default: allow — most sites want AI citation traffic.

### Bridge (your Ranki.io content into your IDE)

#### `list_projects()` — requires API key
List the projects in your [Ranki.io](https://ranki.io) account. Pull your own content into Claude / Cursor while you work.

#### `get_article(article_id)` — requires API key
Fetch a single Ranki.io article — title, HTML, focus keywords, TOC, image URLs, SEO score. Use it for syndication, repurposing, reference.

---

## Install

### Claude Desktop / Claude Code (recommended for most vibe-coders)

Add to `~/.claude/claude_desktop_config.json`:

```json
{
  "mcpServers": {
    "ranki": {
      "command": "npx",
      "args": ["-y", "@ranki/mcp"],
      "env": { "RANKI_API_KEY": "rk_live_..." }
    }
  }
}
```

Restart Claude Desktop. The MCP indicator should show **ranki** with 10 tools.

### Cursor (HTTP transport, no npx needed)

`.cursor/mcp.json` in your project:

```json
{
  "mcpServers": {
    "ranki": {
      "url": "https://mcp.ranki.io",
      "headers": { "X-API-Key": "rk_live_..." }
    }
  }
}
```

### ChatGPT Desktop, Continue.dev, Zed, anything MCP-capable

If the client supports stdio MCP, use the Claude Desktop snippet. If it supports HTTP MCP, use the Cursor snippet.

### Get your free API key

Visit [app.ranki.io/developer](https://app.ranki.io/developer) and click **Reveal**. Your key already exists — every Ranki.io account gets one auto-created. Free advisor tools work without a key (5 calls/IP/day); the `list_projects` and `get_article` bridge tools require one.

---

## How vibe-coders use it

### Scenario 1: "I just shipped a site, what do I do?"

```
You: I just shipped my Next.js app at https://myapp.com. What SEO do I need?

Claude (via Ranki MCP):
  → Calling seo_starter_kit(domain="myapp.com")
  ← [Returns robots.txt + sitemap.xml + llms.txt + JSON-LD templates]
  → Writing public/robots.txt
  → Writing public/sitemap.xml (enumerating pages from app/ directory…)
  → Writing public/llms.txt
  → Adding JSON-LD <script> to app/layout.tsx
  → Calling audit_seo to verify…
  ← Score: 92/100. 🎉
```

### Scenario 2: "Why isn't ChatGPT citing my docs?"

```
You: Why doesn't ChatGPT cite my docs site?

Claude (via Ranki MCP):
  → Calling audit_aeo(url="https://docs.myproduct.com")
  ← Score 38/100. Failing: no FAQPage schema, no llms.txt, robots.txt blocks ClaudeBot.
  → Fixing each failure in your repo…
  ← Score 88/100. Re-deploy and ChatGPT should pick it up within 7-14 days.
```

### Scenario 3: "I don't know what blog posts to write"

```
You: I have a Stripe alternative landing page but the blog is empty. Help.

Claude (via Ranki MCP):
  → Calling find_topic_ideas(url="https://mystripe-alt.com")
  ← [Returns brief with topic generation methodology + 15-topic structure]
  → [Generates 15 topics organized by intent, picks top 3]
  ← Recommended first 3 articles:
     1. "How to switch payment processors without losing customers" (transactional)
     2. "Stripe vs us: side-by-side fee comparison for $10K/mo MRR" (commercial)
     3. "What is interchange-plus pricing and why most SaaSes overpay" (informational)
```

### Scenario 4: "What gap keywords am I missing?"

```
You: My competitors are stripe.com and lemonsqueezy.com. What am I missing?

Claude (via Ranki MCP):
  → Calling find_keyword_gap(url="https://mystripe-alt.com",
                              competitors=["stripe.com","lemonsqueezy.com"])
  ← [Returns methodology + per-competitor analysis steps]
  → Crawling /blog on both competitors…
  → Cross-referencing against your sitemap…
  ← 5 high-value gaps found:
     - "PCI compliance for small SaaS" (covered by Stripe, not you)
     - "How to handle subscription dunning" (covered by both, not you)
     - … 3 more
```

---

## Architecture

```
┌────────────────────────┐         ┌──────────────────────────┐
│  Your Claude / Cursor  │         │  mcp.ranki.io (PHP)      │
│                        │         │                          │
│  1. Sees 10 tools      │ JSON-RPC│  - 10 tool definitions   │
│  2. Decides to use one ├────────►│  - HTTP/SSE transport    │
│  3. Receives advice    │         │  - 5 free/IP/day rate    │
│  4. Acts on your code  │         │  - REST API bridge       │
│  5. Pays in YOUR creds │         │                          │
└────────────────────────┘         └────────────┬─────────────┘
                                                │ (only for keyed tools)
                                                ▼
                                   ┌──────────────────────────┐
                                   │  app.ranki.io REST API   │
                                   │  /api/v1/projects        │
                                   │  /api/v1/articles/...    │
                                   └──────────────────────────┘
```

### Two transports

- **stdio** (Claude Desktop, Claude Code, most MCP clients) — install the [`@ranki/mcp`](npx/) npm package, which is a 50-line Node.js shim that proxies stdio JSON-RPC to `https://mcp.ranki.io`.
- **HTTP** (Cursor, custom clients) — point at `https://mcp.ranki.io` directly. No Node install needed.

### Repository layout

```
ranki-mcp/
├── server/                     # PHP MCP server (deployed to mcp.ranki.io)
│   ├── public/index.php        #   GET → marketing landing page (HTML)
│   ├── index.php               #   POST → JSON-RPC 2.0 dispatcher
│   ├── lib/
│   │   ├── jsonrpc.php         #   JSON-RPC reply helpers
│   │   ├── registry.php        #   Tool registry + REST API bridge
│   │   └── ratelimit.php       #   Per-IP rate limit (5/day for free tier)
│   └── tools/
│       ├── seo_starter_kit.php
│       ├── find_topic_ideas.php
│       ├── find_keyword_gap.php
│       ├── audit_aeo.php
│       ├── audit_seo.php
│       ├── generate_sitemap_xml.php
│       ├── generate_llms_txt.php
│       ├── generate_robots_txt.php
│       ├── list_projects.php
│       └── get_article.php
└── npx/                        # Node.js stdio shim (published as @ranki/mcp)
    ├── package.json
    ├── index.js                #   ~50 lines: stdin→POST→stdout
    └── README.md
```

---

## Why "advisor only" matters

Every other SEO + AI tool out there has the same business model:
1. You pay them.
2. They call OpenAI / Anthropic with your data.
3. They charge you their AI cost + a margin.
4. You get a black-box answer.

Ranki MCP doesn't do this. It returns:
- **Structured checklists** (parseable, not prose).
- **Generated file content** (the exact `robots.txt` to deploy, not "you should write a `robots.txt`").
- **Methodologies** (step-by-step playbooks your AI executes, not just "do keyword research").

Your Claude / Cursor evaluates the advice against your codebase, decides what to apply, and edits the files. You pay for the AI tokens your AI uses. Ranki MCP itself never spends a single LLM token — that's why we can run the advisor tools for free.

This also means:
- **No vendor lock-in.** You can stop using Ranki MCP tomorrow; your code is yours.
- **No surprise bills.** Your AI cost is whatever Claude/Cursor charges, not opaque markups.
- **No "AI hallucination" worry from us.** We return deterministic data; your AI is the one doing the inference.

---

## SEO vs AEO — what's the difference?

**SEO (Search Engine Optimization)** is making your site rank in the classic 10 blue links on Google. The signals: title tags, meta descriptions, H1, canonicals, sitemap, internal links, page speed, mobile-friendly, HTTPS. Tools like Ahrefs / SEMrush / SurferSEO score these.

**AEO (Answer Engine Optimization)** is making your site **get cited** when ChatGPT, Claude, Perplexity, or Google AI Overviews answer a user's question. The signals are different:
- **FAQPage JSON-LD** — single biggest citation signal.
- **Definitional intros** — first paragraph is a concise "X is …" answer.
- **Author byline + E-E-A-T** — LLMs prefer cited sources with named authors.
- **`llms.txt`** — explicit invitation for LLMs to use your content.
- **`robots.txt` allowing AI bots** — GPTBot / ClaudeBot / PerplexityBot must NOT be blocked.
- **Answer-style headings** — H2/H3 phrased as questions ("What is X?", "How does X work?").
- **Comparison tables** — the highest-citation HTML element in AI Overviews.

`audit_aeo` checks all 8 of these and tells your AI exactly what to fix. As of 2026, **AEO traffic is the fastest-growing SEO channel** and most sites have zero coverage.

---

## llms.txt — the emerging AI-search standard

Inspired by `robots.txt` but for LLMs. A Markdown file at `/llms.txt` tells AI crawlers:
- What your site is about (in plain English, not metadata).
- Which pages are most important.
- How to cite you.

```markdown
# Acme Corp

> Acme makes the SDK for shipping React Native apps faster.

## Key pages

- [Homepage](https://acme.dev/)
- [Documentation](https://acme.dev/docs)
- [Pricing](https://acme.dev/pricing)
- [Blog](https://acme.dev/blog)

## About

- Founded 2024, based in Berlin.
- Used by 12,000+ teams including Linear and Notion.
- Open source SDK on github.com/acme/sdk.
```

Use `generate_llms_txt` to create one in 5 seconds.

---

## Self-hosting

The MCP server is plain PHP 8.4 — no framework, no database, no Composer dependencies. Drop the `server/` directory behind an Nginx vhost serving `public/index.php` and you're done.

```nginx
server {
  server_name mcp.yourdomain.com;
  root /var/www/ranki-mcp/server/public;
  index index.php;
  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }
  location ~ \.php$ {
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
  }
}
```

The `lib/ratelimit.php` uses files in `/tmp/` for IP rate-limiting — works out of the box. For Redis-backed rate limiting at scale, swap the implementation.

---

## Contributing

PRs welcome for new advisor tools. To add a tool:

1. Create `server/tools/your_tool.php` returning a callable `function (array $args, string $apiKey): array`.
2. Return `rk_mcp_text_content("...your structured advice...")`.
3. Register the tool in `server/lib/registry.php` under `rk_mcp_tool_definitions()`.

Tool naming: `<verb>_<noun>` snake_case (e.g. `audit_aeo`, `find_topic_ideas`).

Tool philosophy: **return data + instructions for the calling AI**, never call an LLM yourself.

---

## FAQ

### Does this cost money?
The advisor tools (everything except `list_projects` / `get_article`) are **free** — 5 calls per IP per UTC day. To remove that limit, get a free API key at [app.ranki.io/developer](https://app.ranki.io/developer). The bridge tools require a key because they pull your private Ranki.io data.

### Does Ranki MCP use my Claude credits?
**Yes — and only yours.** We never make LLM calls. The MCP server returns structured advice; your Claude / Cursor evaluates and acts on it using your own credits.

### Where does data flow?
- The advisor tools (`audit_*`, `generate_*`, `seo_starter_kit`, `find_*`) fetch the URL you pass (no other network calls).
- The bridge tools (`list_projects`, `get_article`) call `app.ranki.io/api/v1/...` over HTTPS with your `X-API-Key`.
- We don't log request bodies. We log IP + tool name + response status for rate-limiting + debugging.

### Is it open source?
Yes — MIT license, full source in this repo.

### Can I run it inside my company's VPC?
Yes — `server/` is plain PHP, no external service dependencies except `app.ranki.io` for the bridge tools (which you can disable by removing those tool files).

### How is this different from competitors like Surfer / Frase / Outrank?
Those are SaaS dashboards that audit one URL at a time and recommend changes. Ranki MCP is a **protocol layer** that lets your AI use those audits inline while it's writing code in your IDE. Different shape, different price point (free), different audience (vibe-coders, not SEO professionals).

### I'm a vibe-coder and I have no idea what AEO means.
That's literally who this is for. Start with `seo_starter_kit("yourdomain.com")` — your Claude will walk you through everything.

### Will you train AI on my data?
We don't train models. We don't have models. We're a thin advisor over deterministic checks.

---

## License

MIT. See [LICENSE](LICENSE).

Built with care by [Ranki.io](https://ranki.io) — AI SEO + AEO automation for founders, agencies, and creators.
