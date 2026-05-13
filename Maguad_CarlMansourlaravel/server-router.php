<?php

$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$publicPath = __DIR__ . '/public';
$requestedFile = realpath($publicPath . $requestUri);

if ($requestUri !== '/' && $requestedFile && str_starts_with($requestedFile, realpath($publicPath)) && is_file($requestedFile)) {
    return false;
}

require_once $publicPath . '/index.php';
