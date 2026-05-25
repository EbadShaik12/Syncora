@extends('layouts.app')
@section('title', 'My Applications')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    @include('components.back-button', ['fallback' => route('startup.dashboard'), 'label' => 'Back to Dashboard'])
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">My Applications</h1>
        <a href="{{ route('startup.challenges') }}" class="text-sm text-primary-600 hover:underline">Browse challenges →</a>
    </div>

    @if($applications->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center">
            <div class="text-5xl mb-3">📤</div>
            <p class="text-gray-500 mb-4">You haven't applied to any challenges yet.</p>
            <a href="{{ route('startup.challenges') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium">Browse Challenges</a>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($applications as $app)
            <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                <div class="flex items-start gap-4">
                    <img src="{{ $app->challenge->corporate->logoUrl() }}" class="w-12 h-12 rounded-xl object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <p class="text-xs text-gray-500">{{ $app->challenge->corporate->companyName() }}</p>
                                <h3 class="font-semibold">{{ $app->challenge->title }}</h3>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium capitalize {{ $app->statusColor() }}">{{ $app->status }}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-2">{{ $app->cover_letter }}</p>
                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                            <span>Applied {{ $app->created_at->diffForHumans() }}</span>
                            @if($app->proposal_file)<a href="{{ asset('storage/'.$app->proposal_file) }}" target="_blank" class="text-primary-600 hover:underline">📄 View proposal</a>@endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $applications->links() }}</div>
    @endif
</div>
@endsection
