<?php

namespace App\Http\Controllers;

use App\Models\ChallengeApplication;
use App\Models\Connection;
use App\Models\User;
use App\Services\CompatibilityScoreService;
use App\Services\GeminiAIService;

class LeaderboardController extends Controller
{
    public function index(CompatibilityScoreService $scoreService, GeminiAIService $gemini)
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
            ->map(function ($u) use ($viewer, $scoreService, $gemini) {
                $profile = $u->startupProfile;

                $connections  = $u->connections_one + $u->connections_two;
                $applications = $u->applications_count;
                $shortlisted  = ChallengeApplication::where('startup_id', $u->id)
                                    ->whereIn('status', ['shortlisted', 'interview'])
                                    ->count();
                $badges       = $u->badges_count;

                // Profile completeness
                $completeness = 0;
                if ($profile) {
                    foreach (['elevator_pitch', 'city', 'website', 'funding_status', 'team_size', 'founded_year'] as $field) {
                        if (!empty($profile->$field)) $completeness += 5;
                    }
                    if ($profile->tech_tags  && count($profile->tech_tags))  $completeness += 10;
                    if ($profile->milestones && $profile->milestones->count()) $completeness += 10;
                }

                // Base formula score (same weights as before)
                $baseScore = ($connections  * 15)
                           + ($applications * 8)
                           + ($shortlisted  * 20)
                           + ($badges       * 12)
                           + $completeness;

                // Compatibility score (if viewer is corporate)
                $compatibilityScore = 0;
                if ($viewer->isCorporate()) {
                    $score = $scoreService->calculate($u, $viewer);
                    $compatibilityScore = $score['score'] ?? 0;
                    $baseScore += (int)($compatibilityScore / 2);
                }

                // ── Gemini AI ranking analysis ───────────────────────────
                $startupData = [
                    'name'           => $profile?->company_name ?? $u->name,
                    'industry'       => $profile?->industry?->name ?? 'Unknown',
                    'stage'          => $profile?->stage ?? 'Unknown',
                    'tech_tags'      => $profile?->tech_tags ?? [],
                    'pitch'          => $profile?->elevator_pitch ?? 'Not provided',
                    'city'           => $profile?->city ?? 'Unknown',
                    'team_size'      => $profile?->team_size ?? 'Unknown',
                    'funding_status' => $profile?->funding_status ?? 'Unknown',
                    'completeness'   => $completeness,
                ];

                $metrics = [
                    'connections'  => $connections,
                    'applications' => $applications,
                    'shortlisted'  => $shortlisted,
                    'badges'       => $badges,
                    'base_score'   => $baseScore,
                ];

                $aiRank = $gemini->rankStartup($startupData, $metrics);

                // Final score = base + AI bonus (0-100)
                $totalScore = round($baseScore + ($aiRank['ai_rank_score'] ?? 0));

                $u->lb_score          = $totalScore;
                $u->lb_connections    = $connections;
                $u->lb_applications   = $applications;
                $u->lb_shortlisted    = $shortlisted;
                $u->lb_badges         = $badges;
                $u->lb_compat         = $compatibilityScore;
                $u->lb_ai_potential   = $aiRank['ai_potential']     ?? 'Medium';
                $u->lb_ai_insight     = $aiRank['ai_insight']       ?? '';
                $u->lb_ai_bonus       = $aiRank['ai_rank_score']    ?? 0;
                $u->lb_growth_factor  = $aiRank['ai_growth_factor'] ?? 1.0;

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
            ->map(function ($u) use ($gemini) {
                $profile     = $u->corporateProfile;
                $challenges  = $u->challenges_count;
                $totalApps   = ChallengeApplication::whereHas('challenge', fn($q) => $q->where('corporate_id', $u->id))->count();
                $shortlisted = ChallengeApplication::whereHas('challenge', fn($q) => $q->where('corporate_id', $u->id))->where('status', 'shortlisted')->count();
                $connections = Connection::where('user_one_id', $u->id)->orWhere('user_two_id', $u->id)->where('status', 'active')->count();
                $badges      = $u->badges_count;

                // Base formula score
                $baseScore = ($challenges   * 20)
                           + ($totalApps    * 5)
                           + ($shortlisted  * 15)
                           + ($connections  * 18)
                           + ($badges       * 10);

                // ── Gemini AI ranking analysis ───────────────────────────
                $corporateData = [
                    'name'              => $profile?->company_name ?? $u->name,
                    'industry'          => $profile?->industry?->name ?? 'Unknown',
                    'size'              => $profile?->company_size ?? 'Unknown',
                    'partnership_types' => $profile?->partnership_types ?? [],
                    'seeking_tech'      => $profile?->seeking_technologies ?? [],
                    'city'              => $profile?->city ?? 'Unknown',
                ];

                $metrics = [
                    'challenges' => $challenges,
                    'total_apps' => $totalApps,
                    'shortlisted' => $shortlisted,
                    'connections' => $connections,
                    'badges'      => $badges,
                    'base_score'  => $baseScore,
                ];

                $aiRank = $gemini->rankCorporate($corporateData, $metrics);

                // Final score = base + AI bonus
                $totalScore = round($baseScore + ($aiRank['ai_rank_score'] ?? 0));

                $u->lb_score         = $totalScore;
                $u->lb_challenges    = $challenges;
                $u->lb_apps          = $totalApps;
                $u->lb_shortlisted   = $shortlisted;
                $u->lb_connections   = $connections;
                $u->lb_ai_potential  = $aiRank['ai_potential']     ?? 'Medium';
                $u->lb_ai_insight    = $aiRank['ai_insight']       ?? '';
                $u->lb_ai_bonus      = $aiRank['ai_rank_score']    ?? 0;
                $u->lb_growth_factor = $aiRank['ai_growth_factor'] ?? 1.0;

                return $u;
            })
            ->sortByDesc('lb_score')
            ->values();

        return view('leaderboard', compact('startups', 'corporates', 'viewer'));
    }
}
