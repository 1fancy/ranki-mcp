import { z } from 'zod';
import { textContent } from '../mcp-helpers.js';

export const InstallSkillInput = z.object({
  agent: z
    .enum(['claude_code', 'claude_desktop', 'cursor', 'windsurf', 'claude_web', 'generic', 'all'])
    .optional()
    .default('all'),
});

const SKILL_BASE = 'https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo';

const SNIPPETS: Record<string, { label: string; body: string }> = {
  one_liner: {
    label: 'One-line CLI (auto-detects your editor)',
    body: 'npx @ranki.io/cli install',
  },
  claude_code: {
    label: 'Claude Code · Claude Desktop (user-level)',
    body: `mkdir -p ~/.claude/skills/ranki-seo
curl -fsSL ${SKILL_BASE}/SKILL.md -o ~/.claude/skills/ranki-seo/SKILL.md
# restart Claude Code`,
  },
  claude_desktop: {
    label: 'Claude Desktop',
    body: `mkdir -p ~/.claude/skills/ranki-seo
curl -fsSL ${SKILL_BASE}/SKILL.md -o ~/.claude/skills/ranki-seo/SKILL.md
# restart Claude Desktop`,
  },
  cursor: {
    label: 'Cursor (project-level rule file)',
    body: `curl -fsSL ${SKILL_BASE}/.cursorrules -o .cursorrules
# Cursor picks it up automatically — no restart needed`,
  },
  windsurf: {
    label: 'Windsurf (project-level rule file)',
    body: `curl -fsSL ${SKILL_BASE}/.windsurfrules -o .windsurfrules`,
  },
  claude_web: {
    label: 'Claude.ai web — Projects (no file install)',
    body: `# Open claude.ai → Projects → New project
# Custom instructions: paste the body of SKILL.md (skip the YAML frontmatter).
# Body URL: ${SKILL_BASE}/SKILL.md
# Every chat in the Project auto-loads it.`,
  },
  generic: {
    label: 'Generic AGENTS.md (Continue.dev, Zed AI, OpenAI Codex, custom MCP clients)',
    body: `curl -fsSL ${SKILL_BASE}/AGENTS.md -o AGENTS.md`,
  },
};

export async function installSkill(args: z.infer<typeof InstallSkillInput>) {
  const which = args.agent;
  let out = `RANKI SEO SKILL — install commands\n\n`;
  out += `Source repo: https://github.com/1fancy/ranki-seo-skills\n\n`;
  out += `Easiest:\n  ${SNIPPETS.one_liner.body}\n\n`;
  out += `(The one-line CLI auto-detects your editor and writes the right config. Manual snippets below.)\n\n`;

  const wantedKeys =
    which === 'all'
      ? ['claude_code', 'claude_desktop', 'cursor', 'windsurf', 'claude_web', 'generic']
      : [which];

  for (const k of wantedKeys) {
    const s = SNIPPETS[k];
    if (!s) continue;
    out += `## ${s.label}\n\n${s.body}\n\n`;
  }

  out += `After install, ask the agent: "audit my site and fix what's broken at https://my-site.com". The Skill auto-activates on SEO/AEO prompts.\n`;
  return textContent(out);
}
