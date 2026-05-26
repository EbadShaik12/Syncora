<?php

namespace App\Http\Controllers\Corporate;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\CorporateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CorporateProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->corporateProfile;

        if (!$profile) {
            $profile = CorporateProfile::create([
                'user_id'      => $user->id,
                'company_name' => $user->name,
            ]);
        }

        $industries = Industry::orderBy('name')->get();

        return view('corporate.profile-edit', compact('profile', 'industries'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->corporateProfile;

        $validated = $request->validate([
            'company_name'         => ['required', 'string', 'max:255'],
            'industry_id'          => ['required', 'exists:industries,id'],
            'company_size'         => ['required', 'in:small,medium,large,enterprise'],
            'about'                => ['nullable', 'string'],
            'problem_statement'    => ['required', 'string', 'min:20'],
            'partnership_types'    => ['nullable', 'array'],
            'partnership_types.*'  => ['in:investment,pilot,mentorship,acquisition'],
            'seeking_stages'       => ['nullable', 'array'],
            'seeking_stages.*'     => ['in:idea,mvp,growth,scale'],
            'seeking_technologies' => ['nullable', 'string'],
            'budget_min'           => ['nullable', 'numeric', 'min:0'],
            'budget_max'           => ['nullable', 'numeric', 'gte:budget_min'],
            'city'                 => ['nullable', 'string', 'max:255'],
            'state'                => ['nullable', 'string', 'max:255'],
            'website'              => ['nullable', 'url'],
            'linkedin'             => ['nullable', 'url'],
            'logo'                 => ['nullable', 'image', 'max:10240'],
            'banner'               => ['nullable', 'image', 'max:15360'],
            'mission'              => ['nullable', 'string'],
            'vision'               => ['nullable', 'string'],
            'annual_revenue'       => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
            $profile->logo = $logoPath;
        }

        if ($request->hasFile('banner')) {
            if ($profile->banner) {
                Storage::disk('public')->delete($profile->banner);
            }
            $bannerPath = $request->file('banner')->store('banners', 'public');
            $profile->banner = $bannerPath;
        }

        $seekingTech = [];
        if (!empty($validated['seeking_technologies'])) {
            $seekingTech = array_filter(array_map('trim', explode(',', $validated['seeking_technologies'])));
        }

        $profile->update([
            'company_name'         => $validated['company_name'],
            'industry_id'          => $validated['industry_id'],
            'company_size'         => $validated['company_size'],
            'about'                => $validated['about'],
            'problem_statement'    => $validated['problem_statement'],
            'partnership_types'    => $validated['partnership_types'] ?? [],
            'seeking_stages'       => $validated['seeking_stages'] ?? [],
            'seeking_technologies' => $seekingTech,
            'budget_min'           => $validated['budget_min'] ?? 0,
            'budget_max'           => $validated['budget_max'] ?? 0,
            'city'                 => $validated['city'] ?? null,
            'state'                => $validated['state'] ?? null,
            'website'              => $validated['website'] ?? null,
            'linkedin'             => $validated['linkedin'] ?? null,
            'logo'                 => $profile->logo,
            'banner'               => $profile->banner,
            'mission'              => $validated['mission'] ?? null,
            'vision'               => $validated['vision'] ?? null,
            'annual_revenue'       => $validated['annual_revenue'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
