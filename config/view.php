<?php

return [
    'paths' => [resource_path('views')],
    'compiled' => env('VIEW_COMPILED_PATH', isset($_ENV['VERCEL']) ? '/tmp' : realpath(storage_path('framework/views'))),
];
