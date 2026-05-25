@extends('layouts.app')
@section('title', 'Edit Innovation Challenge')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 relative z-10 animate-fade-in">
    @include('components.back-button', ['fallback' => route('corporate.challenges.index'), 'label' => 'Back to Challenges'])

    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-xs font-bold uppercase tracking-widest text-purple-600 dark:text-purple-400 mb-4 border border-purple-200 dark:border-purple-800/50">
            Edit Mode
        </div>
        <h1 class="text-3xl font-black mb-2 text-gray-900 dark:text-white font-outfit">
            Modify <span class="text-gradient from-purple-500 to-pink-500">Challenge Details</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Update the problem statement, parameters, and tag identifiers for your challenge.</p>
    </div>

    <form method="POST" action="{{ route('corporate.challenges.update', $challenge) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="glass-card-strong rounded-3xl p-8 border-glow space-y-6">
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Challenge Title</label>
                <input type="text" name="title" required value="{{ old('title', $challenge->title) }}" placeholder="e.g. Next-Gen Generative AI Recommendation System" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                @error('title')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Industry Sector</label>
                    <select name="industry_id" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                        @foreach($industries as $i)
                            <option value="{{ $i->id }}" {{ old('industry_id', $challenge->industry_id) == $i->id ? 'selected' : '' }}>{{ $i->name }}</option>
                        @endforeach
                    </select>
                    @error('industry_id')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Deadline</label>
                    <input type="date" name="deadline" required value="{{ old('deadline', $challenge->deadline ? $challenge->deadline->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    @error('deadline')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Status</label>
                    <select name="status" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                        <option value="open" {{ old('status', $challenge->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="reviewing" {{ old('status', $challenge->status) == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="closed" {{ old('status', $challenge->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Problem Statement & Description</label>
                <textarea name="description" rows="6" required placeholder="Describe the corporate challenge in detail, including the exact core issues and goals..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">{{ old('description', $challenge->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Technical & Submission Requirements</label>
                <textarea name="requirements" rows="4" placeholder="Detail any mandatory stack requirements, certifications, timeline rules, or team sizes..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">{{ old('requirements', $challenge->requirements) }}</textarea>
                @error('requirements')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Min Budget (₹)</label>
                    <input type="number" name="budget_min" min="0" required value="{{ old('budget_min', $challenge->budget_min) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    @error('budget_min')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Max Budget (₹)</label>
                    <input type="number" name="budget_max" min="0" required value="{{ old('budget_max', $challenge->budget_max) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    @error('budget_max')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Required Skills / Technology Tags <span class="text-xs text-gray-400 font-normal">(comma-separated)</span></label>
                <input type="text" name="required_tags" value="{{ old('required_tags', is_array($challenge->required_tags) ? implode(', ', $challenge->required_tags) : '') }}" placeholder="e.g. AI, Python, Docker, PyTorch" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                @error('required_tags')
                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="w-full shimmer-btn bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-purple-600/25 transition-all duration-300 hover:scale-[1.01]">
            Save and Update Challenge
        </button>
    </form>
</div>
@endsection
