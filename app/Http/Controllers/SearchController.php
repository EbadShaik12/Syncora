<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\User;
use App\Services\CompatibilityScoreService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, CompatibilityScoreService $scoreService)
    {
        $user       = auth()->user();
        $targetRole = $user->isStartup() ? 'corporate' : 'startup';
        $industries = Industry::orderBy('name')->get();

        $query = User::where('role', $targetRole)->where('status', 'approved');

        // Keyword search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q, $targetRole) {
                $relation = $targetRole === 'startup' ? 'startupProfile' : 'corporateProfile';
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhereHas($relation, fn($r) => $r->where('company_name', 'like', "%{$q}%")
                        ->orWhere('elevator_pitch', 'like', "%{$q}%")
                        ->orWhere('problem_statement', 'like', "%{$q}%"));
            });
        }

        // Industry filter
        if ($request->filled('industry_id')) {
            $relation = $targetRole === 'startup' ? 'startupProfile' : 'corporateProfile';
            $query->whereHas($relation, fn($r) => $r->where('industry_id', $request->industry_id));
        }

        // City filter
        if ($request->filled('city')) {
            $relation = $targetRole === 'startup' ? 'startupProfile' : 'corporateProfile';
            $query->whereHas($relation, fn($r) => $r->where('city', 'like', '%' . $request->city . '%'));
        }

        // Stage filter (startups only)
        if ($request->filled('stage') && $targetRole === 'startup') {
            $query->whereHas('startupProfile', fn($r) => $r->where('stage', $request->stage));
        }

        // Budget range filter (corporates only)
        if ($targetRole === 'corporate') {
            if ($request->filled('budget_min')) {
                $query->whereHas('corporateProfile', fn($r) => $r->where('budget_max', '>=', $request->budget_min));
            }
            if ($request->filled('budget_max')) {
                $query->whereHas('corporateProfile', fn($r) => $r->where('budget_min', '<=', $request->budget_max));
            }
        }

        // Tech tag filter
        if ($request->filled('tech_tag')) {
            $tag = strtolower($request->tech_tag);
            if ($targetRole === 'startup') {
                $query->whereHas('startupProfile', fn($r) => $r->whereJsonContains('tech_tags', $tag));
            } else {
                $query->whereHas('corporateProfile', fn($r) => $r->whereJsonContains('seeking_technologies', $tag));
            }
        }

        $results = $query->with(['startupProfile.industry', 'corporateProfile.industry'])->paginate(12);

        // Attach compatibility scores
        $results->getCollection()->transform(function ($candidate) use ($user, $scoreService) {
            $startup   = $user->isStartup() ? $user : $candidate;
            $corporate = $user->isCorporate() ? $user : $candidate;
            $candidate->compatibility = $scoreService->calculate($startup, $corporate);
            return $candidate;
        });

        // Sort by score if requested
        if ($request->get('sort') === 'score') {
            $sorted = $results->getCollection()->sortByDesc(fn($c) => $c->compatibility['score'])->values();
            $results->setCollection($sorted);
        }

        // Collect all tags for filter chips (from current result set)
        $allTags = $results->getCollection()->flatMap(function ($r) use ($targetRole) {
            if ($targetRole === 'startup') return $r->startupProfile?->tech_tags ?? [];
            return $r->corporateProfile?->seeking_technologies ?? [];
        })->unique()->sort()->values();

        if ($request->ajax() || $request->has('ajax')) {
            return view('partials.search-results', compact('results', 'targetRole'));
        }

        return view('search', compact('results', 'industries', 'targetRole', 'allTags'));
    }

    public function suggestions(Request $request)
    {
        $q = $request->q;
        if (strlen($q) < 2) return response()->json([]);

        $user       = auth()->user();
        $targetRole = $user->isStartup() ? 'corporate' : 'startup';

        $users = User::where('role', $targetRole)
            ->where('status', 'approved')
            ->where(function ($sub) use ($q, $targetRole) {
                $relation = $targetRole === 'startup' ? 'startupProfile' : 'corporateProfile';
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhereHas($relation, fn($r) => $r->where('company_name', 'like', "%{$q}%"));
            })
            ->limit(6)
            ->get()
            ->map(fn($u) => [
                'id'     => $u->id,
                'name'   => $u->companyName(),
                'avatar' => $u->logoUrl(),
                'role'   => $u->role,
                'url'    => route('profile.show', $u->id),
                'industry' => $u->profile()?->industry?->name ?? ucfirst($u->role),
            ]);

        return response()->json($users);
    }
}
