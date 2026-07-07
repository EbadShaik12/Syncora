<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {});
    }

    public function render($request, Throwable $e)
    {
        if (env('VERCEL')) {
            header("Content-Type: text/plain");
            echo "ORIGINAL EXCEPTION ON VERCEL:\n";
            echo "Message: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . " (Line " . $e->getLine() . ")\n";
            echo "Trace:\n" . $e->getTraceAsString() . "\n";
            exit(0);
        }

        return parent::render($request, $e);
    }
}
