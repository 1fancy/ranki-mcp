#!/usr/bin/env node
/**
 * @ranki/cli — one-line installer + updater for the Ranki MCP suite.
 *
 *   npx @ranki/cli install   # writes MCP config + Skill file for your AI editor
 *   npx @ranki/cli update    # re-pulls the latest Skill + bumps @ranki/mcp
 *   npx @ranki/cli check     # diagnoses your current setup
 *
 * Auto-detects which AI editor is installed on this machine and writes the
 * right config file in the right place. Falls back to interactive picker
 * if it can't tell.
 */

import { existsSync, readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { homedir } from 'node:os';
import { join, dirname } from 'node:path';
import { execSync } from 'node:child_process';
import { createInterface } from 'node:readline/promises';
import { stdin as input, stdout as output } from 'node:process';

const SKILL_RAW = 'https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo';
const HOME = homedir();
const cmd = (process.argv[2] || 'install').toLowerCase();

const c = {
  reset: '\x1b[0m', bold: '\x1b[1m', dim: '\x1b[2m',
  orange: '\x1b[38;5;208m', green: '\x1b[32m', red: '\x1b[31m',
  blue: '\x1b[34m', yellow: '\x1b[33m',
};

function log(msg) { console.log(msg); }
function ok(msg) { log(`  ${c.green}✓${c.reset} ${msg}`); }
function info(msg) { log(`  ${c.blue}→${c.reset} ${msg}`); }
function warn(msg) { log(`  ${c.yellow}!${c.reset} ${msg}`); }
function err(msg) { log(`  ${c.red}✗${c.reset} ${msg}`); }
function header(msg) { log(`\n${c.bold}${c.orange}${msg}${c.reset}\n`); }

const TARGETS = {
  claude_desktop: {
    label: 'Claude Desktop',
    detect: () => existsSync(join(HOME, 'Library/Application Support/Claude'))
              || existsSync(join(HOME, '.config/Claude'))
              || existsSync(join(HOME, 'AppData/Roaming/Claude')),
    configPath: () => {
      if (process.platform === 'darwin') return join(HOME, 'Library/Application Support/Claude/claude_desktop_config.json');
      if (process.platform === 'win32') return join(HOME, 'AppData/Roaming/Claude/claude_desktop_config.json');
      return join(HOME, '.config/Claude/claude_desktop_config.json');
    },
    writer: writeStdioMcp,
    skillPath: () => join(HOME, '.claude/skills/ranki-seo/SKILL.md'),
    skillUrl: `${SKILL_RAW}/SKILL.md`,
  },
  claude_code: {
    label: 'Claude Code (CLI)',
    detect: () => {
      try { execSync('claude --version', { stdio: 'ignore' }); return true; } catch { return false; }
    },
    install: (key) => {
      try {
        execSync(`claude mcp add ranki -e RANKI_API_KEY=${key} -- npx -y @ranki/mcp`, { stdio: 'inherit' });
        return true;
      } catch { return false; }
    },
    skillPath: () => join(HOME, '.claude/skills/ranki-seo/SKILL.md'),
    skillUrl: `${SKILL_RAW}/SKILL.md`,
  },
  cursor: {
    label: 'Cursor',
    detect: () => existsSync(join(HOME, 'Library/Application Support/Cursor'))
              || existsSync(join(HOME, '.config/Cursor'))
              || existsSync(join(HOME, 'AppData/Roaming/Cursor')),
    configPath: () => join(process.cwd(), '.cursor/mcp.json'),
    writer: writeHttpMcp,
    skillPath: () => join(process.cwd(), '.cursorrules'),
    skillUrl: `${SKILL_RAW}/.cursorrules`,
  },
  windsurf: {
    label: 'Windsurf',
    detect: () => existsSync(join(HOME, '.codeium/windsurf'))
              || existsSync(join(HOME, 'AppData/Roaming/Windsurf')),
    configPath: () => join(process.cwd(), '.windsurf/mcp.json'),
    writer: writeHttpMcp,
    skillPath: () => join(process.cwd(), '.windsurfrules'),
    skillUrl: `${SKILL_RAW}/.windsurfrules`,
  },
  chatgpt_desktop: {
    label: 'ChatGPT Desktop',
    detect: () => existsSync(join(HOME, 'Library/Application Support/ChatGPT'))
              || existsSync(join(HOME, '.config/chatgpt-desktop'))
              || existsSync(join(HOME, 'AppData/Roaming/ChatGPT')),
    configPath: () => {
      if (process.platform === 'darwin') return join(HOME, 'Library/Application Support/ChatGPT/mcp.json');
      if (process.platform === 'win32') return join(HOME, 'AppData/Roaming/ChatGPT/mcp.json');
      return join(HOME, '.config/chatgpt-desktop/mcp.json');
    },
    writer: writeStdioMcp,
    skillPath: () => join(HOME, '.claude/skills/ranki-seo/SKILL.md'),
    skillUrl: `${SKILL_RAW}/SKILL.md`,
  },
};

function writeStdioMcp(path, key) {
  let existing = {};
  if (existsSync(path)) {
    try { existing = JSON.parse(readFileSync(path, 'utf8')); } catch {}
  }
  existing.mcpServers = existing.mcpServers || {};
  existing.mcpServers.ranki = {
    command: 'npx',
    args: ['-y', '@ranki/mcp'],
    env: { RANKI_API_KEY: key },
  };
  mkdirSync(dirname(path), { recursive: true });
  writeFileSync(path, JSON.stringify(existing, null, 2));
}

function writeHttpMcp(path, key) {
  let existing = {};
  if (existsSync(path)) {
    try { existing = JSON.parse(readFileSync(path, 'utf8')); } catch {}
  }
  existing.mcpServers = existing.mcpServers || {};
  existing.mcpServers.ranki = {
    url: 'https://mcp.ranki.io',
    headers: { 'X-API-Key': key },
  };
  mkdirSync(dirname(path), { recursive: true });
  writeFileSync(path, JSON.stringify(existing, null, 2));
}

async function fetchToFile(url, dest) {
  const res = await fetch(url);
  if (!res.ok) throw new Error(`Fetch ${url} failed (HTTP ${res.status})`);
  const body = await res.text();
  mkdirSync(dirname(dest), { recursive: true });
  writeFileSync(dest, body);
  return body.length;
}

async function ask(q) {
  const rl = createInterface({ input, output });
  const a = await rl.question(q);
  rl.close();
  return a.trim();
}

async function detectAll() {
  const found = [];
  for (const [k, t] of Object.entries(TARGETS)) {
    try { if (t.detect()) found.push(k); } catch {}
  }
  return found;
}

async function pickTarget() {
  const found = await detectAll();
  if (found.length === 0) {
    warn(`No supported editor auto-detected. Listing all:`);
  } else if (found.length === 1) {
    info(`Detected: ${c.bold}${TARGETS[found[0]].label}${c.reset}`);
    return found[0];
  }

  const list = Object.keys(TARGETS);
  list.forEach((k, i) => {
    const flag = found.includes(k) ? `${c.green}(detected)${c.reset}` : c.dim + '(install path)' + c.reset;
    log(`    ${i + 1}. ${TARGETS[k].label} ${flag}`);
  });
  const pick = await ask(`\n  Pick a number (1-${list.length}) or 'all': `);
  if (pick.toLowerCase() === 'all') return 'all';
  const idx = parseInt(pick, 10) - 1;
  if (isNaN(idx) || idx < 0 || idx >= list.length) {
    err('Invalid selection');
    process.exit(1);
  }
  return list[idx];
}

async function getApiKey(explicit) {
  if (explicit) return explicit;
  if (process.env.RANKI_API_KEY) {
    info(`Using RANKI_API_KEY from env (${process.env.RANKI_API_KEY.slice(0, 12)}…)`);
    return process.env.RANKI_API_KEY;
  }
  log(`\n  ${c.dim}Generate a free key at ${c.reset}${c.blue}https://app.ranki.io/developer${c.reset}`);
  log(`  ${c.dim}(or skip — advisor tools still work without one, rate-limited 5/IP/day)${c.reset}\n`);
  const k = await ask('  Paste your Ranki.io API key (or Enter to skip): ');
  return k || 'YOUR_KEY';
}

async function installOne(targetKey, apiKey) {
  const t = TARGETS[targetKey];
  log(`\n  ${c.bold}${t.label}${c.reset}`);

  // MCP install
  if (t.install) {
    if (t.install(apiKey)) {
      ok('MCP server registered via CLI');
    } else {
      err('CLI install failed; falling back to manual config');
    }
  } else if (t.configPath && t.writer) {
    const cp = t.configPath();
    try {
      t.writer(cp, apiKey);
      ok(`MCP config written to ${c.dim}${cp}${c.reset}`);
    } catch (e) {
      err(`Failed to write MCP config: ${e.message}`);
    }
  }

  // Skill install
  if (t.skillPath && t.skillUrl) {
    const sp = t.skillPath();
    try {
      const bytes = await fetchToFile(t.skillUrl, sp);
      ok(`Skill downloaded → ${c.dim}${sp}${c.reset} (${bytes} bytes)`);
    } catch (e) {
      err(`Skill download failed: ${e.message}`);
    }
  }
}

async function install() {
  header('Ranki — install MCP + Skill');
  const apiKey = await getApiKey();
  const targetKey = await pickTarget();
  const targets = targetKey === 'all' ? Object.keys(TARGETS) : [targetKey];
  for (const t of targets) {
    await installOne(t, apiKey);
  }
  log(`\n  ${c.bold}Done.${c.reset} Restart your editor, then say to your AI:\n`);
  log(`  ${c.orange}"audit my site for SEO and fix it"${c.reset}\n`);
  log(`  ${c.dim}Docs: https://mcp.ranki.io · ${c.reset}${c.dim}Repo: https://github.com/1fancy/seo-aeo-audit-mcp-ranki${c.reset}\n`);
}

async function update() {
  header('Ranki — update Skill + MCP package');
  const targets = await detectAll();
  if (targets.length === 0) {
    warn('No installed editors detected. Run `npx @ranki/cli install` first.');
    return;
  }
  for (const k of targets) {
    const t = TARGETS[k];
    if (t.skillPath && t.skillUrl && existsSync(t.skillPath())) {
      try {
        const bytes = await fetchToFile(t.skillUrl, t.skillPath());
        ok(`Skill refreshed for ${t.label} (${bytes} bytes)`);
      } catch (e) {
        err(`${t.label}: ${e.message}`);
      }
    }
  }
  log(`\n  ${c.dim}The MCP server itself uses npx -y @ranki/mcp, which auto-pulls latest on every launch.${c.reset}\n`);
}

async function check() {
  header('Ranki — check setup');
  const detected = await detectAll();
  if (detected.length === 0) {
    warn('No supported editors detected on this machine.');
  } else {
    detected.forEach((k) => ok(`${TARGETS[k].label} installed`));
  }
  try {
    const r = await fetch('https://mcp.ranki.io/', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ jsonrpc: '2.0', id: 1, method: 'tools/list' }),
    });
    const j = await r.json();
    const n = j.result?.tools?.length || 0;
    ok(`mcp.ranki.io reachable — ${n} tools available`);
  } catch (e) {
    err(`mcp.ranki.io unreachable: ${e.message}`);
  }
  if (process.env.RANKI_API_KEY) {
    try {
      const r = await fetch('https://app.ranki.io/api/v1/me', {
        headers: { 'X-API-Key': process.env.RANKI_API_KEY },
      });
      if (r.ok) {
        const me = await r.json();
        ok(`API key valid — ${me.email} (${me.plan})`);
      } else {
        err('API key invalid; regenerate at https://app.ranki.io/developer');
      }
    } catch (e) {
      err(`API check failed: ${e.message}`);
    }
  } else {
    info(`Set ${c.bold}RANKI_API_KEY${c.reset} to verify your key`);
  }
}

