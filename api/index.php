<?php

// Redirect Laravel cache files to /tmp for serverless compatibility
$cacheVars = [
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
];

foreach ($cacheVars as $key => $val) {
    putenv("{$key}={$val}");
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
}

try {
    // Forward the request to Laravel's public entrypoint
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Laravel Serverless Bootstrap Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
