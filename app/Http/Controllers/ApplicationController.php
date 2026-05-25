<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeApplication;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function availableChallenges(Request $request)
    {
        $query = Challenge::with('corporate.corporateProfile', 'industry')
            ->where('status', 'open')
            ->where('deadline', '>=', now());

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            });
        }

        if ($request->filled('industry_id')) {
            $query->where('industry_id', $request->industry_id);
        }

        $challenges = $query->latest()->paginate(9);
        $industries = \App\Models\Industry::orderBy('name')->get();
        $appliedIds = auth()->user()->applications()->pluck('challenge_id')->toArray();

        return view('startup.challenges', compact('challenges', 'industries', 'appliedIds'));
    }

    public function create(Challenge $challenge)
    {
        if (!$challenge->isOpen()) {
            return back()->withErrors(['challenge' => 'This challenge is no longer accepting applications.']);
        }
        if (auth()->user()->applications()->where('challenge_id', $challenge->id)->exists()) {
            return redirect()->route('startup.applications')->with('info', 'You have already applied to this challenge.');
        }
        $challenge->load('corporate.corporateProfile', 'industry');
        return view('startup.apply', compact('challenge'));
    }

    public function store(Request $request, Challenge $challenge, NotificationService $notificationService)
    {
        $validated = $request->validate([
            'cover_letter' => ['required', 'string', 'min:50', 'max:2000'],
            'approach' => ['nullable', 'string', 'max:3000'],
            'proposal_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        if (auth()->user()->applications()->where('challenge_id', $challenge->id)->exists()) {
            return back()->withErrors(['error' => 'Already applied.']);
        }

        if ($request->hasFile('proposal_file')) {
            $validated['proposal_file'] = $request->file('proposal_file')->store('proposals', 'public');
        }

        $validated['challenge_id'] = $challenge->id;
        $validated['startup_id'] = auth()->id();

        ChallengeApplication::create($validated);
        $notificationService->notifyApplication($challenge->corporate, auth()->user(), $challenge->title);

        return redirect()->route('startup.applications')->with('success', 'Application submitted!');
    }

    public function myApplications()
    {
        $applications = auth()->user()->applications()->with('challenge.corporate.corporateProfile', 'challenge.industry')->latest()->paginate(10);
        return view('startup.applications', compact('applications'));
    }
}