function help() {
  log(`
  ${c.bold}@ranki/cli${c.reset} — one-line installer for Ranki MCP + Skill

  ${c.bold}Commands${c.reset}
    install    Write MCP config + download Skill into your editor
    update     Refresh the Skill file; MCP auto-updates on next launch
    check      Diagnose your current setup
    help       This message

  ${c.bold}Examples${c.reset}
    ${c.dim}# Interactive install (auto-detects your editor):${c.reset}
    npx @ranki/cli install

    ${c.dim}# Update an existing install:${c.reset}
    npx @ranki/cli update

    ${c.dim}# Verify everything works:${c.reset}
    RANKI_API_KEY=rk_live_... npx @ranki/cli check

  ${c.bold}Docs${c.reset}  https://mcp.ranki.io
  ${c.bold}Source${c.reset}  https://github.com/1fancy/seo-aeo-audit-mcp-ranki
`);
}

(async () => {
  try {
    switch (cmd) {
      case 'install': return await install();
      case 'update':  return await update();
      case 'check':   return await check();
      case 'help':    return help();
      case '-h':      return help();
      case '--help':  return help();
      default:
        err(`Unknown command: ${cmd}`);
        help();
        process.exit(1);
    }
  } catch (e) {
    err(`${e.message}`);
    process.exit(1);
  }
})();
