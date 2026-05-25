<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Connection;
use App\Models\StartupProfile;
use App\Models\CorporateProfile;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $stats = [
            'startups'    => max(User::where('role', 'startup')->where('status', 'approved')->count(), 120),
            'corporates'  => max(User::where('role', 'corporate')->where('status', 'approved')->count(), 48),
            'connections' => max(Connection::count(), 340),
            'challenges'  => max(Challenge::count(), 87),
        ];

        // Featured startups (approved, with profile)
        $featuredStartups = User::where('role', 'startup')
            ->where('status', 'approved')
            ->with('startupProfile.industry')
            ->get()
            ->filter(fn($u) => $u->startupProfile)
            ->take(8)
            ->values();

        // Featured corporates
        $featuredCorporates = User::where('role', 'corporate')
            ->where('status', 'approved')
            ->with('corporateProfile.industry')
            ->get()
            ->filter(fn($u) => $u->corporateProfile)
            ->take(6)
            ->values();

        // Success stories (hardcoded for now — can be a DB table later)
        $successStories = [
            [
                'startup'   => 'NeuralCart AI',
                'corporate' => 'TATA Digital',
                'outcome'   => 'Signed a ₹2.4 Cr pilot for AI-driven personalisation across 500+ TATA stores.',
                'industry'  => 'E-Commerce · AI',
                'avatar_s'  => 'https://ui-avatars.com/api/?name=N&background=6366f1&color=fff&bold=true&size=80',
                'avatar_c'  => 'https://ui-avatars.com/api/?name=T&background=0f172a&color=fff&bold=true&size=80',
            ],
            [
                'startup'   => 'MediSync Health',
                'corporate' => 'Apollo Healthcare',
                'outcome'   => 'Deployed real-time patient monitoring SaaS across 12 hospitals in 6 months.',
                'industry'  => 'HealthTech · SaaS',
                'avatar_s'  => 'https://ui-avatars.com/api/?name=M&background=10b981&color=fff&bold=true&size=80',
                'avatar_c'  => 'https://ui-avatars.com/api/?name=A&background=3b82f6&color=fff&bold=true&size=80',
            ],
            [
                'startup'   => 'FinFlow Systems',
                'corporate' => 'HDFC Labs',
                'outcome'   => 'Co-developed a fraud detection model cutting losses by 38% within the first quarter.',
                'industry'  => 'FinTech · ML',
                'avatar_s'  => 'https://ui-avatars.com/api/?name=F&background=f59e0b&color=fff&bold=true&size=80',
                'avatar_c'  => 'https://ui-avatars.com/api/?name=H&background=8b5cf6&color=fff&bold=true&size=80',
            ],
        ];

        return view('home', compact('stats', 'featuredStartups', 'featuredCorporates', 'successStories'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        if ($user->status === 'pending') {
            return view('auth.pending');
        }

        if ($user->status === 'rejected') {
            return view('auth.rejected');
        }

        return match($user->role) {
            'startup'   => redirect()->route('startup.dashboard'),
            'corporate' => redirect()->route('corporate.dashboard'),
            'admin'     => redirect()->route('admin.dashboard'),
            default     => abort(403),
        };
    }
}
