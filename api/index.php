<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Run the full Laravel bootstrap process directly to catch any startup exceptions
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // If bootstrap succeeds, print a success message
    echo "<h1>Laravel Bootstrap Success</h1>";
    echo "<p>Laravel bootstrapped successfully with all config, environment, and database providers.</p>";
    
} catch (\Throwable $e) {
    // Keep HTTP 200 so Chrome displays the error message on screen
    echo "<h1>Laravel Serverless Bootstrap Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
