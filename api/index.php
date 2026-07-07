<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h1>Vercel Serverless Function Debugger</h1>";
echo "<p><strong>Current Directory (__DIR__):</strong> " . htmlspecialchars(__DIR__) . "</p>";

$parentDir = realpath(__DIR__ . '/..');
echo "<p><strong>Parent Directory (realpath):</strong> " . htmlspecialchars($parentDir) . "</p>";

if ($parentDir) {
    echo "<h3>Parent Directory Contents:</h3>";
    echo "<pre>";
    $files = scandir($parentDir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $parentDir . '/' . $file;
        echo $file . (is_dir($path) ? '/' : '') . "\n";
    }
    echo "</pre>";
} else {
    echo "<p>Parent directory could not be resolved.</p>";
}

echo "<h3>Is public/index.php present?</h3>";
$publicIndexPath = __DIR__ . '/../public/index.php';
echo "<p>Path: " . htmlspecialchars($publicIndexPath) . "</p>";
echo "<p>Exists: " . (file_exists($publicIndexPath) ? "YES" : "NO") . "</p>";

echo "<h3>Is vendor/autoload.php present?</h3>";
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
echo "<p>Path: " . htmlspecialchars($autoloadPath) . "</p>";
echo "<p>Exists: " . (file_exists($autoloadPath) ? "YES" : "NO") . "</p>";
