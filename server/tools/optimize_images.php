<?php
declare(strict_types=1);

/**
 * optimize_images — for each image URL (or local path) the user gives,
 * return: target format (WebP / AVIF), suggested target dimensions for
 * a responsive set (1x / 2x), a generated `<picture>` markup block
 * with `srcset` and a placeholder `alt`, plus the literal CLI command
 * the agent should run in the user's repo to convert the file.
 *
 * Heuristic — no actual conversion server-side. The agent runs sharp or
 * cwebp locally to actually convert. We give the prescription, the agent
 * fills it.
 */
return function (array $args, string $apiKey): array {
    $images = $args['images'] ?? null;
    if (! is_array($images) || count($images) === 0) {
        throw new \RuntimeException('images (array of URLs or paths) is required');
    }
    if (count($images) > 20) {
        $images = array_slice($images, 0, 20);
    }

    $defaultMaxWidth = (int) ($args['max_width'] ?? 1600);
    if ($defaultMaxWidth < 200 || $defaultMaxWidth > 4000) {
        $defaultMaxWidth = 1600;
    }

    $out = "IMAGE OPTIMIZATION PLAN — ".count($images)." image(s)\n";
    $out .= "Target formats (in priority order): AVIF (best compression), WebP (fallback), original (final fallback)\n\n";

    $picks = [];
    foreach ($images as $idx => $imgRaw) {
        $img = (string) $imgRaw;
        $isUrl = preg_match('~^https?://~i', $img);
        $basename = basename(parse_url($img, PHP_URL_PATH) ?? $img);
        $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
        $stem = pathinfo($basename, PATHINFO_FILENAME);

        // Probe size only for URLs (HEAD request).
        $bytes = null;
        $width = null;
        $height = null;
        if ($isUrl && rk_mcp_url_blocked_reason($img) === null) {
            $ch = curl_init($img);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_NOBODY => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_CONNECTTIMEOUT => 4,
                CURLOPT_TIMEOUT => 8,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; RankiMCP/0.3; +https://ranki.io)',
            ]);
            curl_exec($ch);
            $cl = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            $bytes = $cl > 0 ? (int) $cl : null;
            curl_close($ch);
        }

        $altSuggestion = ucfirst(str_replace(['-', '_'], ' ', $stem));
        if (strlen($altSuggestion) > 100) {
            $altSuggestion = substr($altSuggestion, 0, 97).'…';
        }
        if ($altSuggestion === '' || ctype_digit($altSuggestion)) {
            $altSuggestion = 'TODO: describe what this image shows (the agent must replace this)';
        }

        $verdict = match (true) {
            in_array($ext, ['svg', 'webp', 'avif'], true) => 'already-modern',
            in_array($ext, ['png', 'jpg', 'jpeg', 'gif'], true) => 'convert',
            default => 'unknown-format',
        };

        $picks[] = [
            'i' => (int) $idx + 1,
            'src' => $img,
            'ext' => $ext,
            'bytes' => $bytes,
            'verdict' => $verdict,
            'alt' => $altSuggestion,
            'stem' => $stem,
        ];
    }

    foreach ($picks as $p) {
        $i = $p['i'];
        $src = $p['src'];
        $ext = $p['ext'];
        $sizeStr = $p['bytes'] !== null ? sprintf(' (%.1f KB)', $p['bytes'] / 1024) : '';

        $out .= "[{$i}] {$src}{$sizeStr}\n";
        $out .= "  Current format: ".($ext ?: 'unknown')."\n";

        if ($p['verdict'] === 'already-modern') {
            $out .= "  Verdict:        already-modern — no conversion needed.\n";
            $out .= "  Action:         confirm the <img alt=\"...\"> describes the image (current suggestion: \"{$p['alt']}\").\n\n";
            continue;
        }

        if ($p['verdict'] === 'unknown-format') {
            $out .= "  Verdict:        unknown extension — agent should inspect manually.\n\n";
            continue;
        }

        // Convert recipe (png/jpg/jpeg/gif → AVIF + WebP, plus original as fallback)
        $stem = $p['stem'];
        $out .= "  Verdict:        convert to AVIF + WebP (keep original as fallback)\n";
        $out .= "  Alt suggestion: \"{$p['alt']}\"\n";
        $out .= "  Target widths:  {$defaultMaxWidth}w (1x), ".((int) ($defaultMaxWidth * 0.5))."w (mobile)\n";
        $out .= "\n";

        $out .= "  Sharp (Node — preferred for build pipelines):\n";
        $out .= "    npx sharp-cli -i \"{$stem}.{$ext}\" -o \"{$stem}.avif\" -f avif --quality 50 resize {$defaultMaxWidth}\n";
        $out .= "    npx sharp-cli -i \"{$stem}.{$ext}\" -o \"{$stem}.webp\" -f webp --quality 75 resize {$defaultMaxWidth}\n";
        $out .= "    npx sharp-cli -i \"{$stem}.{$ext}\" -o \"{$stem}@2x.avif\" -f avif --quality 50 resize ".((int) ($defaultMaxWidth * 2))."\n";
        $out .= "    npx sharp-cli -i \"{$stem}.{$ext}\" -o \"{$stem}@2x.webp\" -f webp --quality 75 resize ".((int) ($defaultMaxWidth * 2))."\n\n";

        $out .= "  cwebp / avifenc (CLI fallback):\n";
        $out .= "    cwebp -q 75 -resize {$defaultMaxWidth} 0 \"{$stem}.{$ext}\" -o \"{$stem}.webp\"\n";
        $out .= "    avifenc --min 25 --max 35 \"{$stem}.{$ext}\" \"{$stem}.avif\"\n\n";

        $out .= "  Replace existing <img> with responsive <picture>:\n";
        $out .= "    <picture>\n";
        $out .= "      <source type=\"image/avif\" srcset=\"{$stem}.avif 1x, {$stem}@2x.avif 2x\">\n";
        $out .= "      <source type=\"image/webp\" srcset=\"{$stem}.webp 1x, {$stem}@2x.webp 2x\">\n";
        $out .= "      <img src=\"{$stem}.{$ext}\" alt=\"{$p['alt']}\" width=\"{$defaultMaxWidth}\" loading=\"lazy\" decoding=\"async\">\n";
        $out .= "    </picture>\n\n";
    }

    $out .= "Agent workflow:\n";
    $out .= "  1. Find these image files in the repo (use Grep/Glob — they're referenced from <img src> or import statements).\n";
    $out .= "  2. Run the conversion command for each (sharp if package.json has it, else cwebp/avifenc).\n";
    $out .= "  3. Replace every <img> referencing the original with the <picture> block above. Don't forget React/Vue/Astro/Svelte variants — adapt the markup to the framework.\n";
    $out .= "  4. Update alt attributes (the suggestions above are heuristic stems — the agent must look at surrounding context and write something descriptive and accessible).\n";
    $out .= "  5. Add `loading=\"lazy\"` to all below-the-fold images. The hero image stays eager-loaded with fetchpriority=\"high\".\n";
    $out .= "  6. Commit with a message like: 'Convert images to AVIF + WebP, add responsive <picture>, fix alt text'.\n";
    $out .= "  7. Call `audit_speed` against the deployed URL to confirm LCP dropped.\n";

    return rk_mcp_text_content($out);
};
