<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlaggedContent;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = FlaggedContent::with(['reporter', 'flaggable', 'reviewer'])
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        $flags = $query->paginate(20)->withQueryString();

        $pendingCount  = FlaggedContent::where('status', 'pending')->count();
        $reviewedCount = FlaggedContent::where('status', 'reviewed')->count();
        $dismissedCount = FlaggedContent::where('status', 'dismissed')->count();

        return view('admin.moderation', compact('flags', 'pendingCount', 'reviewedCount', 'dismissedCount'));
    }

    public function flag(Request $request)
    {
        $validated = $request->validate([
            'flaggable_type' => ['required', 'string'],
            'flaggable_id'   => ['required', 'integer'],
            'reason'         => ['required', 'in:spam,inappropriate,fake,harassment,other'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $typeMap = [
            'user'      => \App\Models\User::class,
            'challenge' => \App\Models\Challenge::class,
        ];

        FlaggedContent::create([
            'reporter_id'    => auth()->id(),
            'flaggable_type' => $typeMap[$validated['flaggable_type']] ?? $validated['flaggable_type'],
            'flaggable_id'   => $validated['flaggable_id'],
            'reason'         => $validated['reason'],
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()->back()->with('success', 'Content flagged for admin review.');
    }

    public function review(Request $request, FlaggedContent $flag)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:reviewed,dismissed'],
        ]);

        $flag->update([
            'status'      => $validated['action'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', "Flag marked as {$validated['action']}.");
    }

    public function banUser(Request $request, FlaggedContent $flag)
    {
        if ($flag->flaggable_type === User::class || $flag->flaggable_type === 'App\\Models\\User') {
            $user = User::find($flag->flaggable_id);
            if ($user) {
                $user->update(['status' => 'suspended']);
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'milestone',
                    'title'   => 'Account Suspended',
                    'body'    => 'Your account has been suspended due to a policy violation. Contact support to appeal.',
                ]);
            }
        }

        $flag->update([
            'status'      => 'reviewed',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'User suspended and flag resolved.');
    }
}
