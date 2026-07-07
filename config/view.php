<?php

return [
    'paths' => [resource_path('views')],
    'compiled' => env('VIEW_COMPILED_PATH', env('VERCEL') ? '/tmp' : realpath(storage_path('framework/views'))),
];
