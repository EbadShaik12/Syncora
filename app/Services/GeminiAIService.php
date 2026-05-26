<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GeminiAIService
 *
 * Calls the Google Gemini API (gemini-1.5-flash) to produce:
 *  - AI-powered compatibility scores between a startup and a corporate
 *  - AI-powered leaderboard ranking scores for startups and corporates
 *
 * Results are cached for 1 hour to avoid excessive API calls.
 */
class GeminiAIService
{
    private string $apiKey;
    private string $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY', ''));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PUBLIC METHODS
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * AI-powered compatibility analysis between a startup and a corporate.
     *
     * Returns an array:
     *  [
     *    'ai_score'          => int (0-100),
     *    'ai_label'          => string,
     *    'ai_summary'        => string,
     *    'ai_strengths'      => string[],
     *    'ai_risks'          => string[],
     *    'ai_recommendation' => string,
     *    'ai_roi_estimate'   => string,
     *  ]
     */
    public function analyzeCompatibility(array $startupData, array $corporateData): array
    {
        $cacheKey = 'gemini_compat_' . md5(json_encode($startupData) . json_encode($corporateData));

        return Cache::remember($cacheKey, now()->addHour(), function () use ($startupData, $corporateData) {
            $prompt = $this->buildCompatibilityPrompt($startupData, $corporateData);
            $raw    = $this->callGemini($prompt);

            return $this->parseCompatibilityResponse($raw);
        });
    }

    /**
     * AI-powered ranking score for a startup.
     *
     * Returns an array:
     *  [
     *    'ai_rank_score'    => int (0-200 bonus points added on top of base score),
     *    'ai_potential'     => string ('High' | 'Medium' | 'Low'),
     *    'ai_insight'       => string (one-line reason),
     *    'ai_growth_factor' => float (1.0–3.0 multiplier label),
     *  ]
     */
    public function rankStartup(array $startupData, array $metrics): array
    {
        $cacheKey = 'gemini_rank_startup_' . md5(json_encode($startupData) . json_encode($metrics));

        return Cache::remember($cacheKey, now()->addHour(), function () use ($startupData, $metrics) {
            $prompt = $this->buildStartupRankPrompt($startupData, $metrics);
            $raw    = $this->callGemini($prompt);

            return $this->parseRankResponse($raw, 'startup');
        });
    }

