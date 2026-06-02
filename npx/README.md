# @ranki.io/mcp

The Ranki.io MCP server — adds SEO + AEO tools to Claude Code, Claude Desktop, and Cursor. Audit your site, generate `sitemap.xml` / `llms.txt` / `robots.txt`, and pull your Ranki.io articles into your editor — all using **your** Claude credits.

## Install

### Claude Desktop / Claude Code (stdio via npx)

Add to `~/.claude/claude_desktop_config.json`:

```json
{
  "mcpServers": {
    "ranki": {
      "command": "npx",
      "args": ["-y", "@ranki.io/mcp"],
      "env": { "RANKI_API_KEY": "rk_live_..." }
    }
  }
}
```

### Cursor (HTTP transport, no npx needed)

`.cursor/mcp.json` in your project root:

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

## Tools

| Tool | Auth | What it does |
|---|---|---|
| `audit_aeo` | Free (5/day per IP) | Scorecard + fix recipes for ChatGPT/Claude/Perplexity/AI Overviews citation signals |
| `audit_seo` | Free | 10-check on-page SEO scorecard |
| `generate_sitemap_xml` | Free | Build `sitemap.xml` from URL list |
| `generate_llms_txt` | Free | Generate the emerging `llms.txt` standard |
| `generate_robots_txt` | Free | Build `robots.txt` allowing/blocking AI crawlers |
| `list_projects` | API key | List your Ranki.io projects |
| `get_article` | API key | Fetch full Ranki.io article (HTML, TOC, images, keywords) |

Get a free API key at https://app.ranki.io/developer

## Why "advisor only"?

The MCP server returns **structure + advice** (checklists, generated files, fix recipes). Your Claude/Cursor evaluates the advice against your code using **your** Claude credits. Ranki MCP never makes LLM calls on your behalf — no surprise bills.

## License

MIT
