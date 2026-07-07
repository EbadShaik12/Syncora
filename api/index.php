<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h1>Vercel Autoloader Debugger</h1>";

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
echo "<p>Autoload Path: " . htmlspecialchars($autoloadPath) . "</p>";
echo "<p>File Exists: " . (file_exists($autoloadPath) ? "YES" : "NO") . "</p>";

if (file_exists($autoloadPath)) {
    try {
        require $autoloadPath;
        echo "<p><strong>Success:</strong> Autoloader booted successfully!</p>";
    } catch (\Throwable $e) {
        echo "<p><strong>Error booting autoloader:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}
