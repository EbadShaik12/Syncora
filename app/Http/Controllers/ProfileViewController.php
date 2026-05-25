<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use App\Services\CompatibilityScoreService;

class ProfileViewController extends Controller
{
    public function show(User $user, CompatibilityScoreService $scoreService)
    {
        $user->load(
            'startupProfile.industry',
            'startupProfile.milestones',
            'corporateProfile.industry',
            'badges'
        );

        $viewer        = auth()->user();
        $compatibility = null;

        if (($viewer->isStartup() && $user->isCorporate()) || ($viewer->isCorporate() && $user->isStartup())) {
            $startup       = $viewer->isStartup() ? $viewer : $user;
            $corporate     = $viewer->isCorporate() ? $viewer : $user;
            $compatibility = $scoreService->calculate($startup, $corporate);
        }

        // Connection status between viewer and this profile
        $connection = Connection::where(function ($q) use ($viewer, $user) {
            $q->where('user_one_id', $viewer->id)->where('user_two_id', $user->id);
        })->orWhere(function ($q) use ($viewer, $user) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $viewer->id);
        })->first();

        return view('profile-show', compact('user', 'compatibility', 'connection'));
    }
}
