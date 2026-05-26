<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminIndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::withCount(['startupProfiles', 'corporateProfiles'])
            ->orderBy('name')
            ->get();
        return view('admin.industries', compact('industries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:industries,name'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        Industry::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? '🏭',
        ]);

        return redirect()->back()->with('success', "Industry \"{$validated['name']}\" added successfully!");
    }

    public function update(Request $request, Industry $industry)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:industries,name,' . $industry->id],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        $industry->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? $industry->icon,
        ]);

        return redirect()->back()->with('success', "Industry updated successfully!");
    }

    public function destroy(Industry $industry)
    {
        $inUse = $industry->startupProfiles()->count() + $industry->corporateProfiles()->count();
        if ($inUse > 0) {
            return redirect()->back()->with('error', "Cannot delete: {$inUse} profile(s) use this industry.");
        }

        $industry->delete();
        return redirect()->back()->with('success', "Industry deleted.");
    }
}
