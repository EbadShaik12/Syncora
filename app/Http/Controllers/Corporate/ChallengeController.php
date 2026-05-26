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
            'attachment'    => ['nullable', 'file', 'mimes:ppt,pptx,pdf,doc,docx,jpeg,png,jpg,gif,svg,webp', 'max:20480'],
        ]);

        $tags = [];
        if (!empty($validated['required_tags'])) {
            $tags = array_filter(array_map('trim', explode(',', $validated['required_tags'])));
        }

        $attachmentPath = null;
        $attachmentFilename = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentFilename = $file->getClientOriginalName();
            $attachmentPath = $file->store('challenges/attachments', 'public');
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
            'attachment_path' => $attachmentPath,
            'attachment_filename' => $attachmentFilename,
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
            'attachment'    => ['nullable', 'file', 'mimes:ppt,pptx,pdf,doc,docx,jpeg,png,jpg,gif,svg,webp', 'max:20480'],
        ]);

        $tags = [];
        if (!empty($validated['required_tags'])) {
            $tags = array_filter(array_map('trim', explode(',', $validated['required_tags'])));
        }

        $attachmentPath = $challenge->attachment_path;
        $attachmentFilename = $challenge->attachment_filename;
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($challenge->attachment_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($challenge->attachment_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($challenge->attachment_path);
            }
            $file = $request->file('attachment');
            $attachmentFilename = $file->getClientOriginalName();
            $attachmentPath = $file->store('challenges/attachments', 'public');
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
            'attachment_path' => $attachmentPath,
            'attachment_filename' => $attachmentFilename,
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
            'body'    => "Your proposal for challenge '{$application->challenge->title}' has been shortlisted by the corporate partner!",
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
            'body'    => "We regret to inform you that your proposal for '{$application->challenge->title}' was not selected.",
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
