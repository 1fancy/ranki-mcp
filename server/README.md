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
| `tools/*.php` | One file per MCP tool. 15 in total: 12 advisor (free, IP-limited) + 3 bridge (require X-API-Key). |

## Tools

12 advisor tools (no key required):
`seo_starter_kit`, `find_topic_ideas`, `find_keyword_gap`, `audit_aeo`, `audit_seo`, `audit_hidden_pages`, `propose_titles_metas`, `explain_seo_terms`, `generate_sitemap_xml`, `generate_llms_txt`, `generate_robots_txt`, `install_skill`.

3 bridge tools (X-API-Key required):
`list_projects`, `get_article`, `get_account`.

## Rate limits

- Unauthenticated: 5 calls per IP per UTC day.
- With a Ranki.io API key (free at [app.ranki.io/developer](https://app.ranki.io/developer)): 500 calls per key per UTC day, plus access to the 3 bridge tools.
- Higher caps: email support@ranki.io.

Counters live in `/tmp/ranki-mcp-rl/` as plain files (sha256 of scope + day). Reset at midnight UTC.

## License

MIT. See [LICENSE](../LICENSE) in repo root.
