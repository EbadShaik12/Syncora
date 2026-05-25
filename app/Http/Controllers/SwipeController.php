<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\InterestSignal;
use App\Models\User;
use App\Services\CompatibilityScoreService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SwipeController extends Controller
{
    public function index(CompatibilityScoreService $scoreService)
    {
        $user = auth()->user();
        $targetRole = $user->isStartup() ? 'corporate' : 'startup';

        // Get users already swiped on
        $swipedIds = InterestSignal::where('sender_id', $user->id)->pluck('receiver_id')->toArray();

        // Get candidates
        $candidates = User::where('role', $targetRole)
            ->where('status', 'approved')
            ->where('id', '!=', $user->id)
            ->whereNotIn('id', $swipedIds)
            ->with(['startupProfile.industry', 'corporateProfile.industry'])
            ->limit(20)
            ->get();

        // Calculate score for each
        $cards = $candidates->map(function ($candidate) use ($user, $scoreService) {
            $startup = $user->isStartup() ? $user : $candidate;
            $corporate = $user->isCorporate() ? $user : $candidate;
            $score = $scoreService->calculate($startup, $corporate);

            return [
                'user' => $candidate,
                'profile' => $candidate->profile(),
                'score' => $score,
            ];
        })->sortByDesc('score.score')->values();

        return view('swipe', compact('cards'));
    }

    public function swipe(Request $request, NotificationService $notificationService)
    {
        $validated = $request->validate([
            'target_id' => ['required', 'exists:users,id'],
            'action' => ['required', 'in:interested,skipped'],
        ]);

        $user = auth()->user();
        $targetId = $validated['target_id'];

        if ($targetId == $user->id) {
            return response()->json(['error' => 'Cannot swipe on yourself'], 422);
        }

        // Store or update signal
        $signal = InterestSignal::updateOrCreate(
            ['sender_id' => $user->id, 'receiver_id' => $targetId],
            ['status' => $validated['action']]
        );

        $isMatch = false;
        $connection = null;

        // Check for mutual interest if this was an "interested" swipe
        if ($validated['action'] === 'interested') {
            $reverseSignal = InterestSignal::where('sender_id', $targetId)
                ->where('receiver_id', $user->id)
                ->where('status', 'interested')
                ->first();

            if ($reverseSignal) {
                // Create connection (smaller id first for consistency)
                $userOneId = min($user->id, $targetId);
                $userTwoId = max($user->id, $targetId);

                $connection = Connection::firstOrCreate(
                    ['user_one_id' => $userOneId, 'user_two_id' => $userTwoId],
                    ['matched_at' => now(), 'status' => 'active']
                );

                $isMatch = true;
                $targetUser = User::find($targetId);
                $notificationService->notifyMatch($user, $targetUser);

                // Dynamic Badge Award Trigger
                app(\App\Services\BadgeService::class)->checkAndAward($user);
                app(\App\Services\BadgeService::class)->checkAndAward($targetUser);
            }
        }

        return response()->json([
            'success' => true,
            'matched' => $isMatch,
            'connection_id' => $connection?->id,
            'target_name' => User::find($targetId)?->companyName(),
        ]);
    }

    public function reset()
    {
        $user = auth()->user();

        // Get connected user IDs to keep mutual swipes intact
        $connectedUserIds = Connection::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                  ->orWhere('user_two_id', $user->id);
        })
        ->get()
        ->map(function ($conn) use ($user) {
            return $conn->otherUser($user->id)->id;
        })
        ->toArray();

        // Delete all interest signals sent by the user, excluding those with active connections
        InterestSignal::where('sender_id', $user->id)
            ->whereNotIn('receiver_id', $connectedUserIds)
            ->delete();

        return redirect()->back()->with('success', 'Your swipe queue has been reset successfully!');
    }
}
