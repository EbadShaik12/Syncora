<?php

namespace App\Services;

use App\Models\User;
use App\Models\StartupProfile;
use App\Models\CorporateProfile;

class CompatibilityScoreService
{
    private GeminiAIService $gemini;

    public function __construct(GeminiAIService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Calculate compatibility score between a startup and corporate user.
     * Blends rule-based scoring with Gemini AI analysis for 0-100 score.
     */
    public function calculate(User $startup, User $corporate): array
    {
        if (!$startup->isStartup() || !$corporate->isCorporate()) {
            return ['score' => 0, 'breakdown' => [], 'label' => 'N/A', 'color' => 'gray'];
        }

        $sp = $startup->startupProfile;
        $cp = $corporate->corporateProfile;

        if (!$sp || !$cp) {
            return ['score' => 0, 'breakdown' => [], 'label' => 'Profile Incomplete', 'color' => 'gray'];
        }

        $industryScore = $this->scoreIndustry($sp, $cp);          // 25
        $techScore = $this->scoreTechnology($sp, $cp);            // 20
        $partnershipScore = $this->scorePartnership($sp, $cp);    // 20
        $stageScore = $this->scoreStage($sp, $cp);                // 15
        $budgetScore = $this->scoreBudget($sp, $cp);              // 10
        $locationScore = $this->scoreLocation($sp, $cp);          // 10

        $ruleScore = $industryScore + $techScore + $partnershipScore + $stageScore + $budgetScore + $locationScore;
        $ruleScore = (int) round($ruleScore);

        // ── Call Gemini AI for qualitative analysis ───────────────────────
        $startupData = [
            'name'           => $sp->company_name,
            'industry'       => $sp->industry?->name ?? 'Unknown',
            'stage'          => $sp->stage ?? 'Unknown',
            'tech_tags'      => $sp->tech_tags ?? [],
            'seeking'        => $sp->seeking ?? [],
            'pitch'          => $sp->elevator_pitch ?? 'Not provided',
            'city'           => $sp->city ?? 'Unknown',
            'team_size'      => $sp->team_size ?? 'Unknown',
            'funding_status' => $sp->funding_status ?? 'Unknown',
        ];

        $corporateData = [
            'name'              => $cp->company_name,
            'industry'          => $cp->industry?->name ?? 'Unknown',
            'size'              => $cp->company_size ?? 'Unknown',
            'seeking_tech'      => $cp->seeking_technologies ?? [],
            'partnership_types' => $cp->partnership_types ?? [],
            'city'              => $cp->city ?? 'Unknown',
        ];

        $ai = $this->gemini->analyzeCompatibility($startupData, $corporateData);

        // Blend: 60% rule-based + 40% AI score (both normalised 0-100)
        $aiScore   = $ai['ai_score'] ?? $ruleScore;
        $blended   = (int) round(($ruleScore * 0.6) + ($aiScore * 0.4));
        $total     = min(100, max(0, $blended));

        // Keep legacy analyst fields for backward compat but prefer AI data
        $roiMultiplier = $total >= 75 ? '3.8x' : ($total >= 50 ? '2.4x' : '1.6x');
        $roiPeriod     = $total >= 75 ? '18 months' : '12 months';
        $successPercentage = min(98, round($total * 0.8 + 20));
        $matchConfidence   = min(95, round($total * 0.9 + 5));

        return [
            'score'   => $total,
            'breakdown' => [
                'industry'    => ['score' => $industryScore,    'max' => 25, 'label' => 'Industry Match'],
                'technology'  => ['score' => $techScore,        'max' => 20, 'label' => 'Tech Overlap'],
                'partnership' => ['score' => $partnershipScore, 'max' => 20, 'label' => 'Partnership Type'],
                'stage'       => ['score' => $stageScore,       'max' => 15, 'label' => 'Stage Fit'],
                'budget'      => ['score' => $budgetScore,      'max' => 10, 'label' => 'Budget Range'],
                'location'    => ['score' => $locationScore,    'max' => 10, 'label' => 'Location'],
            ],
            'label' => $ai['ai_label']  ?? $this->getLabel($total),
            'color' => $this->getColor($total),
            'analyst' => [
                'success_prob_percentage' => $successPercentage,
                'confidence'              => $matchConfidence,
                'roi'                     => $roiMultiplier . ' over ' . $roiPeriod,
                'revenue_impact'          => ($total >= 75 ? '₹1.8 Cr' : ($total >= 50 ? '₹75L' : '₹30L')) . ' Projected ARR Contribution',
                // AI-powered fields
                'summary'         => $ai['ai_summary']        ?? 'Analysis pending.',
                'recommendation'  => $ai['ai_recommendation'] ?? 'Complete your profile for a better match.',
                'risk_analysis'   => !empty($ai['ai_risks'])  ? implode('. ', $ai['ai_risks']) : 'No specific risks identified.',
                'strengths'       => $ai['ai_strengths']      ?? [],
                'roi_estimate'    => $ai['ai_roi_estimate']   ?? 'N/A',
                'ai_score'        => $ai['ai_score']          ?? 0,
                'rule_score'      => $ruleScore,
            ],
        ];
    }


    private function scoreIndustry(StartupProfile $sp, CorporateProfile $cp): float
    {
        if ($sp->industry_id && $cp->industry_id && $sp->industry_id === $cp->industry_id) {
            return 25;
        }
        if ($sp->industry_id || $cp->industry_id) {
            return 10;
        }
        return 0;
    }

    private function scoreTechnology(StartupProfile $sp, CorporateProfile $cp): float
    {
        $startupTags = collect($sp->tech_tags ?? [])->map(fn($t) => strtolower(trim($t)));
        $corpTech = collect($cp->seeking_technologies ?? [])->map(fn($t) => strtolower(trim($t)));

        if ($startupTags->isEmpty() || $corpTech->isEmpty()) return 0;

        $matches = $startupTags->intersect($corpTech)->count();
        $total = max($corpTech->count(), 1);

        return min(20, ($matches / $total) * 20);
    }

    private function scorePartnership(StartupProfile $sp, CorporateProfile $cp): float
    {
        $seeking = collect($sp->seeking ?? [])->map(fn($t) => strtolower(trim($t)));
        $offers = collect($cp->partnership_types ?? [])->map(fn($t) => strtolower(trim($t)));

        if ($seeking->isEmpty() || $offers->isEmpty()) return 0;

        $matches = $seeking->intersect($offers)->count();
        if ($matches === 0) return 0;

        return min(20, ($matches / max($seeking->count(), 1)) * 20);
    }

    private function scoreStage(StartupProfile $sp, CorporateProfile $cp): float
    {
        $seekingStages = collect($cp->seeking_stages ?? [])->map(fn($t) => strtolower(trim($t)));

        if ($seekingStages->isEmpty()) return 7.5; // neutral

        return $seekingStages->contains($sp->stage) ? 15 : 0;
    }

    private function scoreBudget(StartupProfile $sp, CorporateProfile $cp): float
    {
        if (!$sp->budget_min && !$sp->budget_max) return 5;
        if (!$cp->budget_min && !$cp->budget_max) return 5;

        // Overlap check
        $startupMin = $sp->budget_min;
        $startupMax = $sp->budget_max ?: PHP_FLOAT_MAX;
        $corpMin = $cp->budget_min;
        $corpMax = $cp->budget_max ?: PHP_FLOAT_MAX;

        $overlap = min($startupMax, $corpMax) - max($startupMin, $corpMin);

        if ($overlap >= 0) return 10;
        return 3;
    }

    private function scoreLocation(StartupProfile $sp, CorporateProfile $cp): float
    {
        if ($sp->city && $cp->city && strtolower($sp->city) === strtolower($cp->city)) return 10;
        if ($sp->state && $cp->state && strtolower($sp->state) === strtolower($cp->state)) return 6;
        if ($sp->country && $cp->country && strtolower($sp->country) === strtolower($cp->country)) return 3;
        return 0;
    }

    private function getLabel(int $score): string
    {
        if ($score >= 75) return 'Excellent Match';
        if ($score >= 50) return 'Good Match';
        if ($score >= 25) return 'Fair Match';
        return 'Low Match';
    }

    private function getColor(int $score): string
    {
        if ($score >= 75) return 'green';
        if ($score >= 50) return 'blue';
        if ($score >= 25) return 'yellow';
        return 'red';
    }
}
