# Ranki MCP — Free SEO and AEO audit tool for Cursor, Claude Code, Windsurf and ChatGPT

> The free Model Context Protocol (MCP) server that turns Claude Code, Claude Desktop, Cursor, Windsurf and ChatGPT Desktop into a senior SEO and AEO consultant. Audits any URL, generates `sitemap.xml`, `llms.txt`, `robots.txt`, FAQPage schema and JSON-LD, proposes title and meta description candidates, flags pages that should be hidden from search engines, finds keyword gaps and explains every SEO term in plain English.

[![MCP 2024-11-05](https://img.shields.io/badge/MCP-2024--11--05-orange)](https://modelcontextprotocol.io)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![npm @ranki/mcp](https://img.shields.io/npm/v/@ranki/mcp.svg?label=%40ranki%2Fmcp)](https://www.npmjs.com/package/@ranki/mcp)
[![live mcp.ranki.io](https://img.shields.io/badge/live-mcp.ranki.io-black)](https://mcp.ranki.io)
[![Skill repo](https://img.shields.io/badge/companion-ranki--seo--skills-orange)](https://github.com/1fancy/ranki-seo-skills)

You shipped a site. It runs. Lighthouse is green. Six weeks later organic traffic is still zero and ChatGPT, Claude, Perplexity and Google AI Overviews never mention it. The reason is almost always the same: the site is missing `sitemap.xml`, `llms.txt`, FAQPage JSON-LD, the right `robots.txt` and the structural signals AI search engines look for. Ranki MCP fixes that from inside your AI editor.

## Install in one line

```bash
npx @ranki/cli install
```

The CLI auto-detects which AI editor you have installed (Claude Code, Claude Desktop, Cursor, Windsurf, ChatGPT Desktop), writes the right MCP config in the right place and downloads the companion Skill file from the [ranki-seo-skills repo](https://github.com/1fancy/ranki-seo-skills). Re-run `npx @ranki/cli update` later to refresh the Skill; `npx @ranki/cli check` verifies the setup.

Prefer the manual JSON snippet? Examples for each editor are in the [Install](#install) section below.

## What it actually does — 15 tools

The MCP server exposes 15 tools. Your AI editor calls them like any other MCP tool; they return Markdown reports your editor renders inline.

### Audit
- **`audit_seo(url)`** — 10-check on-page SEO scorecard: title length, meta description, H1 uniqueness, canonical, viewport, HTTPS, OpenGraph completeness, image alt coverage, internal link count, JSON-LD presence. Returns score 0-100 with per-failure fix recipes.
- **`audit_aeo(url)`** — 8-check Answer Engine Optimization scorecard: FAQPage / Article JSON-LD, definitional intro under 80 words, author byline, `llms.txt` presence, `robots.txt` allows GPTBot / ClaudeBot / PerplexityBot, answer-style H2/H3 headings, comparison tables.
- **`audit_hidden_pages(urls, domain)`** — classifies each path as `robots-disallow`, `noindex`, `keep` or `unsure` with reasoning. Catches admin routes, API endpoints, drafts, login pages, account dashboards, thank-you pages, build artifacts and search-result URLs. Returns a ready-to-paste `robots.txt` block.

### Generate
- **`generate_sitemap_xml(urls)`** — builds a deploy-ready `sitemap.xml` from a URL list with current `lastmod` timestamps.
- **`generate_llms_txt(site_name, summary, key_pages)`** — generates `llms.txt`, the emerging standard for telling AI crawlers what your site is and which pages to cite.
- **`generate_robots_txt(sitemap_url, allow_ai, disallow_paths)`** — builds a `robots.txt` that explicitly allows or denies GPTBot, ChatGPT-User, ClaudeBot, anthropic-ai, PerplexityBot and Google-Extended.

### Content & strategy
- **`seo_starter_kit(domain)`** — returns the four baseline files most vibe-coded sites are missing (`robots.txt`, `sitemap.xml`, `llms.txt`, JSON-LD) ready to paste into your repo.
- **`find_topic_ideas(url)`** — reads your homepage, infers your niche, and returns a structured brief for generating 15 article topics across informational, commercial and transactional intent with prioritization criteria.
- **`find_keyword_gap(url, competitors)`** — returns a step-by-step methodology for finding keywords competitors rank for but you don't. If no competitors are given, instructs your editor to ask first.
- **`propose_titles_metas(urls, focus_keyword)`** — extracts the actual title, h1 and first paragraph from each URL (or accepts a free-text description for un-deployed pages), then returns a Markdown table with 5 title and meta description candidates per page across 5 angles (descriptive, benefit-led, question-format, specific-number, keyword-first). Each candidate is flagged for length compliance.
- **`explain_seo_terms(category)`** — plain-English glossary of 40+ SEO and AEO terms: SEO, AEO, GEO, JSON-LD, FAQPage, canonical, `llms.txt`, Core Web Vitals, E-E-A-T, helpful content update, doorway pages, and more. Filter by category: basics, AEO, technical, analytics, penalty.

### Install & verify
- **`install_skill(agent)`** — returns the install commands for the [ranki-seo-skills](https://github.com/1fancy/ranki-seo-skills) Skill across Claude Code, Claude Desktop, Cursor, Windsurf, Claude.ai web Projects and generic `AGENTS.md` agents.
- **`get_account()`** — confirms your API key works and returns your account snapshot: name, email, plan, daily and monthly quota, current usage. Requires API key.

### Bridge to your Ranki.io content
- **`list_projects()`** — lists projects in your [Ranki.io](https://ranki.io) account. Requires API key.
- **`get_article(article_id)`** — fetches a single article by its `nano_id`: title, HTML, focus keywords, table of contents, embedded image URLs, SEO score. Requires API key.

## How a typical session looks

```text
You:    audit https://myapp.dev for AEO and fix it

Claude: → Calling ranki.audit_aeo(url="https://myapp.dev")
        ← Score: 38/100. Missing: FAQPage schema, llms.txt, author byline,
                          answer-style H2s, robots.txt blocks ClaudeBot.
        → Editing public/robots.txt to allow AI crawlers…
        → Calling ranki.generate_llms_txt(...) and writing public/llms.txt…
        → Editing app/layout.tsx to inject FAQPage JSON-LD…
        → Calling ranki.audit_aeo again to verify…
        ← Score: 88/100.
```

The Skill file (in [ranki-seo-skills](https://github.com/1fancy/ranki-seo-skills)) tells your editor when to call which tool, in what order, and where in your repo to apply each fix.

## Rate limits

| Tier | Daily cap | Scope | Bridge tools |
|---|---|---|---|
| Free (no key) | 5 calls | per IP | not available |
| Free account ([app.ranki.io/developer](https://app.ranki.io/developer)) | 500 calls | per API key | `list_projects`, `get_article`, `get_account` unlocked |

`X-RateLimit-Limit`, `X-RateLimit-Remaining` and `X-RateLimit-Reset` are returned on every response. The dispatcher's error messages include the reset countdown and the upgrade path.

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

Restart Claude Desktop. The MCP indicator should show **ranki** with 15 tools.

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
│  Claude / Cursor / etc │         │  mcp.ranki.io (PHP)      │
│                        │         │                          │
│  1. Sees 15 tools      │ JSON-RPC│  - 15 tool definitions   │
│  2. Decides to use one ├────────►│  - HTTP + stdio (npx)    │
│  3. Receives advice    │         │  - 5/IP or 500/key per   │
│  4. Acts on the repo   │         │    UTC day rate limit    │
│                        │         │  - REST API bridge       │
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
