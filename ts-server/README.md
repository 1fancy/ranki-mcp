# @ranki.io/mcp-ts — Ranki MCP, Node/TypeScript edition

[![npm](https://img.shields.io/npm/v/@ranki.io/mcp-ts.svg)](https://www.npmjs.com/package/@ranki.io/mcp-ts)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](../LICENSE)
[![MCP 2024-11-05](https://img.shields.io/badge/MCP-2024--11--05-orange)](https://modelcontextprotocol.io)

Free SEO + AEO + Core Web Vitals + image-optimization tools for Claude Code, Cursor, Windsurf, ChatGPT Desktop and any MCP-capable agent — exposed as **22 MCP tools** over either stdio (for local subprocess clients) or HTTP (for self-hosted setups).

This is the **Node/TypeScript reference implementation** of [Ranki MCP](https://github.com/1fancy/seo-aeo-audit-mcp-ranki). The PHP server at [`server/`](../server) is the production deployment that powers [mcp.ranki.io](https://mcp.ranki.io); the TS version is the native-Node alternative for developers who prefer JavaScript tooling.

> **Both implementations expose the same 22 tools, with identical descriptions, the same JSON output, the same SSRF guard, the same rate limits, the same security posture.** Use whichever fits your stack.

## What it does

The MCP returns checklists, deploy-ready files, fix recipes, image-conversion commands and the responsive HTML to paste in. Your agent reads the prescription, writes the changes directly into your repo, runs the conversion commands, and commits — then re-runs the audit to prove the score moved.

Lighthouse 42 → 96 in one conversation. AEO 38 → 96 in the same session. No 40-page audit PDFs.

## Install

### One-line install (recommended)

```bash
npx @ranki.io/cli install
```

This installs both the MCP and the [companion Skill](https://github.com/1fancy/ranki-seo-skills) into whichever editor you have (Claude Code / Claude Desktop / Cursor / Windsurf / ChatGPT Desktop) and chooses the right MCP backend automatically.

### Manual install — stdio

For Claude Desktop, Claude Code or ChatGPT Desktop. Add this to your client's MCP config:

```json
{
  "mcpServers": {
    "ranki": {
      "command": "npx",
      "args": ["-y", "@ranki.io/mcp-ts"],
      "env": {
        "RANKI_API_KEY": "rk_live_..."
      }
    }
  }
}
```

The `RANKI_API_KEY` env var is **optional** — only the 7 paid bridge tools need it. The 15 free tools work without an account.

### Manual install — HTTP server

For Cursor, Windsurf, Claude.ai web, or any client that talks to MCP over HTTP:

```bash
npx @ranki.io/mcp-ts --serve --port 8787
# point your client at http://127.0.0.1:8787 with X-API-Key header
```

The HTTP server binds to `127.0.0.1` by default. To expose it on a LAN or VPS, set `RANKI_MCP_HOST=0.0.0.0` — but read the security section below first.

## The 22 tools

### Free (15) — no account required

| Tool | What it does |
|---|---|
| `audit_seo` | 10-check on-page SEO scorecard (title, meta, H1, canonical, viewport, HTTPS, OpenGraph, image alt, internal links, JSON-LD) |
| `audit_aeo` | 8-check Answer Engine Optimization scorecard (FAQPage / Article schema, definitional intro, author byline, llms.txt, robots AI access, answer-style headings, structured tables) |
| `audit_hidden_pages` | Classifies every URL as `robots-disallow` / `noindex` / `keep` / `unsure` with reasoning |
| `audit_speed` | Real Lighthouse + Core Web Vitals via Google PageSpeed Insights, plus image opportunities with bytes-saved per file |
| `audit_core_web_vitals` | One paragraph per metric (LCP / CLS / INP) with literal fix recipes |
| `optimize_images` | Target format (AVIF + WebP), responsive widths, alt suggestion, `sharp-cli`/`cwebp` command, `<picture>` block with `srcset` |
| `generate_sitemap_xml` | Deploy-ready sitemap.xml |
| `generate_llms_txt` | llms.txt with site summary + key pages |
| `generate_robots_txt` | robots.txt that allows or denies AI crawlers (GPTBot, ClaudeBot, PerplexityBot, Google-Extended, etc.) |
| `seo_starter_kit` | The four baseline files most vibe-coded sites are missing |
| `find_topic_ideas` | Structured brief for 15 article topics across informational / commercial / transactional intent |
| `find_keyword_gap` | Methodology for keyword-gap analysis against named competitors |
| `propose_titles_metas` | 5 title + meta candidates per page across 5 angles, length-validated |
| `explain_seo_terms` | Reference glossary of 40+ SEO + AEO terms |
| `install_skill` | Returns install commands for the [Ranki SEO Skill](https://github.com/1fancy/ranki-seo-skills) across every supported agent |

### Paid (7) — Ranki.io API key required

These call your Ranki.io account via the REST API at `app.ranki.io`. **The TS server never opens a database connection** — every paid call goes through Laravel's `ApiKeyAuth` middleware and is scoped to the calling user's data on the server side.

| Tool | What it does |
|---|---|
| `get_account` | Whoami — name, email, plan, daily/monthly limits, current usage |
| `list_projects` | Lists the projects in your Ranki.io account |
| `list_articles` | Paginated index of articles in a project: nano_id, title, status, language, focus_keyword, TOC, word count, SEO score |
| `get_article` | Single article — title, content_html, focus keyword, TOC, image URLs |
| `list_rank_tracking` | 28-day GSC summary: totals, top keywords by clicks, opportunity keywords |
| `list_gsc_keywords` | Full paginated GSC keyword list, sortable |
| `ai_visibility` | Recorded AI-citation snapshots (ChatGPT, Claude, Perplexity, Google AI Overviews) per project |

Get an API key at [app.ranki.io/developer](https://app.ranki.io/developer).

## Environment

| Variable | Purpose |
|---|---|
| `RANKI_API_KEY` | API key for the 7 paid bridge tools. Optional for the 15 free tools. |
| `GOOGLE_PSI_API_KEY` | Google PageSpeed Insights API key. Optional — without it, `audit_speed` and `audit_core_web_vitals` share Google's unauthenticated quota (~1 req/min). With it: 25,000 calls/day. |
| `RANKI_API_BASE` | Override the upstream Ranki.io API (default `https://app.ranki.io`). For self-hosted Ranki.io deployments only. |
| `RANKI_MCP_HOST` | Bind interface for `--serve` (default `127.0.0.1`). |
| `MCP_TRUSTED_PROXIES` | CSV of CIDR ranges trusted to set `X-Forwarded-For` / `CF-Connecting-IP`. Cloudflare ranges are always trusted. |

## Security

The same hardening as the PHP impl:

- **SSRF guard** — every outbound fetch validates the URL scheme (http/https only), resolves the hostname, and rejects any IP in private / loopback / link-local / reserved / cloud-metadata ranges (incl. AWS IMDS `169.254.169.254`). Redirects are followed manually with re-validation at every hop.
- **Body size cap** — 64 KB hard limit on `--serve` requests.
- **JSON depth cap** — depth 16, throws `-32700` on deeper input.
- **Tool-name allowlist** — `^[a-z][a-z0-9_]{0,63}$` before any filesystem touch.
- **Atomic daily rate-limit** — file-locked counters in `/tmp/ranki-mcp-rl/`. Same scheme as the PHP impl, so if you run both on one host they share state.
- **IP-spoof block** — `X-Forwarded-For` / `CF-Connecting-IP` headers are honoured ONLY when `req.socket.remoteAddress` is inside Cloudflare's published ranges (or a `MCP_TRUSTED_PROXIES` CIDR).
- **No DB access** — the TS server has zero database code. Paid bridge tools call the existing REST API.

The SSRF guard runs in tests — see `tests/ssrf.test.ts`.

## Develop

```bash
npm install
npm run dev          # tsx, hot reload on stdio
npm run build        # → dist/
npm test             # vitest
npm run serve        # HTTP server on :8787
```

## Parity with the PHP server

| Concern | PHP (`server/`) | TS (`ts-server/`) |
|---|---|---|
| Tools | 22 | 22 |
| Tool descriptions | source of truth | identical |
| SSRF guard | `lib/registry.php` `rk_mcp_url_blocked_reason` | `src/ssrf-guard.ts` |
| Rate limit | `lib/ratelimit.php` (`flock`) | `src/ratelimit.ts` (mkdir lockfile, same bucket files) |
| Body cap | 64 KB | 64 KB |
| JSON depth cap | 16 | 16 |
| Stdio transport | n/a (PHP is HTTP-only) | `@modelcontextprotocol/sdk` |
| HTTP transport | `index.php` | `src/transport-http.ts` |

If you find a divergence, that's a bug in one of them — please open an issue.

## License

MIT. See [LICENSE](../LICENSE).
