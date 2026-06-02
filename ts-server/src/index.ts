#!/usr/bin/env node
/**
 * Ranki MCP — Node/TypeScript entry.
 *
 * Two modes:
 *   1. stdio (default) — for Claude Code, Claude Desktop, ChatGPT Desktop
 *      and any local MCP client that spawns the server as a subprocess.
 *      This is what `npx -y @ranki.io/mcp-ts` does.
 *
 *   2. HTTP (--serve) — runs a JSON-RPC 2.0 HTTP server with the same
 *      security guards as the PHP impl on mcp.ranki.io. For Cursor /
 *      Windsurf / self-hosted setups.
 *
 * The TS impl never touches your DB. It calls the hosted Ranki.io REST
 * API at app.ranki.io for the 7 paid bridge tools and runs the other
 * 15 tools natively.
 */
import { runStdio } from './transport-stdio.js';
import { runHttp } from './transport-http.js';

const args = process.argv.slice(2);

if (args.includes('--help') || args.includes('-h')) {
  printHelp();
  process.exit(0);
}

if (args.includes('--version') || args.includes('-v')) {
  // Lazy require — keeps startup fast for the common stdio path.
  const pkg = await import('../package.json', { with: { type: 'json' } });
  console.log(pkg.default.version);
  process.exit(0);
}

if (args.includes('--serve')) {
  const portIdx = args.indexOf('--port');
  const port = portIdx >= 0 ? parseInt(args[portIdx + 1] ?? '', 10) : 8787;
  if (Number.isNaN(port) || port < 1 || port > 65535) {
    console.error('Invalid --port; expected 1-65535');
    process.exit(1);
  }
  const host = process.env.RANKI_MCP_HOST ?? '127.0.0.1';
  await runHttp({ host, port });
} else {
  await runStdio();
}

function printHelp(): void {
  console.log(`Ranki MCP (TypeScript) — SEO + AEO tools for AI editors

Usage:
  npx @ranki.io/mcp-ts                       Run as stdio MCP server (default — for
                                          Claude Code, Claude Desktop, ChatGPT
                                          Desktop, generic MCP clients).
  npx @ranki.io/mcp-ts --serve [--port N]    Run as HTTP JSON-RPC server (default
                                          port 8787; bind to 127.0.0.1 — set
                                          RANKI_MCP_HOST=0.0.0.0 to expose).
  npx @ranki.io/mcp-ts --version             Print version.
  npx @ranki.io/mcp-ts --help                This help.

Environment:
  RANKI_API_KEY            API key from app.ranki.io/developer. Required for
                           the 7 paid bridge tools (list_projects,
                           list_articles, get_article, get_account,
                           list_rank_tracking, list_gsc_keywords,
                           ai_visibility). The 15 free tools work without it.
  GOOGLE_PSI_API_KEY       Optional Google PageSpeed Insights API key.
                           Without it, audit_speed and audit_core_web_vitals
                           share Google's unauthenticated quota (~1 req/min).
  RANKI_API_BASE           Override the upstream API host (default
                           https://app.ranki.io). For self-hosted Ranki.io
                           deployments only.
  RANKI_MCP_HOST           Bind interface for --serve (default 127.0.0.1).

Source: https://github.com/1fancy/seo-aeo-audit-mcp-ranki
Hosted:  https://mcp.ranki.io
`);
}
