<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Manually boot the application providers to expose any boot crashes
    $app->boot();
    
    // If booting succeeds, run the request through the HTTP kernel normally
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    // Keep HTTP 200 so Chrome displays the error message on screen
    echo "<h1>Laravel Serverless Bootstrap Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
