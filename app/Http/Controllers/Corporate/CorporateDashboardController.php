<?php

namespace App\Http\Controllers\Corporate;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Connection;
use App\Models\Challenge;
use App\Models\ChallengeApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorporateDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $corporateProfile = $user->corporateProfile;

        // If no profile completed yet, redirect to edit profile
        if (!$corporateProfile) {
            return redirect()->route('corporate.profile.edit');
        }

        // Dynamic Badge Award Trigger
        app(\App\Services\BadgeService::class)->checkAndAward($user);

        $myChallengeIds = Challenge::where('corporate_id', $user->id)->pluck('id')->toArray();

        // Calculate stats
        $stats = [
            'connections'           => Connection::where('user_one_id', $user->id)
                                                 ->orWhere('user_two_id', $user->id)
                                                 ->count(),
            'open_challenges'       => Challenge::where('corporate_id', $user->id)
                                                 ->where('status', 'open')
                                                 ->count(),
            'challenges_posted'     => Challenge::where('corporate_id', $user->id)
                                                 ->count(),
            'applications_received' => ChallengeApplication::whereIn('challenge_id', $myChallengeIds)
                                                           ->count(),
            'badges'                => $user->badges()->count(),
        ];

        // Fetch corporate's own challenges
        $myChallenges = Challenge::where('corporate_id', $user->id)
            ->withCount('applications')
            ->latest()
            ->get();

        // Fetch recent applications for their challenges
        $recentApplications = ChallengeApplication::whereIn('challenge_id', $myChallengeIds)
            ->with(['startup', 'challenge'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent connections
        $recentConnections = Connection::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne.startupProfile', 'userTwo.startupProfile'])
            ->latest()
            ->take(5)
            ->get();

        return view('corporate.dashboard', compact('stats', 'myChallenges', 'recentApplications', 'recentConnections'));
    }
}
