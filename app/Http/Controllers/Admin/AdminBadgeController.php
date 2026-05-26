<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::withCount('users')->orderBy('name')->get();
        $users  = User::where('role', '!=', 'admin')->where('status', 'approved')
                      ->with('startupProfile', 'corporateProfile')->get();
        return view('admin.badges', compact('badges', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100', 'unique:badges,name'],
            'description'    => ['required', 'string', 'max:500'],
            'icon'           => ['nullable', 'string', 'max:10'],
            'color'          => ['nullable', 'string', 'max:50'],
            'criteria_type'  => ['required', 'in:manual,connections,applications,swipes'],
            'criteria_value' => ['nullable', 'integer', 'min:1'],
        ]);

        Badge::create([
            'name'           => $validated['name'],
            'slug'           => Str::slug($validated['name']),
            'description'    => $validated['description'],
            'icon'           => $validated['icon'] ?? '⭐',
            'color'          => $validated['color'] ?? 'from-yellow-400 to-orange-500',
            'criteria_type'  => $validated['criteria_type'],
            'criteria_value' => $validated['criteria_value'] ?? null,
        ]);

        return redirect()->back()->with('success', "Badge \"{$validated['name']}\" created!");
    }

    public function award(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($user->badges()->where('badge_id', $badge->id)->exists()) {
            return redirect()->back()->with('error', "{$user->companyName()} already has this badge.");
        }

        $user->badges()->attach($badge->id, ['awarded_at' => now()]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'milestone',
            'title'   => "🏅 New Badge: {$badge->name}!",
            'body'    => "Congratulations! You've been awarded the \"{$badge->name}\" badge by the Syncora admin team. {$badge->description}",
        ]);

        return redirect()->back()->with('success', "Badge awarded to {$user->companyName()} successfully!");
    }

    public function revoke(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->badges()->detach($badge->id);

        return redirect()->back()->with('success', "Badge revoked from {$user->companyName()}.");
    }

    public function destroy(Badge $badge)
    {
        $badge->users()->detach();
        $badge->delete();
        return redirect()->back()->with('success', "Badge deleted.");
    }
}
