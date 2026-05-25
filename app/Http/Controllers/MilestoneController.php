<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'milestone_date' => ['required', 'date'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $profile = auth()->user()->startupProfile;
        $validated['startup_profile_id'] = $profile->id;
        $validated['icon'] = $validated['icon'] ?? 'rocket';
        $validated['sort_order'] = $profile->milestones()->max('sort_order') + 1;

        Milestone::create($validated);

        // Dynamic Badge Award Trigger
        app(\App\Services\BadgeService::class)->checkAndAward(auth()->user());

        return back()->with('success', 'Milestone added!');
    }

    public function destroy(Milestone $milestone)
    {
        if ($milestone->startupProfile->user_id !== auth()->id()) abort(403);
        $milestone->delete();
        return back()->with('success', 'Milestone removed.');
    }
}
