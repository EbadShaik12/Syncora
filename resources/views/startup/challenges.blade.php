@extends('layouts.app')
@section('title', 'Innovation Challenges')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('components.back-button', ['fallback' => route('startup.dashboard'), 'label' => 'Back to Dashboard'])
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Innovation Challenges</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Apply to challenges posted by corporates and win pilot opportunities.</p>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 mb-6 grid md:grid-cols-12 gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search challenges..." class="md:col-span-7 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
        <select name="industry_id" class="md:col-span-4 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
            <option value="">All industries</option>
            @foreach($industries as $i)<option value="{{ $i->id }}" @selected(request('industry_id')==$i->id)>{{ $i->name }}</option>@endforeach
        </select>
        <button class="md:col-span-1 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium">Filter</button>
    </form>

    @if($challenges->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center">
            <div class="text-5xl mb-3">📋</div>
            <p class="text-gray-500">No open challenges right now. Check back soon!</p>
        </div>
    @else
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($challenges as $challenge)
        @php $applied = in_array($challenge->id, $appliedIds); @endphp
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-xl transition flex flex-col">
            <div class="flex items-start gap-3 mb-3">
                <img src="{{ $challenge->corporate->logoUrl() }}" class="w-12 h-12 rounded-xl object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-500">{{ $challenge->corporate->companyName() }}</p>
                    <h3 class="font-bold line-clamp-2">{{ $challenge->title }}</h3>
                </div>
            </div>

            @if($challenge->industry)<span class="inline-block px-2 py-0.5 rounded-full text-xs bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 mb-2">{{ $challenge->industry->name }}</span>@endif

            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-4 flex-1">{{ $challenge->description }}</p>

            <div class="border-t border-gray-100 dark:border-gray-700 pt-3 space-y-1 text-xs">
                <div class="flex justify-between"><span class="text-gray-500">Budget:</span><span class="font-semibold text-primary-600">₹{{ number_format($challenge->budget_min) }} – ₹{{ number_format($challenge->budget_max) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Deadline:</span><span>{{ $challenge->deadline->format('M d, Y') }} <span class="text-gray-400">({{ $challenge->deadline->diffForHumans() }})</span></span></div>
            </div>

            @if($applied)
                <button disabled class="mt-4 w-full py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 font-medium cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Applied
                </button>
            @else
                <a href="{{ route('startup.challenges.apply', $challenge) }}" class="mt-4 w-full py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-center block transition">Apply Now</a>
            @endif
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $challenges->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
