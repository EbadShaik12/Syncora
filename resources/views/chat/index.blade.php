@extends('layouts.app')
@section('title', 'Messages')
@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 relative z-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4 reveal">
        <div>
            <h1 class="text-4xl font-black font-outfit text-gray-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </span>
                Messages
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">
                {{ $connections->count() }} active conversation{{ $connections->count() !== 1 ? 's' : '' }}
            </p>
        </div>
        <a href="{{ auth()->user()->isStartup() ? route('startup.swipe') : route('corporate.swipe') }}"
            class="shimmer-btn inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-600 to-purple-600 hover:scale-105 text-white rounded-xl font-bold shadow-xl shadow-primary-600/30 transition self-start sm:self-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            New Match
        </a>
    </div>

    <div class="glass-card-strong rounded-3xl overflow-hidden reveal reveal-delay-1 border-glow shadow-2xl">
        @if($connections->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-gray-800/50">
                @foreach($connections as $conn)
                    @php
                        $other   = $conn->otherUser(auth()->id());
                        $lastMsg = $conn->latestMessage;
                        $unread  = $conn->unread_count ?? 0;
                    @endphp
                    <a href="{{ route('chat.show', $conn) }}"
                        class="flex items-center gap-5 px-6 py-5 hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-all duration-300 group">

                        {{-- Avatar + online dot --}}
                        <div class="relative flex-shrink-0">
                            <img src="{{ $other->logoUrl() }}" alt="{{ $other->companyName() }}"
                                class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white dark:ring-gray-900 shadow-lg group-hover:scale-105 transition-transform bg-white">
                            <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full ring-4 ring-white dark:ring-gray-900 shadow-sm border border-green-400"></span>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <p class="font-bold text-lg truncate {{ $unread > 0 ? 'text-gray-900 dark:text-white font-black' : 'text-gray-800 dark:text-gray-200' }} group-hover:text-primary-600 transition-colors">
                                    {{ $other->companyName() }}
                                </p>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    @if($lastMsg)
                                        <span class="text-xs font-semibold text-gray-400">{{ $lastMsg->created_at->diffForHumans(null, true) }}</span>
                                    @endif
                                    @if($unread > 0)
                                        <span class="w-6 h-6 bg-gradient-to-br from-primary-500 to-pink-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-lg shadow-primary-500/40">
                                            {{ $unread > 9 ? '9+' : $unread }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($lastMsg)
                                    <p class="text-sm truncate {{ $unread > 0 ? 'font-bold text-gray-800 dark:text-gray-200' : 'text-gray-500 dark:text-gray-400 font-medium' }}">
                                        @if($lastMsg->sender_id === auth()->id())
                                            <span class="text-gray-400 font-semibold">You: </span>
                                        @endif
                                        {{ $lastMsg->content }}
                                    </p>
                                @else
                                    <p class="text-sm italic font-semibold text-primary-500">New match — say hello! 👋</p>
                                @endif
                            </div>
                            <div class="mt-2">
                                <span class="text-[10px] px-2.5 py-1 font-bold rounded-lg uppercase tracking-wider {{ $other->isStartup() ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' }}">
                                    {{ $other->profile()?->industry?->name ?? ucfirst($other->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center group-hover:bg-primary-100 dark:group-hover:bg-primary-900/50 transition-colors">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-24 px-8">
                <div class="w-28 h-28 mx-auto rounded-3xl bg-gradient-to-br from-primary-100 to-purple-100 dark:from-primary-900/30 dark:to-purple-900/30 flex items-center justify-center mb-6 shadow-inner ring-1 ring-white/50 dark:ring-white/5">
                    <svg class="w-14 h-14 text-primary-500 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-black font-outfit mb-3 text-gray-900 dark:text-white">No conversations yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-10 max-w-sm mx-auto text-base font-medium leading-relaxed">
                    Start swiping to discover {{ auth()->user()->isStartup() ? 'corporate partners' : 'innovative startups' }}. Once you mutually match, you can chat instantly.
                </p>
                <a href="{{ auth()->user()->isStartup() ? route('startup.swipe') : route('corporate.swipe') }}"
                    class="shimmer-btn inline-flex items-center gap-3 bg-gradient-to-r from-primary-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-black shadow-xl shadow-primary-600/30 transition hover:scale-105">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    Discover Partners
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
