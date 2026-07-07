<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Explicitly run the bootstrap process to crash and bubble up the primary exception
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "<h1>Laravel Bootstrap Success</h1>";
} catch (\Throwable $e) {
    echo "<h1>Laravel Serverless Bootstrap Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
