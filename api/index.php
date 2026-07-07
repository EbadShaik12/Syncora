<?php

// Force HTTPS: Vercel terminates SSL at the CDN level so $_SERVER['HTTPS']
// is never set inside the PHP function. We must tell Laravel explicitly.
$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
$_SERVER['SERVER_PORT'] = 443;

// Redirect Laravel cache files to /tmp for serverless compatibility
// The serverless filesystem is read-only, so all generated cache files
// must be written to /tmp which is the only writable directory
$cacheVars = [
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_CONFIG_CACHE'   => '/tmp/config.php',
    'APP_ROUTES_CACHE'   => '/tmp/routes.php',
    'APP_EVENTS_CACHE'   => '/tmp/events.php',
];

foreach ($cacheVars as $key => $val) {
    putenv("{$key}={$val}");
    $_ENV[$key]    = $val;
    $_SERVER[$key] = $val;
}

// SQLite fix: Vercel's project filesystem is read-only.
// Copy the database to /tmp (writable) on every cold start so Laravel
// can perform INSERT/UPDATE/DELETE operations normally.
$srcDb  = __DIR__ . '/../database/database.sqlite';
$tmpDb  = '/tmp/database.sqlite';

if (file_exists($srcDb) && !file_exists($tmpDb)) {
    copy($srcDb, $tmpDb);
}

// Point Laravel to the writable /tmp copy of the database
putenv("DB_DATABASE={$tmpDb}");
$_ENV['DB_DATABASE']    = $tmpDb;
$_SERVER['DB_DATABASE'] = $tmpDb;

// Forward the request to Laravel's standard public entrypoint
require __DIR__ . '/../public/index.php';
