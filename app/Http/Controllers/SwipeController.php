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

        if ($user->isStartup()) {
            // Get challenge IDs already swiped on by this startup
            $swipedChallengeIds = InterestSignal::where('sender_id', $user->id)
                ->whereNotNull('challenge_id')
                ->pluck('challenge_id')
                ->toArray();

            // Get challenges already applied to (excluding those that were rejected, so they can reappear)
            $appliedChallengeIds = \App\Models\ChallengeApplication::where('startup_id', $user->id)
                ->whereIn('status', ['pending', 'shortlisted'])
                ->pluck('challenge_id')
                ->toArray();

            $excludeIds = array_merge($swipedChallengeIds, $appliedChallengeIds);

            // Get open challenges from approved corporates
            $candidates = \App\Models\Challenge::where('status', 'open')
                ->whereNotIn('id', $excludeIds)
                ->whereHas('corporate', function ($query) {
                    $query->where('status', 'approved');
                })
                ->with(['corporate', 'industry'])
                ->limit(20)
                ->get();

            // Calculate compatibility score for each candidate (corporate)
            $cards = $candidates->map(function ($challenge) use ($user, $scoreService) {
                $corporate = $challenge->corporate;
                $score = $scoreService->calculate($user, $corporate);

                return [
                    'challenge' => $challenge,
                    'user' => $corporate, // to remain compatible with template references to $card['user']
                    'profile' => $corporate->profile(),
                    'score' => $score,
                ];
            })->sortByDesc('score.score')->values();
        } else {
            // General Corporate user swiping on Startup candidates
            $swipedIds = InterestSignal::where('sender_id', $user->id)
                ->whereNull('challenge_id')
                ->pluck('receiver_id')
                ->toArray();

            $candidates = User::where('role', 'startup')
                ->where('status', 'approved')
                ->where('id', '!=', $user->id)
                ->whereNotIn('id', $swipedIds)
                ->with(['startupProfile.industry'])
                ->limit(20)
                ->get();

            $cards = $candidates->map(function ($candidate) use ($user, $scoreService) {
                $score = $scoreService->calculate($candidate, $user);

                return [
                    'user' => $candidate,
                    'profile' => $candidate->profile(),
                    'score' => $score,
                ];
            })->sortByDesc('score.score')->values();
        }

        return view('swipe', compact('cards'));
    }

    public function swipe(Request $request, NotificationService $notificationService)
    {
        $validated = $request->validate([
            'target_id' => ['required', 'integer'], // Can be user_id (for corporate) or challenge_id (for startup)
            'action' => ['required', 'in:interested,skipped'],
            'is_challenge' => ['nullable', 'boolean'],
        ]);

        $user = auth()->user();
        $targetId = $validated['target_id'];
        $isChallenge = $validated['is_challenge'] ?? false;

        $isMatch = false;
        $connection = null;
        $targetName = '';

        if ($isChallenge) {
            $challenge = \App\Models\Challenge::findOrFail($targetId);
            $corporate = $challenge->corporate;
            $targetName = $challenge->title;

            // Store interest signal for this specific challenge
            $signal = InterestSignal::updateOrCreate(
                ['sender_id' => $user->id, 'receiver_id' => $corporate->id, 'challenge_id' => $challenge->id],
                ['status' => $validated['action']]
            );

            // If startup swiped interested, immediately create a challenge application with 'pending' status!
            if ($validated['action'] === 'interested') {
                \App\Models\ChallengeApplication::firstOrCreate(
                    ['challenge_id' => $challenge->id, 'startup_id' => $user->id],
                    [
                        'cover_letter' => "Swiped right on your LinkedIn post! 🚀 We are excited to collaborate and solve this challenge.",
                        'approach' => "Will provide detailed approach upon connection.",
                        'status' => 'pending'
                    ]
                );

                // Also notify the Corporate user!
                \App\Models\Notification::create([
                    'user_id' => $corporate->id,
                    'type' => 'application',
                    'title' => 'New Swipe Application! 🚀',
                    'body' => "{$user->companyName()} swiped right on your challenge '{$challenge->title}'!",
                    'link' => route('corporate.challenges.applications', $challenge),
                ]);

                // Check if the Corporate already swiped 'interested' on this Startup!
                $reverseSignal = InterestSignal::where('sender_id', $corporate->id)
                    ->where('receiver_id', $user->id)
                    ->where('status', 'interested')
                    ->first();

                if ($reverseSignal) {
                    $userOneId = min($user->id, $corporate->id);
                    $userTwoId = max($user->id, $corporate->id);

                    $connection = Connection::firstOrCreate(
                        ['user_one_id' => $userOneId, 'user_two_id' => $userTwoId],
                        ['matched_at' => now(), 'status' => 'active']
                    );

                    $isMatch = true;
                    $notificationService->notifyMatch($user, $corporate);

                    // Dynamic Badge Award Trigger
                    app(\App\Services\BadgeService::class)->checkAndAward($user);
                    app(\App\Services\BadgeService::class)->checkAndAward($corporate);
                }
            }
        } else {
            // General user-to-user swipe (Corporate swiping on Startup):
            if ($targetId == $user->id) {
                return response()->json(['error' => 'Cannot swipe on yourself'], 422);
            }

            $signal = InterestSignal::updateOrCreate(
                ['sender_id' => $user->id, 'receiver_id' => $targetId, 'challenge_id' => null],
                ['status' => $validated['action']]
            );

            $targetUser = User::find($targetId);
            $targetName = $targetUser?->companyName();

            if ($validated['action'] === 'interested') {
                $reverseSignal = InterestSignal::where('sender_id', $targetId)
                    ->where('receiver_id', $user->id)
                    ->where('status', 'interested')
                    ->first();

                if ($reverseSignal) {
                    $userOneId = min($user->id, $targetId);
                    $userTwoId = max($user->id, $targetId);

                    $connection = Connection::firstOrCreate(
                        ['user_one_id' => $userOneId, 'user_two_id' => $userTwoId],
                        ['matched_at' => now(), 'status' => 'active']
                    );

                    $isMatch = true;
                    $notificationService->notifyMatch($user, $targetUser);

                    app(\App\Services\BadgeService::class)->checkAndAward($user);
                    app(\App\Services\BadgeService::class)->checkAndAward($targetUser);
                }
            }
        }

        return response()->json([
            'success' => true,
            'matched' => $isMatch,
            'connection_id' => $connection?->id,
            'target_name' => $targetName,
        ]);
    }

    public function reset()
    {
        $user = auth()->user();

        if ($user->isStartup()) {
            // Delete all interest signals (swipes) for this startup
            InterestSignal::where('sender_id', $user->id)->delete();

            // Also delete challenge applications that are rejected,
            // so they can apply/swipe on them again! Do NOT delete active ones.
            \App\Models\ChallengeApplication::where('startup_id', $user->id)
                ->where('status', 'rejected')
                ->delete();

            return redirect()->route('startup.swipe')
                ->with('success', 'Queue reset! Fresh challenges are ready for you.');
        } else {
            // Corporate: delete non-matched swipes only
            $connectedUserIds = Connection::where(function ($query) use ($user) {
                $query->where('user_one_id', $user->id)
                      ->orWhere('user_two_id', $user->id);
            })
            ->get()
            ->map(function ($conn) use ($user) {
                return $conn->otherUser($user->id)->id;
            })
            ->toArray();

            InterestSignal::where('sender_id', $user->id)
                ->whereNull('challenge_id')
                ->whereNotIn('receiver_id', $connectedUserIds)
                ->delete();

            return redirect()->route('corporate.swipe')
                ->with('success', 'Queue reset! Fresh startups are ready for you.');
        }
    }
}
