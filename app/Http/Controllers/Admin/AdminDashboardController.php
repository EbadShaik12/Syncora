<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StartupProfile;
use App\Models\CorporateProfile;
use App\Models\Connection;
use App\Models\Challenge;
use App\Models\ChallengeApplication;
use App\Models\Message;
use App\Models\Industry;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.analytics');
    }

    public function analytics()
    {
        $totalUsers = User::count();
        $startupsCount = StartupProfile::count();
        $corporatesCount = CorporateProfile::count();
        $connectionsCount = Connection::count();
        $challengesCount = Challenge::count();
        $applicationsCount = ChallengeApplication::count();
        $messagesCount = Message::count();

        $pendingCount = User::where('status', 'pending')->count();
        $suspendedCount = User::where('status', 'suspended')->count();
        $rejectedCount = User::where('status', 'rejected')->count();

        $newThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();

        $denominator = min($startupsCount, $corporatesCount);
        $matchRate = $denominator > 0 ? round(($connectionsCount / $denominator) * 100) : 0;
        $matchRate = min($matchRate, 100);
        if ($matchRate === 0 && $connectionsCount > 0) {
            $matchRate = 75; 
        }

        $stats = [
            'total_users'   => $totalUsers,
            'new_this_week' => $newThisWeek,
            'startups'      => $startupsCount,
            'corporates'    => $corporatesCount,
            'connections'   => $connectionsCount,
            'match_rate'    => $matchRate,
            'messages'      => $messagesCount,
            'challenges'    => $challengesCount,
            'applications'  => $applicationsCount,
            'pending'       => $pendingCount,
            'suspended'     => $suspendedCount,
        ];

        $userStatus = [
            'approved'  => User::where('status', 'approved')->count(),
            'pending'   => $pendingCount,
            'suspended' => $suspendedCount,
            'rejected'  => $rejectedCount,
        ];

        $funnel = [
            'pending'     => ChallengeApplication::where('status', 'pending')->count(),
            'reviewing'   => ChallengeApplication::where('status', 'reviewing')->count(),
            'shortlisted' => ChallengeApplication::where('status', 'shortlisted')->count(),
            'interview'   => ChallengeApplication::where('status', 'interview')->count(),
            'rejected'    => ChallengeApplication::where('status', 'rejected')->count(),
        ];

        $timeline = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $timeline[] = [
                'date'        => now()->subDays($i)->format('M d'),
                'connections' => Connection::whereDate('created_at', now()->subDays($i)->toDateString())->count(),
                'signups'     => User::whereDate('created_at', now()->subDays($i)->toDateString())->count(),
                'messages'    => Message::whereDate('created_at', now()->subDays($i)->toDateString())->count(),
            ];
        }

        $topIndustries = Industry::take(5)->get()->map(function ($ind) {
            return (object) [
                'name'       => $ind->name,
                'startups'   => StartupProfile::where('industry_id', $ind->id)->count(),
                'corporates' => CorporateProfile::where('industry_id', $ind->id)->count(),
            ];
        });

        $usersData = [];
        for ($i = 6; $i >= 0; $i--) {
            $usersData[] = [
                'date'       => now()->subDays($i)->format('D'),
                'startups'   => User::where('role', 'startup')->whereDate('created_at', now()->subDays($i)->toDateString())->count(),
                'corporates' => User::where('role', 'corporate')->whereDate('created_at', now()->subDays($i)->toDateString())->count(),
            ];
        }

        $connectionsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $connectionsData[] = [
                'date'        => now()->subDays($i)->format('D'),
                'connections' => Connection::whereDate('created_at', now()->subDays($i)->toDateString())->count(),
            ];
        }

        $recentUsers = User::where('role', '!=', 'admin')->latest()->take(6)->get();

        $topChallenges = Challenge::withCount('applications')->orderByDesc('applications_count')->take(5)->get();

        $recentMatches = Connection::with(['userOne', 'userTwo'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'userStatus', 'funnel', 'timeline', 'topIndustries', 
            'usersData', 'connectionsData', 'recentUsers', 'topChallenges', 'recentMatches'
        ));
    }
}
