<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CompatibilityScoreService;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function startupReport(User $user, CompatibilityScoreService $scoreService)
    {
        if (!$user->isStartup()) abort(404);

        $user->load('startupProfile.industry', 'startupProfile.milestones', 'badges');
        $viewer = auth()->user();
        $compatibility = null;

        if ($viewer->isCorporate()) {
            $compatibility = $scoreService->calculate($user, $viewer);
        }

        $pdf = Pdf::loadView('pdf.startup-report', compact('user', 'compatibility'));
        $name = str_replace(' ', '-', strtolower($user->companyName()));
        return $pdf->download("{$name}-profile.pdf");
    }
}
