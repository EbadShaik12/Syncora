@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    @include('components.back-button', ['fallback' => route('corporate.dashboard'), 'label' => 'Back to Dashboard'])
    <h1 class="text-3xl font-bold mb-6">Your Corporate Profile</h1>

    <form method="POST" action="{{ route('corporate.profile.update') }}" enctype="multipart/form-data" data-warn-unsaved class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Basic Info</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Logo</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ $profile->logo ? asset('storage/'.$profile->logo) : auth()->user()->logoUrl() }}" class="w-20 h-20 rounded-2xl object-cover bg-gray-105 border border-slate-250 dark:border-zinc-700 shadow-inner">
                        <div class="flex flex-col gap-1">
                            <input type="file" name="logo" accept="image/*" class="block text-sm text-slate-500 dark:text-zinc-400">
                            <p class="text-[10px] text-gray-450 dark:text-zinc-500 font-bold mt-1">Recommended: Square PNG/JPEG up to 10MB</p>
                            @error('logo')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Profile Cover Banner</label>
                    <div class="flex flex-col gap-3">
                        @if($profile->banner)
                            <div class="relative w-full h-32 rounded-2xl overflow-hidden border border-slate-200 dark:border-zinc-700 shadow-inner">
                                <img src="{{ asset('storage/'.$profile->banner) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-full h-32 rounded-2xl bg-gradient-to-r from-primary-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold shadow-inner opacity-80">
                                Default Cover Gradient Active
                            </div>
                        @endif
                        <div class="flex flex-col gap-1">
                            <input type="file" name="banner" accept="image/*" class="block text-sm text-slate-500 dark:text-zinc-400">
                            <p class="text-[10px] text-gray-455 dark:text-zinc-500 font-bold mt-1">Recommended: Landscape banner up to 15MB</p>
                            @error('banner')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                        </div>
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

        <!-- Mission, Vision & Venture Fund -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Mission, Vision &amp; Venture Allocation</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Our Mission <span class="text-xs text-gray-400">(Shows on profile timeline)</span></label>
                    <textarea name="mission" rows="2" placeholder="e.g. To identify, accelerate, and scale innovative solutions through corporate backing." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">{{ old('mission', $profile->mission) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Our Vision <span class="text-xs text-gray-400">(Shows on profile timeline)</span></label>
                    <textarea name="vision" rows="2" placeholder="e.g. To establish the benchmark for corporate innovation and digital partnership." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">{{ old('vision', $profile->vision) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Venture Allocation / Strategic Pipeline Fund <span class="text-xs text-gray-400">(Shows as current year metric in timeline)</span></label>
                    <input type="text" name="annual_revenue" value="{{ old('annual_revenue', $profile->annual_revenue) }}" placeholder="e.g. Pipeline: Active Match or Sandbox Pilots: 5+ Coordinated" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
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
