# Ranki MCP — PHP server

The hosted MCP server that powers [mcp.ranki.io](https://mcp.ranki.io).

Plain PHP 8.4 — no framework, no Composer dependencies, no database. Drop behind an Nginx vhost serving `public/index.php` and you have a working MCP server.

## Run locally

```bash
cd server/public
php -S 127.0.0.1:8080
```

Then POST a JSON-RPC request:

```bash
curl -X POST http://127.0.0.1:8080/ \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":1,"method":"tools/list"}'
```

## File layout

| Path | What it does |
|---|---|
| `public/index.php` | Web entry point. GET → marketing landing HTML. POST → forwards to `../index.php`. |
| `public/llms.txt` | The site's own llms.txt (used by AI crawlers). |
| `index.php` | JSON-RPC 2.0 dispatcher. Handles `initialize`, `tools/list`, `tools/call`, `ping`. |
| `lib/jsonrpc.php` | Reply helpers + client IP detection (Cloudflare-aware). |
| `lib/registry.php` | Tool definitions + REST API bridge to `app.ranki.io`. |
| `lib/ratelimit.php` | Per-IP and per-key rate limits (5/IP/UTC-day, 500/key/UTC-day). |
| `tools/*.php` | One file per MCP tool. 21 in total: 15 free (IP-limited) + 6 bridge (require X-API-Key). |

## Tools

15 free tools (no key required):

- **Audit:** `audit_seo`, `audit_aeo`, `audit_hidden_pages`
- **Speed and images:** `audit_speed`, `audit_core_web_vitals`, `optimize_images`
- **Generate:** `generate_sitemap_xml`, `generate_llms_txt`, `generate_robots_txt`
- **Content and strategy:** `seo_starter_kit`, `find_topic_ideas`, `find_keyword_gap`, `propose_titles_metas`, `explain_seo_terms`
- **Install:** `install_skill`

6 paid bridge tools (X-API-Key required):
`get_account`, `list_projects`, `get_article`, `list_rank_tracking`, `list_gsc_keywords`, `ai_visibility`.

## Rate limits

- No key: 5 calls per IP per UTC day. Free tools only.
- Ranki.io API key (paid plan, [app.ranki.io/developer](https://app.ranki.io/developer)): 500 calls per key per UTC day, plus access to the 6 bridge tools that read your real Google Search Console keywords, rank tracking and AI citations.
- Higher caps: email support@ranki.io.

Counters live in `/tmp/ranki-mcp-rl/` as plain files (sha256 of scope + day). Reset at midnight UTC.

## Environment

- `GOOGLE_PSI_API_KEY` — Google PageSpeed Insights API key (free tier, 25k requests/day). Used by `audit_speed` and `audit_core_web_vitals`. Without it the upstream rate limit is much tighter (around 1 request per minute per IP) — fine for local testing, not enough for production traffic.

## License

MIT. See [LICENSE](../LICENSE) in repo root.
