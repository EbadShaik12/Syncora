@extends('layouts.app')
@section('title', 'Startup Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
    
    <!-- Welcome Hero — Clean B2B SaaS Style -->
    <div class="reveal bg-white dark:bg-zinc-950 rounded-2xl p-8 md:p-12 mb-10 shadow-lg relative overflow-hidden border border-slate-200 dark:border-zinc-800">
        {{-- Floating shapes (subtle SaaS accent) --}}
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-500/5 rounded-full blur-[80px]"></div>
        <div class="absolute -bottom-16 -left-16 w-80 h-80 bg-indigo-500/5 rounded-full blur-[80px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start justify-between gap-8">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-blue-200 mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ now()->format('l, M d') }}
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-outfit leading-tight mb-4 text-slate-900 dark:text-white">
                    Hi, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-200 dark:to-indigo-200">{{ auth()->user()->name }}</span> 👋
                </h1>
                <p class="text-slate-600 dark:text-blue-100/80 max-w-xl text-lg leading-relaxed font-medium mb-8">Ready to scale? Discover corporate partners, apply to open innovation challenges, and grow your startup.</p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('startup.swipe') }}" class="magnetic bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-blue-700 px-8 py-4 rounded-xl font-bold shadow-md hover:scale-[1.02] transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        Discover Partners
                    </a>
                    <a href="{{ route('startup.challenges') }}" class="magnetic border border-slate-200 dark:border-white/30 text-slate-700 dark:text-white bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 px-8 py-4 rounded-xl font-bold hover:scale-[1.02] transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Browse Challenges
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block relative w-64 h-64">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full blur-2xl opacity-40 animate-pulse-glow"></div>
                <img src="{{ asset('images/feature-matching.png') }}" alt="Matching Engine" class="w-full h-full object-cover rounded-full border-4 border-white/10 shadow-2xl relative z-10 animate-float-slow">
            </div>
        </div>
    </div>

    <!-- Stats Grid — Premium Glassmorphic -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-6 mb-10 stagger-container">
        @foreach([
            ['label' => 'Total Matches',   'value' => $stats['connections'],     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857', 'color' => 'from-blue-500 to-cyan-500',    'ring' => 'text-cyan-500'],
            ['label' => 'Pending Swipes',  'value' => $stats['pending_signals'], 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'color' => 'from-pink-500 to-rose-500',   'ring' => 'text-rose-500'],
            ['label' => 'Applications',    'value' => $stats['applications'],    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'from-purple-500 to-indigo-500', 'ring' => 'text-indigo-500'],
            ['label' => 'Shortlisted',     'value' => $stats['shortlisted'],     'icon' => 'M5 13l4 4L19 7', 'color' => 'from-green-500 to-emerald-500',  'ring' => 'text-emerald-500'],
            ['label' => 'Total Badges',    'value' => $stats['badges'],          'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'color' => 'from-yellow-400 to-orange-500', 'ring' => 'text-yellow-500'],
        ] as $i => $stat)
            <div class="reveal reveal-delay-{{ $i + 1 }} glass-card rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-gradient-to-br {{ $stat['color'] }} rounded-full blur-2xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center shadow-lg text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                    </div>
                    
                    {{-- Mini Ring Chart --}}
                    <div class="relative w-12 h-12">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" class="text-gray-200 dark:text-gray-800" />
                            <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" stroke-dasharray="125.6" stroke-dashoffset="{{ 125.6 - (125.6 * min(100, ($stat['value'] ?: 0) * 15)) / 100 }}" class="{{ $stat['ring'] }} transition-all duration-1000 ease-out" />
                        </svg>
                    </div>
                </div>
                
                <p class="text-4xl font-black font-outfit text-gray-900 dark:text-white" data-count="{{ $stat['value'] }}">0</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-bold uppercase tracking-wider">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-8 stagger-container">
        
        <!-- Main Column: Challenges & Connections -->
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Open Challenges Spotlight --}}
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <div class="flex items-center justify-between mb-8 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <h2 class="text-2xl font-black font-outfit flex items-center gap-3 text-gray-900 dark:text-white">
                        <span class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                        Open Innovation Challenges
                    </h2>
                    <a href="{{ route('startup.challenges') }}" class="text-sm font-bold text-primary-600 hover:text-primary-500 transition px-4 py-2 bg-primary-50 dark:bg-primary-900/20 rounded-xl">View all</a>
                </div>
                
                @if($openChallenges->count() > 0)
                    <div class="grid sm:grid-cols-2 gap-5">
                        @foreach($openChallenges as $challenge)
                            <a href="{{ route('startup.challenges.apply', $challenge) }}" class="flex flex-col p-5 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white/50 dark:bg-gray-900/50 hover:border-blue-400 dark:hover:border-blue-500 transition group card-lift relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-blue-400 to-cyan-500"></div>
                                
                                <div class="flex items-start gap-4 mb-4">
                                    <img src="{{ $challenge->corporate->logoUrl() }}" alt="" class="w-12 h-12 rounded-xl object-cover shadow-sm border border-gray-100 dark:border-gray-800">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $challenge->corporate->companyName() }}</p>
                                        <h3 class="font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-blue-600 transition">{{ $challenge->title }}</h3>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4 font-medium flex-grow">{{ $challenge->description }}</p>
                                <div class="flex items-center justify-between text-xs font-bold mt-auto pt-4 border-t border-gray-100 dark:border-gray-800">
                                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 px-3 py-1.5 rounded-lg border border-blue-100 dark:border-blue-800/50">{{ $challenge->industry?->name }}</span>
                                    <span class="flex items-center gap-1 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $challenge->deadline->format('M d') }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <div class="w-20 h-20 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No active challenges</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Check back soon for new corporate innovation challenges, or discover partners via swiping.</p>
                    </div>
                @endif
            </div>

            {{-- Badges Showcase --}}
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <div class="flex items-center justify-between mb-8 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <h2 class="text-2xl font-black font-outfit flex items-center gap-3 text-gray-900 dark:text-white">
                        <span class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl text-yellow-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </span>
                        Earned Badges
                    </h2>
                    <span class="text-2xl font-black text-yellow-500">{{ $stats['badges'] }}</span>
                </div>

                @if($badges->count() > 0)
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach($badges as $badge)
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/10 dark:to-orange-900/10 border border-yellow-200/60 dark:border-yellow-800/30 hover:border-yellow-400 transition card-lift group">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 dark:text-white truncate">{{ $badge->name }}</p>
                                @if($badge->description)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $badge->description }}</p>
                                @endif
                                @if($badge->pivot?->awarded_at)
                                <p class="text-[10px] text-yellow-600 dark:text-yellow-500 font-bold mt-1 uppercase tracking-wider">
                                    Earned {{ \Carbon\Carbon::parse($badge->pivot->awarded_at)->diffForHumans() }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-yellow-50/50 dark:bg-yellow-900/10 rounded-2xl border border-dashed border-yellow-200 dark:border-yellow-800/30">
                        <div class="w-20 h-20 mx-auto rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No badges yet</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs mx-auto">Complete your profile and start interacting to earn achievement badges!</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Sidebar: Profile & Connections -->
        <div class="space-y-8">
            
            {{-- Profile Completeness --}}
            <div class="reveal">
                @include('components.profile-completeness')
            </div>

            {{-- Recent Connections --}}
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-black font-outfit text-gray-900 dark:text-white">Recent Matches</h2>
                </div>
                
                @if($recentConnections->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentConnections as $conn)
                            @php $other = $conn->otherUser(auth()->id()); @endphp
                            <a href="{{ route('chat.show', $conn) }}" class="flex items-center gap-4 p-3 rounded-2xl bg-white/30 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800 hover:border-pink-400 transition group card-lift">
                                <div class="relative">
                                    <img src="{{ $other->logoUrl() }}" alt="" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 dark:text-white truncate group-hover:text-pink-600 transition">{{ $other->companyName() }}</p>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-1">{{ $conn->matched_at->diffForHumans() }}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-pink-50 dark:bg-pink-900/30 flex items-center justify-center text-pink-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('chat.index') }}" class="block text-center text-sm font-bold text-gray-500 hover:text-pink-600 mt-6 pt-4 border-t border-gray-200 dark:border-gray-800 transition uppercase tracking-wider">View All Matches</a>
                @else
                    <div class="text-center py-10 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <div class="w-16 h-16 mx-auto rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center mb-4 text-pink-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-500 mb-4">No matches yet</p>
                        <a href="{{ route('startup.swipe') }}" class="text-sm font-bold text-pink-600 hover:underline">Start Swiping</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Floating Action Button (Premium Style) --}}
