<?php
declare(strict_types=1);

/**
 * install_skill — returns the exact install commands for the Ranki SEO + AEO
 * Skill across every supported AI agent. Advisory only: this tool never
 * touches the user's filesystem. The calling AI reads the response and (if
 * it has a write tool) applies the commands; otherwise the user pastes
 * them into their terminal.
 *
 * Optional arg: `agent` (claude_code | claude_desktop | cursor | windsurf |
 * claude_web | all). Default: all.
 */
return function (array $args, string $apiKey): array {
    $agent = strtolower(trim((string) ($args['agent'] ?? 'all')));

    $blocks = [
        'claude_code' => [
            'title' => 'Claude Code (user-level — every project gets it)',
            'install' => "mkdir -p ~/.claude/skills/ranki-seo && \\\n  curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/SKILL.md \\\n    -o ~/.claude/skills/ranki-seo/SKILL.md",
            'verify' => "Restart Claude Code. Then say: \"audit my site for AEO and fix it\" — the Skill auto-activates.",
        ],
        'claude_desktop' => [
            'title' => 'Claude Desktop (user-level)',
            'install' => "mkdir -p ~/.claude/skills/ranki-seo && \\\n  curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/SKILL.md \\\n    -o ~/.claude/skills/ranki-seo/SKILL.md",
            'verify' => "Restart Claude Desktop. Open a new chat. Mention SEO or AEO — the Skill triggers and walks Claude through the playbook.",
        ],
        'cursor' => [
            'title' => 'Cursor (project-level — committed with your repo)',
            'install' => "curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/.cursorrules \\\n  -o .cursorrules",
            'verify' => "Cursor reloads .cursorrules automatically. No restart needed. Ask Cursor: \"audit my page for AEO\".",
        ],
        'windsurf' => [
            'title' => 'Windsurf (project-level)',
            'install' => "curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/.windsurfrules \\\n  -o .windsurfrules",
            'verify' => "Windsurf reloads .windsurfrules automatically. Then ask: \"why isn't ChatGPT citing my docs?\".",
        ],
        'claude_web' => [
            'title' => 'Claude.ai web (Pro / Team / Enterprise)',
            'install' => "# No file install — use Claude.ai Projects:\n# 1. Open https://claude.ai/projects\n# 2. Click \"New project\" → name it \"SEO + AEO\"\n# 3. Paste the body of SKILL.md (without the YAML frontmatter) into\n#    Project custom instructions.\n# 4. Every chat in that Project auto-loads the playbook.\n#\n# Get the SKILL.md body from:\n#   https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/SKILL.md",
            'verify' => "Open any chat inside the Project. The Skill is active. (Custom Connector for the MCP server also needed — see https://mcp.ranki.io/#install.)",
        ],
        'generic' => [
            'title' => 'Generic AI agent (Continue.dev · Zed AI · OpenAI Codex · custom)',
            'install' => "curl -fsSL https://raw.githubusercontent.com/1fancy/ranki-seo-skills/main/skills/ranki-seo/AGENTS.md \\\n  -o AGENTS.md",
            'verify' => "Some agents pick AGENTS.md up automatically; others want it pasted into the system prompt.",
        ],
    ];

    $aliasMap = [
        'cli' => 'claude_code',
        'claude-code' => 'claude_code',
        'claude' => 'claude_code',
        'desktop' => 'claude_desktop',
        'claude-desktop' => 'claude_desktop',
        'web' => 'claude_web',
        'claude.ai' => 'claude_web',
        'claude-web' => 'claude_web',
        'projects' => 'claude_web',
    ];
    if (isset($aliasMap[$agent])) {
        $agent = $aliasMap[$agent];
    }

    if ($agent !== 'all' && isset($blocks[$agent])) {
        $blocks = [$agent => $blocks[$agent]];
    }

    $out = "## Install the Ranki SEO + AEO Skill\n\n";
    $out .= "The Skill is a Markdown playbook for your AI. It auto-activates when you mention SEO, AEO, sitemap, llms.txt, ranking, schema, or \"why isn't ChatGPT citing my docs.\" It tells your AI when to call each Ranki MCP tool, in what order, and how to apply the fixes to your codebase.\n\n";
    $out .= "**Prerequisite:** the Ranki MCP server must be configured in your client first (`mcp.ranki.io` HTTP or `npx -y @ranki.io/mcp` stdio). The Skill orchestrates the MCP tools.\n\n";
    $out .= "---\n\n";

    foreach ($blocks as $key => $b) {
        $out .= "### {$b['title']}\n\n";
        $out .= "```bash\n{$b['install']}\n```\n\n";
        $out .= "**Verify:** {$b['verify']}\n\n";
        $out .= "---\n\n";
    }

    $out .= "## What the Skill actually does\n\n";
    $out .= "- Auto-activates on 20+ SEO/AEO trigger phrases\n";
    $out .= "- 5 pre-built playbooks: starter-kit flow, AEO-citation flow, topic-discovery, keyword-gap, perf\n";
    $out .= "- Hard constraints: never recommend forbidden patterns, never push upgrades on trivial fixes\n";
    $out .= "- Same content across all four formats (SKILL.md, .cursorrules, .windsurfrules, AGENTS.md) — tuned to each agent's conventions\n";
    $out .= "- MIT licensed — fork it, customize it for your stack\n\n";
    $out .= "**Repo:** https://github.com/1fancy/ranki-seo-skills\n";

    return rk_mcp_text_content($out);
};
