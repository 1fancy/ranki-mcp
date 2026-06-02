# @ranki.io/cli

One command that wires the Ranki MCP server, the Claude Skill, and the REST API into whatever AI editor you're using.

```bash
npx @ranki.io/cli install
```

Auto-detects Claude Code, Claude Desktop, Cursor, Windsurf, ChatGPT Desktop. Writes the right config in the right place. Downloads the Skill file. Done.

## Commands

```bash
npx @ranki.io/cli install   # write MCP config + download Skill
npx @ranki.io/cli update    # refresh Skill; MCP auto-updates on next launch
npx @ranki.io/cli check     # verify your setup works
npx @ranki.io/cli help      # print help
```

## API key

The advisor tools (audit_seo, audit_aeo, generate_sitemap_xml, etc.) work without a key, rate-limited 5/IP/day. With a free Ranki.io key you get 500/day and unlock the bridge tools (list_projects, get_article, get_account).

Generate one at https://app.ranki.io/developer, then either:

- Paste it when the installer asks
- Set `RANKI_API_KEY` in your shell, or
- Edit the MCP config the installer just wrote

## What it installs per editor

| Editor | MCP config location | Skill location |
|---|---|---|
| Claude Code (CLI) | `claude mcp add ranki ...` | `~/.claude/skills/ranki-seo/SKILL.md` |
| Claude Desktop | `claude_desktop_config.json` | `~/.claude/skills/ranki-seo/SKILL.md` |
| Cursor | `.cursor/mcp.json` (project) | `.cursorrules` (project) |
| Windsurf | `.windsurf/mcp.json` (project) | `.windsurfrules` (project) |
| ChatGPT Desktop | `mcp.json` (per-OS) | `~/.claude/skills/ranki-seo/SKILL.md` |

## License

MIT.
