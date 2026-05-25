<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Connection;
use App\Models\Message;
use App\Models\Milestone;
use App\Models\User;

class BadgeService
{
    public function checkAndAward(User $user): array
    {
        $awarded = [];
        $badges = Badge::all();

        foreach ($badges as $badge) {
            if ($user->badges()->where('badges.id', $badge->id)->exists()) continue;

            if ($this->meetsCriteria($user, $badge)) {
                $user->badges()->attach($badge->id, ['awarded_at' => now()]);
                $awarded[] = $badge;

                app(NotificationService::class)->send(
                    $user,
                    'badge',
                    "Badge Unlocked: {$badge->name}",
                    $badge->description,
                    null,
                    'trophy'
                );
            }
        }

        return $awarded;
    }

    private function meetsCriteria(User $user, Badge $badge): bool
    {
        return match($badge->criteria_type) {
            'connections' => $user->getAllConnections()->count() >= $badge->criteria_value,
            'messages' => Message::where('sender_id', $user->id)->count() >= $badge->criteria_value,
            'milestones' => $user->isStartup() ? Milestone::where('startup_profile_id', $user->startupProfile?->id)->count() >= $badge->criteria_value : false,
            'profile_complete' => $this->isProfileComplete($user),
            default => false,
        };
    }

    private function isProfileComplete(User $user): bool
    {
        $profile = $user->profile();
        if (!$profile) return false;

        if ($user->isStartup()) {
            return $profile->company_name && $profile->industry_id && $profile->elevator_pitch && $profile->stage;
        }

        return $profile->company_name && $profile->industry_id && $profile->problem_statement;
    }
}
