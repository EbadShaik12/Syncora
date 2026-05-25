@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    @include('components.back-button', ['fallback' => route('corporate.dashboard'), 'label' => 'Back to Dashboard'])
    <h1 class="text-3xl font-bold mb-6">Your Corporate Profile</h1>

    <form method="POST" action="{{ route('corporate.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Basic Info</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Logo</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ $profile->logo ? asset('storage/'.$profile->logo) : auth()->user()->logoUrl() }}" class="w-20 h-20 rounded-2xl object-cover bg-gray-100">
                        <input type="file" name="logo" accept="image/*" class="block text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Industry</label>
                    <select name="industry_id" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                        @foreach($industries as $i)<option value="{{ $i->id }}" @selected(old('industry_id',$profile->industry_id)==$i->id)>{{ $i->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Company Size</label>
                    <select name="company_size" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                        @foreach(['small'=>'Small (≤50)','medium'=>'Medium (50-500)','large'=>'Large (500-5000)','enterprise'=>'Enterprise (5000+)'] as $val=>$label)<option value="{{ $val }}" @selected(old('company_size',$profile->company_size)==$val)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">About Company</label>
                    <textarea name="about" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">{{ old('about', $profile->about) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Problem Statement <span class="text-xs text-gray-400">(What you're looking for)</span></label>
                    <textarea name="problem_statement" rows="4" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">{{ old('problem_statement', $profile->problem_statement) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Partnership & Targeting</h2>
            <p class="text-sm font-medium mb-3">Partnership Types</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                @foreach(['investment'=>'💰 Investment','pilot'=>'🚀 Pilot','mentorship'=>'🎓 Mentorship','acquisition'=>'🤝 Acquisition'] as $val=>$label)
                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                    <input type="checkbox" name="partnership_types[]" value="{{ $val }}" {{ in_array($val, (array)($profile->partnership_types ?? [])) ? 'checked' : '' }} class="rounded text-primary-600">
                    <span class="text-sm">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            <p class="text-sm font-medium mb-3">Looking for stages</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                @foreach(['idea'=>'Idea','mvp'=>'MVP','growth'=>'Growth','scale'=>'Scale'] as $val=>$label)
                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                    <input type="checkbox" name="seeking_stages[]" value="{{ $val }}" {{ in_array($val, (array)($profile->seeking_stages ?? [])) ? 'checked' : '' }} class="rounded text-primary-600">
                    <span class="text-sm">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Technologies of Interest <span class="text-xs text-gray-400">(comma-separated)</span></label>
                <input type="text" name="seeking_technologies" value="{{ old('seeking_technologies', is_array($profile->seeking_technologies) ? implode(', ', $profile->seeking_technologies) : '') }}" placeholder="e.g. AI, Blockchain, IoT" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
            </div>

            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <div><label class="block text-sm font-medium mb-2">Budget Min (₹)</label><input type="number" name="budget_min" min="0" value="{{ old('budget_min', $profile->budget_min) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
                <div><label class="block text-sm font-medium mb-2">Budget Max (₹)</label><input type="number" name="budget_max" min="0" value="{{ old('budget_max', $profile->budget_max) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Location & Links</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium mb-2">City</label><input type="text" name="city" value="{{ old('city',$profile->city) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
                <div><label class="block text-sm font-medium mb-2">State</label><input type="text" name="state" value="{{ old('state',$profile->state) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
                <div><label class="block text-sm font-medium mb-2">Website</label><input type="url" name="website" value="{{ old('website',$profile->website) }}" placeholder="https://" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
                <div><label class="block text-sm font-medium mb-2">LinkedIn</label><input type="url" name="linkedin" value="{{ old('linkedin',$profile->linkedin) }}" placeholder="https://linkedin.com/..." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
            </div>
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-primary-600/30 transition hover:scale-[1.01]">Save Profile</button>
    </form>
</div>
@endsection
