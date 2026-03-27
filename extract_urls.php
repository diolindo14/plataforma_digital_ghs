<?php
$html = file_get_contents(__DIR__ . '/app/views/admin/dashboard.php');
// Extrai rotas do admin
preg_match_all('/(href|action|fetch)\s*[=]\s*["\'](<\?= URL_ROOT \?>)?\/admin\/([^"\'?]+)["\']/i', $html, $matches);
$routes = array_unique($matches[3]);
sort($routes);
file_put_contents('routes_admin.json', json_encode(array_values($routes), JSON_PRETTY_PRINT));
