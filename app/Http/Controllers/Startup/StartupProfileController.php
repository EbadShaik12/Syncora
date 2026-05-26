<?php

namespace App\Http\Controllers\Startup;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\StartupProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StartupProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->startupProfile;
        
        if (!$profile) {
            // Create a blank profile if none exists
            $profile = StartupProfile::create([
                'user_id'      => $user->id,
                'company_name' => $user->name,
            ]);
        }

        $industries = Industry::orderBy('name')->get();
        $milestones = $profile->milestones;

        return view('startup.profile-edit', compact('profile', 'industries', 'milestones'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->startupProfile;

        $validated = $request->validate([
            'company_name'   => ['required', 'string', 'max:255'],
            'industry_id'    => ['required', 'exists:industries,id'],
            'stage'          => ['required', 'in:idea,mvp,growth,scale'],
            'team_size'      => ['nullable', 'integer', 'min:1'],
            'founded_year'   => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'funding_status' => ['required', 'in:bootstrapped,pre_seed,seed,series_a,series_b_plus'],
            'funding_amount' => ['nullable', 'numeric', 'min:0'],
            'elevator_pitch' => ['required', 'string', 'min:20'],
            'tech_tags'      => ['nullable', 'string'],
            'seeking'        => ['nullable', 'array'],
            'seeking.*'      => ['in:investment,pilot,mentorship,acquisition'],
            'budget_min'     => ['nullable', 'numeric', 'min:0'],
            'budget_max'     => ['nullable', 'numeric', 'gte:budget_min'],
            'city'           => ['nullable', 'string', 'max:255'],
            'state'          => ['nullable', 'string', 'max:255'],
            'website'        => ['nullable', 'url'],
            'linkedin'       => ['nullable', 'url'],
            'logo'           => ['nullable', 'image', 'max:10240'],
            'banner'         => ['nullable', 'image', 'max:15360'],
            'mission'        => ['nullable', 'string'],
            'vision'         => ['nullable', 'string'],
            'annual_revenue' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
            $profile->logo = $logoPath;
        }

        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($profile->banner) {
                Storage::disk('public')->delete($profile->banner);
            }
            $bannerPath = $request->file('banner')->store('banners', 'public');
            $profile->banner = $bannerPath;
        }

        // Convert tech_tags to array
        $techTags = [];
        if (!empty($validated['tech_tags'])) {
            $techTags = array_filter(array_map('trim', explode(',', $validated['tech_tags'])));
        }

        $profile->update([
            'company_name'   => $validated['company_name'],
            'industry_id'    => $validated['industry_id'],
            'stage'          => $validated['stage'],
            'team_size'      => $validated['team_size'] ?? 1,
            'founded_year'   => $validated['founded_year'] ?? null,
            'funding_status' => $validated['funding_status'],
            'funding_amount' => $validated['funding_amount'] ?? 0,
            'elevator_pitch' => $validated['elevator_pitch'],
            'tech_tags'      => $techTags,
            'seeking'        => $validated['seeking'] ?? [],
            'budget_min'     => $validated['budget_min'] ?? 0,
            'budget_max'     => $validated['budget_max'] ?? 0,
            'city'           => $validated['city'] ?? null,
            'state'          => $validated['state'] ?? null,
            'website'        => $validated['website'] ?? null,
            'linkedin'       => $validated['linkedin'] ?? null,
            'logo'           => $profile->logo,
            'banner'         => $profile->banner,
            'mission'        => $validated['mission'] ?? null,
            'vision'         => $validated['vision'] ?? null,
            'annual_revenue' => $validated['annual_revenue'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
