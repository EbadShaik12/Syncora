@extends('layouts.app')
@section('title', 'Corporate Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
    
    <!-- Welcome Hero — Mesh Gradient & Glassmorphism -->
    <div class="reveal glass-card-strong rounded-[2.5rem] p-8 md:p-12 text-white mb-10 shadow-2xl relative overflow-hidden border-glow">
        {{-- Floating shapes --}}
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-purple-500/30 rounded-full blur-[80px] animate-pulse-glow"></div>
        <div class="absolute -bottom-16 -left-16 w-80 h-80 bg-pink-500/30 rounded-full blur-[80px]" style="animation-delay: 2s;"></div>
        
        {{-- Glass Grid Pattern --}}
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9ImIiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI2IpIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PC9zdmc+')] opacity-50"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start justify-between gap-8">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md text-xs font-bold uppercase tracking-widest text-purple-200 mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    {{ now()->format('l, M d') }}
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-outfit leading-tight mb-4 text-glow">
                    Hi, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-200 to-pink-200">{{ auth()->user()->name }}</span> 👋
                </h1>
                <p class="text-purple-100/80 max-w-xl text-lg leading-relaxed font-medium mb-8">Your innovation hub. Discover high-growth startups, post challenges, and accelerate your R&D pipeline.</p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('corporate.swipe') }}" class="magnetic shimmer-btn bg-white text-purple-700 px-8 py-4 rounded-2xl font-bold shadow-[0_0_40px_rgba(255,255,255,0.3)] hover:scale-105 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        Discover Startups
                    </a>
                    <a href="{{ route('corporate.challenges.create') }}" class="magnetic glass-card border border-white/30 text-white px-8 py-4 rounded-2xl font-bold hover:bg-white/10 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Post Challenge
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block relative w-64 h-64">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full blur-2xl opacity-40 animate-pulse-glow"></div>
                <img src="{{ asset('images/feature-ai.png') }}" alt="AI Engine" class="w-full h-full object-cover rounded-full border-4 border-white/10 shadow-2xl relative z-10 animate-float-slow">
            </div>
        </div>
    </div>

    <!-- Stats Grid — Premium Glassmorphic -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10 stagger-container">
        @foreach([
            ['label' => 'Total Connections', 'value' => $stats['connections'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'color' => 'from-blue-500 to-cyan-500', 'ring' => 'text-cyan-500'],
            ['label' => 'Active Challenges', 'value' => $stats['open_challenges'], 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'from-purple-500 to-pink-500', 'ring' => 'text-pink-500'],
            ['label' => 'Total Posted', 'value' => $stats['challenges_posted'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2', 'color' => 'from-orange-500 to-red-500', 'ring' => 'text-orange-500'],
            ['label' => 'New Applications', 'value' => $stats['applications_received'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'from-green-500 to-emerald-500', 'ring' => 'text-emerald-500'],
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
        
        <!-- Main Column: Challenges & Timeline -->
        <div class="lg:col-span-2 space-y-8">
            
            {{-- My Challenges --}}
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <div class="flex items-center justify-between mb-8 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <h2 class="text-2xl font-black font-outfit flex items-center gap-3 text-gray-900 dark:text-white">
                        <span class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-xl text-purple-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                        My Challenges
                    </h2>
                    <a href="{{ route('corporate.challenges.index') }}" class="text-sm font-bold text-primary-600 hover:text-primary-500 transition px-4 py-2 bg-primary-50 dark:bg-primary-900/20 rounded-xl">View all</a>
                </div>
                
                @if($myChallenges->count() > 0)
                    <div class="space-y-4">
                        @foreach($myChallenges as $challenge)
                            <a href="{{ route('corporate.challenges.show', $challenge) }}" class="flex items-center gap-5 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white/50 dark:bg-gray-900/50 hover:border-purple-400 dark:hover:border-purple-500 transition group card-lift">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-black text-2xl shadow-lg">
                                    {{ strtoupper(substr($challenge->title, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-lg font-bold truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition text-gray-900 dark:text-white">{{ $challenge->title }}</p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full {{ $challenge->statusColor() }}">{{ ucfirst($challenge->status) }}</span>
                                        <span class="flex items-center gap-1 text-sm text-gray-500 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            {{ $challenge->applications_count }} applications
                                        </span>
                                    </div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/50 group-hover:text-purple-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <div class="w-20 h-20 mx-auto bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-600 mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No challenges posted</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">Post an innovation challenge to attract top startups to solve your corporate problems.</p>
                        <a href="{{ route('corporate.challenges.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition">
                            Create First Challenge
                        </a>
                    </div>
                @endif
            </div>

            {{-- Recent Applications Timeline --}}
            @if($recentApplications->count() > 0)
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <h2 class="text-2xl font-black font-outfit mb-8 border-b border-gray-200 dark:border-gray-800 pb-4 text-gray-900 dark:text-white">Activity Timeline</h2>
                <div class="relative border-l-2 border-gray-200 dark:border-gray-800 ml-4 space-y-8">
                    @foreach($recentApplications as $app)
                        <div class="relative pl-8">
                            <div class="absolute -left-[11px] top-1 w-5 h-5 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 border-4 border-white dark:border-gray-900 shadow"></div>
                            <div class="bg-white/50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 hover:border-purple-400 transition card-lift">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $app->startup->logoUrl() }}" alt="" class="w-12 h-12 rounded-xl object-cover shadow-sm">
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white">{{ $app->startup->companyName() }} <span class="text-gray-500 font-medium">applied to</span></p>
                                            <p class="text-purple-600 dark:text-purple-400 font-bold line-clamp-1">{{ $app->challenge->title }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold uppercase px-3 py-1.5 rounded-lg {{ $app->statusColor() }} whitespace-nowrap">{{ ucfirst($app->status) }}</span>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-semibold">{{ $app->created_at->diffForHumans() }}</span>
                                    <a href="{{ route('corporate.challenges.applications', $app->challenge) }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 uppercase tracking-wider">Review Application →</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

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
                            <a href="{{ route('chat.show', $conn) }}" class="flex items-center gap-4 p-3 rounded-2xl bg-white/30 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800 hover:border-blue-400 transition group card-lift">
                                <div class="relative">
                                    <img src="{{ $other->logoUrl() }}" alt="" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 transition">{{ $other->companyName() }}</p>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-1">{{ $conn->matched_at->diffForHumans() }}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('chat.index') }}" class="block text-center text-sm font-bold text-gray-500 hover:text-purple-600 mt-6 pt-4 border-t border-gray-200 dark:border-gray-800 transition uppercase tracking-wider">View All Connections</a>
                @else
                    <div class="text-center py-10 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4 text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-500 mb-4">No matches yet</p>
                        <a href="{{ route('corporate.swipe') }}" class="text-sm font-bold text-blue-600 hover:underline">Start Swiping</a>
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
        <a href="{{ route('leaderboard') }}" class="glass-card-strong px-5 py-3 rounded-2xl flex items-center gap-3 font-bold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 hover:scale-105 transition shadow-xl">
            <span class="text-xl">🏆</span> Leaderboard
        </a>
        <a href="{{ route('corporate.swipe') }}" class="glass-card-strong px-5 py-3 rounded-2xl flex items-center gap-3 font-bold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 hover:scale-105 transition shadow-xl">
            <span class="text-xl">💫</span> Discover Startups
        </a>
        <a href="{{ route('corporate.challenges.create') }}" class="glass-card-strong px-5 py-3 rounded-2xl flex items-center gap-3 font-bold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 hover:scale-105 transition shadow-xl">
            <span class="text-xl">⚡</span> Post Challenge
        </a>
    </div>
    <button @click="open = !open"
        class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-full shadow-[0_10px_30px_rgba(168,85,247,0.4)] hover:scale-110 flex items-center justify-center relative overflow-hidden"
        :class="open ? 'rotate-45' : ''" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)">
        <div class="absolute inset-0 bg-white/20" x-show="open"></div>
        <svg class="w-8 h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
    </button>
</div>
@endsection
