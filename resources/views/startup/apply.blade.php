@extends('layouts.app')
@section('title', 'Apply: ' . $challenge->title)
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    @include('components.back-button', ['fallback' => route('startup.challenges'), 'label' => 'Back to Challenges'])

    <!-- Challenge summary -->
    <div class="bg-gradient-to-br from-primary-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl mb-6">
        <div class="flex items-start gap-3 mb-3">
            <img src="{{ $challenge->corporate->logoUrl() }}" class="w-12 h-12 rounded-xl object-cover bg-white">
            <div class="flex-1">
                <p class="text-xs opacity-80">{{ $challenge->corporate->companyName() }}</p>
                <h1 class="text-2xl font-bold">{{ $challenge->title }}</h1>
            </div>
        </div>
        <p class="text-sm opacity-90 mb-3">{{ $challenge->description }}</p>
        <div class="flex flex-wrap gap-3 text-xs">
            <span class="px-2.5 py-1 bg-white/20 rounded-full">💰 ₹{{ number_format($challenge->budget_min) }} – ₹{{ number_format($challenge->budget_max) }}</span>
            <span class="px-2.5 py-1 bg-white/20 rounded-full">📅 Deadline: {{ $challenge->deadline->format('M d, Y') }}</span>
        </div>
    </div>

    @if($challenge->requirements)
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6 text-sm">
        <p class="font-semibold mb-1">Requirements:</p>
        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $challenge->requirements }}</p>
    </div>
    @endif

    <!-- Application form -->
    <form method="POST" action="{{ route('startup.challenges.apply.store', $challenge) }}" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 space-y-5">
        @csrf
        <h2 class="font-bold text-lg">Your Application</h2>

        <div>
            <label class="block text-sm font-medium mb-2">Cover Letter <span class="text-xs text-gray-400">(50-2000 chars)</span></label>
            <textarea name="cover_letter" rows="5" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none" placeholder="Tell them why you're the right fit...">{{ old('cover_letter') }}</textarea>
            @error('cover_letter')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Your Approach / Solution <span class="text-xs text-gray-400">(optional, max 3000 chars)</span></label>
            <textarea name="approach" rows="6" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none" placeholder="Describe your technical approach, timeline, and deliverables...">{{ old('approach') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Proposal Document <span class="text-xs text-gray-400">(PDF / DOC, max 5MB)</span></label>
            <input type="file" name="proposal_file" accept=".pdf,.doc,.docx" class="block w-full text-sm">
            @error('proposal_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-primary-600/30 transition hover:scale-[1.01]">Submit Application</button>
    </form>
</div>
@endsection
