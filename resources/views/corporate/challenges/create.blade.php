@extends('layouts.app')
@section('title', 'Post Innovation Challenge')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 relative z-10 animate-fade-in">
    @include('components.back-button', ['fallback' => route('corporate.challenges.index'), 'label' => 'Back to Challenges'])

    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-xs font-bold uppercase tracking-widest text-purple-600 dark:text-purple-400 mb-4 border border-purple-200 dark:border-purple-800/50">
            Challenge Builder
        </div>
        <h1 class="text-3xl font-black mb-2 text-gray-900 dark:text-white font-outfit">
            Post New <span class="text-gradient from-purple-500 to-pink-500">Innovation Challenge</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Define your corporate problem and find the startup with the right technical solution.</p>
    </div>

    {{-- Error Banner --}}
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 text-sm font-medium flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="list-disc pl-4 mt-1 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('corporate.challenges.store') }}" class="space-y-6">
        @csrf

        <div class="glass-card-strong rounded-3xl p-8 border-glow space-y-6">
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Challenge Title</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Next-Gen Generative AI Recommendation System" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                @error('title')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Industry Sector</label>
                    <select name="industry_id" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                        <option value="" disabled selected>Select Industry</option>
                        @foreach($industries as $i)
                            <option value="{{ $i->id }}" {{ old('industry_id') == $i->id ? 'selected' : '' }}>{{ $i->name }}</option>
                        @endforeach
                    </select>
                    @error('industry_id')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Deadline <span class="text-xs text-gray-400 font-normal">(Must be after today)</span></label>
                    <input type="date" name="deadline" required value="{{ old('deadline') }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    @error('deadline')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Problem Statement & Description</label>
                <textarea name="description" rows="6" required placeholder="Describe the corporate challenge in detail, including the exact core issues and goals..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Technical & Submission Requirements</label>
                <textarea name="requirements" rows="4" placeholder="Detail any mandatory stack requirements, certifications, timeline rules, or team sizes..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">{{ old('requirements') }}</textarea>
                @error('requirements')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Min Budget (₹)</label>
                    <input type="number" name="budget_min" min="0" required value="{{ old('budget_min', 0) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    @error('budget_min')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Max Budget (₹)</label>
                    <input type="number" name="budget_max" min="0" required value="{{ old('budget_max', 0) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    @error('budget_max')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Required Skills / Technology Tags <span class="text-xs text-gray-400 font-normal">(comma-separated)</span></label>
                <input type="text" name="required_tags" value="{{ old('required_tags') }}" placeholder="e.g. AI, Python, Docker, PyTorch" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                @error('required_tags')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="w-full shimmer-btn bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-purple-600/25 transition-all duration-300 hover:scale-[1.01]">
            Publish Challenge
        </button>
    </form>
</div>
@endsection
