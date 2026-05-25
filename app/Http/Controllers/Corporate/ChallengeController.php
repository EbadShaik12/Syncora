<?php

namespace App\Http\Controllers\Corporate;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeApplication;
use App\Models\Industry;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::where('corporate_id', Auth::id())
            ->withCount('applications')
            ->latest()
            ->get();

        return view('corporate.challenges.index', compact('challenges'));
    }

    public function create()
    {
        $industries = Industry::orderBy('name')->get();
        return view('corporate.challenges.create', compact('industries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'industry_id'   => ['required', 'exists:industries,id'],
            'description'   => ['required', 'string'],
            'requirements'  => ['nullable', 'string'],
            'budget_min'    => ['required', 'numeric', 'min:0'],
            'budget_max'    => ['required', 'numeric', 'gte:budget_min'],
            'deadline'      => ['required', 'date', 'after:today'],
            'required_tags' => ['nullable', 'string'],
        ]);

        $tags = [];
        if (!empty($validated['required_tags'])) {
            $tags = array_filter(array_map('trim', explode(',', $validated['required_tags'])));
        }

        Challenge::create([
            'corporate_id'  => Auth::id(),
            'industry_id'   => $validated['industry_id'],
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'requirements'  => $validated['requirements'],
            'budget_min'    => $validated['budget_min'],
            'budget_max'    => $validated['budget_max'],
            'deadline'      => $validated['deadline'],
            'required_tags' => $tags,
            'status'        => 'open',
        ]);

        return redirect()->route('corporate.challenges.index')->with('success', 'Challenge posted successfully!');
    }

    public function show(Challenge $challenge)
    {
        return redirect()->route('corporate.challenges.applications', $challenge);
    }

    public function edit(Challenge $challenge)
    {
        $industries = Industry::orderBy('name')->get();
        return view('corporate.challenges.edit', compact('challenge', 'industries'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'industry_id'   => ['required', 'exists:industries,id'],
            'description'   => ['required', 'string'],
            'requirements'  => ['nullable', 'string'],
            'budget_min'    => ['required', 'numeric', 'min:0'],
            'budget_max'    => ['required', 'numeric', 'gte:budget_min'],
            'deadline'      => ['required', 'date'],
            'required_tags' => ['nullable', 'string'],
            'status'        => ['required', 'in:open,reviewing,closed'],
        ]);

        $tags = [];
        if (!empty($validated['required_tags'])) {
            $tags = array_filter(array_map('trim', explode(',', $validated['required_tags'])));
        }

        $challenge->update([
            'industry_id'   => $validated['industry_id'],
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'requirements'  => $validated['requirements'],
            'budget_min'    => $validated['budget_min'],
            'budget_max'    => $validated['budget_max'],
            'deadline'      => $validated['deadline'],
            'required_tags' => $tags,
            'status'        => $validated['status'],
        ]);

        return redirect()->route('corporate.challenges.index')->with('success', 'Challenge updated successfully!');
    }

    public function destroy(Challenge $challenge)
    {
        $challenge->delete();
        return redirect()->route('corporate.challenges.index')->with('success', 'Challenge deleted successfully.');
    }

    public function applications(Challenge $challenge)
    {
        $applications = $challenge->applications()
            ->with(['startup.startupProfile'])
            ->latest()
            ->get();

        return view('corporate.challenges.applications', compact('challenge', 'applications'));
    }

    public function kanban(Challenge $challenge)
    {
        $applications = $challenge->applications()
            ->with(['startup.startupProfile'])
            ->get();

        return view('corporate.challenges.kanban', compact('challenge', 'applications'));
    }

    public function shortlist(ChallengeApplication $application)
    {
        $application->update(['status' => 'shortlisted']);

        Notification::create([
            'user_id' => $application->startup_id,
            'title'   => 'Proposal Shortlisted! 🚀',
            'message' => "Your proposal for challenge '{$application->challenge->title}' has been shortlisted by the corporate partner!",
            'type'    => 'milestone',
        ]);

        return redirect()->back()->with('success', 'Proposal shortlisted and founder notified!');
    }

    public function rejectApplication(ChallengeApplication $application)
    {
        $application->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $application->startup_id,
            'title'   => 'Proposal Update',
            'message' => "We regret to inform you that your proposal for '{$application->challenge->title}' was not selected.",
            'type'    => 'milestone',
        ]);

        return redirect()->back()->with('warning', 'Application rejected.');
    }

    public function updateStage(Request $request, ChallengeApplication $application)
    {
        $request->validate([
            'status' => ['required', 'in:pending,shortlisted,rejected'],
        ]);

        $application->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }
}
