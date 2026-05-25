<?php

namespace App\Http\Controllers;

use App\Models\ChallengeApplication;
use App\Models\Connection;
use App\Models\User;
use App\Services\CompatibilityScoreService;

class LeaderboardController extends Controller
{
    public function index(CompatibilityScoreService $scoreService)
    {
        $viewer = auth()->user();

        // ── Startup leaderboard ──────────────────────────────────────────────
        $startups = User::where('role', 'startup')
            ->where('status', 'approved')
            ->with(['startupProfile.industry', 'startupProfile.milestones', 'badges'])
            ->withCount([
                'badges',
                'connectionsAsUserOne as connections_one',
                'connectionsAsUserTwo as connections_two',
                'applications',
            ])
            ->get()
            ->map(function ($u) use ($viewer, $scoreService) {
                $profile = $u->startupProfile;

                $connections  = $u->connections_one + $u->connections_two;
                $applications = $u->applications_count;
                $shortlisted  = \App\Models\ChallengeApplication::where('startup_id', $u->id)->whereIn('status', ['shortlisted','interview'])->count();
                $badges       = $u->badges_count;

                // Profile completeness
                $completeness = 0;
                if ($profile) {
                    foreach (['elevator_pitch','city','website','funding_status','team_size','founded_year'] as $field) {
                        if (!empty($profile->$field)) $completeness += 5;
                    }
                    if ($profile->tech_tags  && count($profile->tech_tags))  $completeness += 10;
                    if ($profile->milestones && $profile->milestones->count()) $completeness += 10;
                }

                $compatibilityScore = 0;
                if ($viewer->isCorporate()) {
                    $score = $scoreService->calculate($u, $viewer);
                    $compatibilityScore = $score['score'] ?? 0;
                }

                $totalScore = ($connections  * 15)
                            + ($applications * 8)
                            + ($shortlisted  * 20)
                            + ($badges       * 12)
                            + $completeness
                            + (int)($compatibilityScore / 2);

                $u->lb_score        = round($totalScore);
                $u->lb_connections  = $connections;
                $u->lb_applications = $applications;
                $u->lb_shortlisted  = $shortlisted;
                $u->lb_badges       = $badges;
                $u->lb_compat       = $compatibilityScore;

                return $u;
            })
            ->sortByDesc('lb_score')
            ->values();


        // ── Corporate leaderboard ────────────────────────────────────────────
        $corporates = User::where('role', 'corporate')
            ->where('status', 'approved')
            ->with(['corporateProfile.industry', 'badges'])
            ->withCount(['badges', 'challenges'])
            ->get()
            ->map(function ($u) {
                $challenges   = $u->challenges_count;
                $totalApps    = \App\Models\ChallengeApplication::whereHas('challenge', fn($q) => $q->where('corporate_id', $u->id))->count();
                $shortlisted  = \App\Models\ChallengeApplication::whereHas('challenge', fn($q) => $q->where('corporate_id', $u->id))->where('status', 'shortlisted')->count();
                $connections  = Connection::where('user_one_id', $u->id)->orWhere('user_two_id', $u->id)->where('status', 'active')->count();
                $badges       = $u->badges_count;

                $totalScore = ($challenges   * 20)
                            + ($totalApps    * 5)
                            + ($shortlisted  * 15)
                            + ($connections  * 18)
                            + ($badges       * 10);

                $u->lb_score      = round($totalScore);
                $u->lb_challenges = $challenges;
                $u->lb_apps       = $totalApps;
                $u->lb_shortlisted = $shortlisted;
                $u->lb_connections = $connections;

                return $u;
            })
            ->sortByDesc('lb_score')
            ->values();

        return view('leaderboard', compact('startups', 'corporates', 'viewer'));
    }
}