<div x-data="{ open: false }" class="fixed bottom-8 right-8 z-50 flex flex-col items-end gap-3">
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-90"
         class="flex flex-col items-end gap-3 mb-2">
        <a href="{{ route('leaderboard') }}" class="glass-card-strong px-5 py-3 rounded-2xl flex items-center gap-3 font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 hover:scale-105 transition shadow-xl">
            <span class="text-xl">🏆</span> Leaderboard
        </a>
        <a href="{{ route('startup.challenges') }}" class="glass-card-strong px-5 py-3 rounded-2xl flex items-center gap-3 font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 hover:scale-105 transition shadow-xl">
            <span class="text-xl">⚡</span> Browse Challenges
        </a>
        <a href="{{ route('startup.swipe') }}" class="glass-card-strong px-5 py-3 rounded-2xl flex items-center gap-3 font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 hover:scale-105 transition shadow-xl">
            <span class="text-xl">💫</span> Discover Partners
        </a>
    </div>
    <button @click="open = !open"
        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full shadow-[0_10px_30px_rgba(59,130,246,0.4)] hover:scale-110 flex items-center justify-center relative overflow-hidden"
        :class="open ? 'rotate-45' : ''" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)">
        <div class="absolute inset-0 bg-white/20" x-show="open"></div>
        <svg class="w-8 h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
    </button>
</div>
@endsection
