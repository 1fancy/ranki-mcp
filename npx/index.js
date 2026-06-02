#!/usr/bin/env node
/**
 * @ranki.io/mcp — thin Node.js shim that proxies stdio JSON-RPC to the hosted
 * Ranki.io MCP server at https://mcp.ranki.io. Vibe-coders install via:
 *
 *   { "mcpServers": { "ranki": {
 *       "command": "npx",
 *       "args": ["-y", "@ranki.io/mcp"],
 *       "env": { "RANKI_API_KEY": "rk_live_..." }
 *   } } }
 *
 * Why a shim instead of a full MCP SDK install? The hosted server already
 * speaks MCP 2024-11-05 over HTTP. The shim just translates stdin (one
 * JSON-RPC message per line) to a POST and pipes the response to stdout.
 * Keeps the install tiny (no SDK dependency) and the source-of-truth in
 * PHP at mcp.ranki.io.
 */

const ENDPOINT = process.env.RANKI_MCP_ENDPOINT || 'https://mcp.ranki.io/';
const API_KEY = process.env.RANKI_API_KEY || '';

// MCP stdio protocol: each message is a line of JSON terminated by '\n'.
// We accumulate stdin chunks, split on newline, dispatch each one.
let buffer = '';
let stdinClosed = false;
let inFlight = 0;

process.stdin.setEncoding('utf8');
process.stdin.on('data', (chunk) => {
  buffer += chunk;
  let nl;
  while ((nl = buffer.indexOf('\n')) >= 0) {
    const line = buffer.slice(0, nl).trim();
    buffer = buffer.slice(nl + 1);
    if (line) {
      inFlight++;
      handleMessage(line).finally(() => {
        inFlight--;
        if (stdinClosed && inFlight === 0) process.exit(0);
      });
    }
  }
});

process.stdin.on('end', () => {
  stdinClosed = true;
  if (inFlight === 0) process.exit(0);
});

async function handleMessage(line) {
  let req;
  try {
    req = JSON.parse(line);
  } catch (e) {
    // Malformed input — emit a parse-error response if we can recover an id
    writeResponse({ jsonrpc: '2.0', id: null, error: { code: -32700, message: 'Parse error' } });
    return;
  }

  // Notifications (no id) get no response per JSON-RPC spec, but we still
  // forward them upstream so the server can update its state.
  const isNotification = req.id === undefined || req.id === null;

  try {
    const headers = { 'Content-Type': 'application/json', Accept: 'application/json' };
    if (API_KEY) headers['X-API-Key'] = API_KEY;

    const res = await fetch(ENDPOINT, {
      method: 'POST',
      headers,
      body: JSON.stringify(req),
    });

    const text = await res.text();
    if (isNotification) return;

    if (!res.ok) {
      // Hosted server errored — translate to JSON-RPC error so the client
      // doesn't crash on an HTTP-shaped response.
      writeResponse({
        jsonrpc: '2.0',
        id: req.id,
        error: { code: -32000, message: `Upstream HTTP ${res.status}`, data: text.slice(0, 500) },
      });
      return;
    }

    // Pass through verbatim — server already returned a JSON-RPC envelope.
    process.stdout.write(text + '\n');
  } catch (e) {
    if (isNotification) return;
    writeResponse({
      jsonrpc: '2.0',
      id: req.id ?? null,
      error: { code: -32603, message: `Shim error: ${e.message || e}` },
    });
  }
}

function writeResponse(obj) {
  process.stdout.write(JSON.stringify(obj) + '\n');
}
