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
| `index.php` | JSON-RPC 2.0 dispatcher. Handles `initialize`, `tools/list`, `tools/call`, `ping`. |
| `lib/jsonrpc.php` | Reply helpers + client IP detection (Cloudflare-aware). |
| `lib/registry.php` | Tool definitions + REST API bridge to `app.ranki.io`. |
| `lib/ratelimit.php` | Per-IP rate limit (5/UTC-day for unauthenticated calls). |
| `tools/*.php` | One file per MCP tool. Each returns a `function ($args, $apiKey): array`. |

## Add a tool

1. Create `tools/your_tool.php`:

```php
<?php
declare(strict_types=1);

return function (array $args, string $apiKey): array {
    $domain = (string) ($args['domain'] ?? '');
    if ($domain === '') {
        throw new \RuntimeException('domain is required');
    }
    // …do work, build $text…
    return rk_mcp_text_content($text);
};
```

2. Register it in `lib/registry.php` under `rk_mcp_tool_definitions()`:

```php
[
    'name' => 'your_tool',
    'description' => 'What it does (becomes the tool description Claude sees).',
    'inputSchema' => [
        'type' => 'object',
        'properties' => ['domain' => ['type' => 'string']],
        'required' => ['domain'],
    ],
],
```

3. If the tool requires a Ranki.io API key, add its name to `RK_MCP_KEYED_TOOLS` at the top of `lib/registry.php`.

That's it. The dispatcher auto-loads `tools/your_tool.php` when `tools/call` is invoked with `name: "your_tool"`.

## Production vhost (Nginx)

```nginx
server {
  listen 443 ssl http2;
  server_name mcp.yourdomain.com;
  ssl_certificate /path/to/cert.pem;
  ssl_certificate_key /path/to/key.pem;

  root /var/www/ranki-mcp/server/public;
  index index.php;

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
  }
}
```