    /**
     * AI-powered ranking score for a corporate.
     *
     * Returns the same shape as rankStartup().
     */
    public function rankCorporate(array $corporateData, array $metrics): array
    {
        $cacheKey = 'gemini_rank_corporate_' . md5(json_encode($corporateData) . json_encode($metrics));

        return Cache::remember($cacheKey, now()->addHour(), function () use ($corporateData, $metrics) {
            $prompt = $this->buildCorporateRankPrompt($corporateData, $metrics);
            $raw    = $this->callGemini($prompt);

            return $this->parseRankResponse($raw, 'corporate');
        });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PROMPT BUILDERS
    // ──────────────────────────────────────────────────────────────────────────

    private function buildCompatibilityPrompt(array $s, array $c): string
    {
        $startupTech      = implode(', ', $s['tech_tags']      ?? []);
        $startupSeeking   = implode(', ', $s['seeking']        ?? []);
        $corporateTech    = implode(', ', $c['seeking_tech']   ?? []);
        $corporatePartner = implode(', ', $c['partnership_types'] ?? []);

        return <<<PROMPT
You are an expert venture analyst and startup-corporate partnership evaluator.

Analyze the following startup-corporate pairing and return a structured JSON response.

--- STARTUP ---
Company: {$s['name']}
Industry: {$s['industry']}
Stage: {$s['stage']}
Tech Stack: {$startupTech}
Partnership Seeking: {$startupSeeking}
Elevator Pitch: {$s['pitch']}
City: {$s['city']}
Team Size: {$s['team_size']}
Funding Status: {$s['funding_status']}

--- CORPORATE ---
Company: {$c['name']}
Industry: {$c['industry']}
Company Size: {$c['size']}
Seeking Technologies: {$corporateTech}
Partnership Types: {$corporatePartner}
City: {$c['city']}

Based on industry alignment, technology compatibility, partnership intent match, stage appropriateness, and geographic proximity, analyze this pairing.

Return ONLY valid JSON (no markdown, no explanation outside JSON):
{
  "ai_score": <integer 0-100>,
  "ai_label": "<Excellent Match|Good Match|Fair Match|Low Match>",
  "ai_summary": "<2-3 sentence analysis>",
  "ai_strengths": ["<strength 1>", "<strength 2>", "<strength 3>"],
  "ai_risks": ["<risk 1>", "<risk 2>"],
  "ai_recommendation": "<1 actionable sentence>",
  "ai_roi_estimate": "<e.g. '2.5x in 18 months'>"
}
PROMPT;
    }

    private function buildStartupRankPrompt(array $s, array $m): string
    {
        $techTags = implode(', ', $s['tech_tags'] ?? []);

        return <<<PROMPT
You are an expert startup analyst evaluating performance and potential for a leaderboard ranking system.

--- STARTUP PROFILE ---
Company: {$s['name']}
Industry: {$s['industry']}
Stage: {$s['stage']}
Tech Stack: {$techTags}
Elevator Pitch: {$s['pitch']}
City: {$s['city']}
Team Size: {$s['team_size']}
Funding Status: {$s['funding_status']}
Profile Completeness: {$s['completeness']}%

--- PLATFORM METRICS ---
Connections: {$m['connections']}
Challenge Applications: {$m['applications']}
Shortlisted Applications: {$m['shortlisted']}
Badges Earned: {$m['badges']}
Base Score (formula-based): {$m['base_score']}

Evaluate this startup's overall platform performance and growth potential.
Consider: engagement quality, growth stage momentum, profile quality, tech relevance, and accomplishment metrics.

Return ONLY valid JSON (no markdown, no explanation outside JSON):
{
  "ai_rank_score": <integer 0-100, bonus points to add to base score>,
  "ai_potential": "<High|Medium|Low>",
  "ai_insight": "<one insightful sentence about why this startup ranks here>",
  "ai_growth_factor": <float between 1.0 and 3.0>
}
PROMPT;
    }

    private function buildCorporateRankPrompt(array $c, array $m): string
    {
        $partnerTypes = implode(', ', $c['partnership_types'] ?? []);
        $seekingTech  = implode(', ', $c['seeking_tech']      ?? []);

        return <<<PROMPT
You are an expert corporate engagement analyst evaluating a company's performance on a startup-corporate matchmaking platform.

--- CORPORATE PROFILE ---
Company: {$c['name']}
Industry: {$c['industry']}
Company Size: {$c['size']}
Partnership Types Offered: {$partnerTypes}
Technologies Seeking: {$seekingTech}
City: {$c['city']}

--- PLATFORM METRICS ---
Challenges Posted: {$m['challenges']}
Total Applications Received: {$m['total_apps']}
Startups Shortlisted: {$m['shortlisted']}
Active Connections: {$m['connections']}
Badges Earned: {$m['badges']}
Base Score (formula-based): {$m['base_score']}

Evaluate this corporate's engagement quality, challenge quality, and startup ecosystem contribution.
Consider: challenge diversity, application conversion rates, connection depth, and industry influence.

Return ONLY valid JSON (no markdown, no explanation outside JSON):
{
  "ai_rank_score": <integer 0-100, bonus points to add to base score>,
  "ai_potential": "<High|Medium|Low>",
  "ai_insight": "<one insightful sentence about why this corporate ranks here>",
  "ai_growth_factor": <float between 1.0 and 3.0>
}
PROMPT;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API CALL
    // ──────────────────────────────────────────────────────────────────────────

    private function callGemini(string $prompt): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('GeminiAIService: No API key configured.');
            return null;
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->endpoint}?key={$this->apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.3,
                        'maxOutputTokens' => 512,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('GeminiAIService: API request failed.', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        } catch (\Throwable $e) {
            Log::error('GeminiAIService: Exception during API call.', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // RESPONSE PARSERS
    // ──────────────────────────────────────────────────────────────────────────

    private function parseCompatibilityResponse(?string $raw): array
    {
        $defaults = [
            'ai_score'          => 0,
            'ai_label'          => 'Analyzing…',
            'ai_summary'        => 'AI analysis is unavailable at the moment.',
            'ai_strengths'      => [],
            'ai_risks'          => [],
            'ai_recommendation' => 'Complete your profile for a better match.',
            'ai_roi_estimate'   => 'N/A',
        ];

        if (!$raw) return $defaults;

        try {
            $json = json_decode($this->extractJson($raw), true, 512, JSON_THROW_ON_ERROR);

            return [
                'ai_score'          => (int)   ($json['ai_score']          ?? 0),
                'ai_label'          => (string) ($json['ai_label']          ?? $defaults['ai_label']),
                'ai_summary'        => (string) ($json['ai_summary']        ?? $defaults['ai_summary']),
                'ai_strengths'      => (array)  ($json['ai_strengths']      ?? []),
                'ai_risks'          => (array)  ($json['ai_risks']          ?? []),
                'ai_recommendation' => (string) ($json['ai_recommendation'] ?? $defaults['ai_recommendation']),
                'ai_roi_estimate'   => (string) ($json['ai_roi_estimate']   ?? $defaults['ai_roi_estimate']),
            ];
        } catch (\Throwable $e) {
            Log::warning('GeminiAIService: Failed to parse compatibility response.', ['raw' => $raw]);
            return $defaults;
        }
    }

    private function parseRankResponse(?string $raw, string $type): array
    {
        $defaults = [
            'ai_rank_score'    => 0,
            'ai_potential'     => 'Medium',
            'ai_insight'       => 'AI ranking analysis pending.',
            'ai_growth_factor' => 1.0,
        ];

        if (!$raw) return $defaults;

        try {
            $json = json_decode($this->extractJson($raw), true, 512, JSON_THROW_ON_ERROR);

            return [
                'ai_rank_score'    => min(100, max(0, (int)   ($json['ai_rank_score']    ?? 0))),
                'ai_potential'     => (string) ($json['ai_potential']     ?? 'Medium'),
                'ai_insight'       => (string) ($json['ai_insight']       ?? $defaults['ai_insight']),
                'ai_growth_factor' => (float)  ($json['ai_growth_factor'] ?? 1.0),
            ];
        } catch (\Throwable $e) {
            Log::warning("GeminiAIService: Failed to parse {$type} rank response.", ['raw' => $raw]);
            return $defaults;
        }
    }

    /**
     * Strip markdown fences if the model wraps JSON in ```json ... ``` blocks.
     */
    private function extractJson(string $raw): string
    {
        $raw = trim($raw);
        // Remove ```json ... ``` or ``` ... ``` fences
        if (preg_match('/```(?:json)?\s*([\s\S]+?)\s*```/i', $raw, $m)) {
            return $m[1];
        }
        return $raw;
    }
}
