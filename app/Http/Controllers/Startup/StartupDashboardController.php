<?php

namespace App\Http\Controllers\Startup;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CorporateProfile;
use App\Models\Industry;
use App\Models\InterestSignal;
use App\Models\Connection;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Challenge;
use App\Models\ChallengeApplication;
use App\Models\Milestone;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StartupDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $startupProfile = $user->startupProfile;

        // If no profile completed yet, redirect to edit profile
        if (!$startupProfile) {
            return redirect()->route('startup.profile.edit');
        }

        // Dynamic Badge Award Trigger
        app(\App\Services\BadgeService::class)->checkAndAward($user);

        $tab = $request->get('tab', 'overview');

        // Fetch recent connections/matches
        $connections = Connection::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                  ->orWhere('user_two_id', $user->id);
        })
        ->with(['userOne.corporateProfile', 'userTwo.corporateProfile', 'userOne.startupProfile', 'userTwo.startupProfile'])
        ->latest()
        ->get();

        // Calculate stats
        $stats = [
            'views'           => 142,
            'connections'     => $connections->count(),
            'pending_signals' => InterestSignal::where('receiver_id', $user->id)
                ->where('status', 'interested')
                ->whereNotIn('sender_id', InterestSignal::where('sender_id', $user->id)->pluck('receiver_id')->toArray())
                ->count(),
            'applications'    => ChallengeApplication::where('startup_id', $user->id)->count(),
            'shortlisted'     => ChallengeApplication::where('startup_id', $user->id)->where('status', 'shortlisted')->count(),
            'interests'       => InterestSignal::where('receiver_id', $user->id)->count(),
            'badges'          => $user->badges()->count(),
        ];

        // 1. Overview tab data
        $milestones = Milestone::where('startup_profile_id', $startupProfile->id)
            ->get();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Mark notifications as read
        Notification::where('user_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);

        // 2. Swipe discovery data (all corporates not yet swiped)
        $swipedCorporateIds = InterestSignal::where('sender_id', $user->id)
            ->pluck('receiver_id')
            ->toArray();

        $corporatesToSwipe = User::where('role', 'corporate')
            ->where('status', 'approved')
            ->whereNotIn('id', $swipedCorporateIds)
            ->with('corporateProfile.industry')
            ->get()
            ->map(function ($corp) use ($startupProfile) {
                // Calculate compatibility score %
                $profile = $corp->corporateProfile;
                $score = 70;
                if ($profile) {
                    if ($profile->industry_id === $startupProfile->industry_id) {
                        $score += 15;
                    }
                    $startTags = $startupProfile->tech_tags ?? [];
                    $seekTags = $profile->seeking_technologies ?? [];
                    $common = array_intersect(array_map('strtolower', $startTags), array_map('strtolower', $seekTags));
                    if (count($common) > 0) {
                        $score += 10;
                    }
                    if (strtolower($profile->city) === strtolower($startupProfile->city)) {
                        $score += 4;
                    }
                }
                $corp->compatibility_score = min($score, 99);
                return $corp;
            });

        // 3. Search & Filter tab data
        $industries = Industry::all();
        
        $query = User::where('role', 'corporate')
            ->where('status', 'approved')
            ->with('corporateProfile.industry');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('corporateProfile', function ($sub) use ($search) {
                      $sub->where('company_name', 'like', "%{$search}%")
                          ->orWhere('city', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('industry_id') && $request->industry_id != '') {
            $query->whereHas('corporateProfile', function ($sub) use ($request) {
                $sub->where('industry_id', $request->industry_id);
            });
        }

        $allCorporates = $query->get()->map(function ($corp) use ($startupProfile) {
            $corp->has_sent_interest = InterestSignal::where('sender_id', Auth::id())
                ->where('receiver_id', $corp->id)
                ->where('status', 'interested')
                ->exists();
            $corp->is_connected = Connection::where(function ($q) use ($corp) {
                $q->where('user_one_id', Auth::id())->where('user_two_id', $corp->id);
            })->orWhere(function ($q) use ($corp) {
                $q->where('user_one_id', $corp->id)->where('user_two_id', Auth::id());
            })->exists();
            return $corp;
        });

        // 4. Challenges tab data
        $challenges = Challenge::where('status', 'open')
            ->with('corporate.corporateProfile')
            ->latest()
            ->get()
            ->map(function ($challenge) use ($user) {
                $challenge->has_applied = ChallengeApplication::where('challenge_id', $challenge->id)
                    ->where('startup_id', $user->id)
                    ->exists();
                return $challenge;
            });

        // 5. Milestones & Achievements (Timeline)
        $allBadges = Badge::all();
        $userBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        $openChallenges = $challenges;
        $recentConnections = $connections->take(5);
        $badges = $user->badges;

        return view('startup.dashboard', compact(
            'user', 'startupProfile', 'tab', 'stats', 'connections', 
            'milestones', 'notifications', 'corporatesToSwipe', 'allCorporates',
            'industries', 'challenges', 'allBadges', 'userBadgeIds',
            'openChallenges', 'recentConnections', 'badges'
        ));
    }
}
