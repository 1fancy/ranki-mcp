<?php
declare(strict_types=1);

return function (array $args, string $apiKey): array {
    $perPage = (int) min(50, max(1, (int) ($args['per_page'] ?? 25)));
    $resp = rk_mcp_api_call("/api/v1/projects?per_page={$perPage}", $apiKey);

    $rows = $resp['data'] ?? [];
    $meta = $resp['meta'] ?? [];

    if (empty($rows)) {
        return rk_mcp_text_content('No projects in your Ranki.io account yet. Create one at https://app.ranki.io/projects');
    }

    $out = "Your Ranki.io projects ({$meta['total']} total):\n\n";
    foreach ($rows as $p) {
        $out .= "• {$p['name']} ({$p['id']})\n";
        $out .= '  URL: '.($p['url'] ?? 'n/a')."\n";
        $out .= '  Language: '.($p['writing_language'] ?? 'n/a').' · '.($p['is_active'] ? 'active' : 'paused')."\n\n";
    }

    if (($meta['last_page'] ?? 1) > 1) {
        $out .= "(showing page 1 of {$meta['last_page']})\n";
    }

    return rk_mcp_text_content($out);
};
