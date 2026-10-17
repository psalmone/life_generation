<?php
/**
 * PHP Built-in Server Router for LavaLust
 * Handles static assets and routes all other requests to index.php
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

// If the file exists and is not a directory, serve it as static file
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}

// Otherwise route through index.php
require __DIR__ . '/index.php';
