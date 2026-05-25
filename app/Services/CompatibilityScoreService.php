<?php

namespace App\Services;

use App\Models\User;
use App\Models\StartupProfile;
use App\Models\CorporateProfile;

class CompatibilityScoreService
{
    /**
     * Calculate compatibility score between a startup and corporate user.
     * Returns weighted score 0-100.
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

        $total = $industryScore + $techScore + $partnershipScore + $stageScore + $budgetScore + $locationScore;
        $total = round($total);

        return [
            'score' => $total,
            'breakdown' => [
                'industry' => ['score' => $industryScore, 'max' => 25, 'label' => 'Industry Match'],
                'technology' => ['score' => $techScore, 'max' => 20, 'label' => 'Tech Overlap'],
                'partnership' => ['score' => $partnershipScore, 'max' => 20, 'label' => 'Partnership Type'],
                'stage' => ['score' => $stageScore, 'max' => 15, 'label' => 'Stage Fit'],
                'budget' => ['score' => $budgetScore, 'max' => 10, 'label' => 'Budget Range'],
                'location' => ['score' => $locationScore, 'max' => 10, 'label' => 'Location'],
            ],
            'label' => $this->getLabel($total),
            'color' => $this->getColor($total),
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
