@extends('layouts.app')
@section('title', 'Leaderboard — Syncora')

@push('styles')
<style type="text/tailwindcss">
/* Leaderboard Animations */
@keyframes countUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
@keyframes fadeUp  { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }

/* Premium Gradients for Ranks */
.rank-gold   { background: linear-gradient(135deg, #FFD700 0%, #FDB931 50%, #E6B800 100%); text-shadow: 0 1px 2px rgba(0,0,0,0.3); }
.rank-silver { background: linear-gradient(135deg, #E2E2E2 0%, #C9D6FF 50%, #94A3B8 100%); text-shadow: 0 1px 2px rgba(0,0,0,0.2); }
.rank-bronze { background: linear-gradient(135deg, #FFB75E 0%, #ED8F03 50%, #B45309 100%); text-shadow: 0 1px 2px rgba(0,0,0,0.2); }

/* Staggered Row Animation */
.leaderboard-row { animation: fadeUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards; opacity: 0; }
.leaderboard-row:nth-child(1)  { animation-delay: 0.1s; }
.leaderboard-row:nth-child(2)  { animation-delay: 0.2s; }
.leaderboard-row:nth-child(3)  { animation-delay: 0.3s; }
.leaderboard-row:nth-child(4)  { animation-delay: 0.4s; }
.leaderboard-row:nth-child(5)  { animation-delay: 0.5s; }
.leaderboard-row:nth-child(n+6){ animation-delay: 0.6s; }

.score-bar { transition: width 1.5s cubic-bezier(0.4,0,0.2,1); }

/* 3D Podium Styles */
.podium-container {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 1.5rem;
    height: 350px;
    perspective: 1000px;
    margin-bottom: 3rem;
}
.podium-step {
    position: relative;
    width: 140px;
    border-radius: 16px 16px 0 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-top: 1.5rem;
    transform-style: preserve-3d;
    box-shadow: inset 0 20px 40px rgba(255,255,255,0.2), 0 -10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}
.podium-step:hover {
    transform: translateY(-10px) scale(1.02);
}
.podium-step::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 10px;
    background: rgba(255,255,255,0.4);
    border-radius: 16px 16px 0 0;
    transform: translateY(-5px) translateZ(10px);
}
.podium-1 { height: 220px; background: linear-gradient(to bottom, #FDB931, #E6B800); z-index: 3; }
.podium-2 { height: 160px; background: linear-gradient(to bottom, #C9D6FF, #94A3B8); z-index: 2; }
.podium-3 { height: 120px; background: linear-gradient(to bottom, #ED8F03, #B45309); z-index: 1; }

.avatar-container {
    position: absolute;
    top: -90px;
    left: 50%;
    transform: translateX(-50%);
    animation: float 4s ease-in-out infinite;
}
.podium-1 .avatar-container { top: -110px; }

@keyframes float {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(-10px); }
}

.you-badge { @apply ml-3 px-2 py-1 text-[10px] font-black rounded-full bg-gradient-to-r from-primary-500 to-pink-500 text-white uppercase tracking-wider shadow-md; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 relative z-10" x-data="{ tab: '{{ $viewer->isStartup() ? 'corporates' : 'startups' }}' }">
    @include('components.back-button', ['fallback' => route('dashboard'), 'label' => 'Back to Dashboard'])

    {{-- ── Hero Header ── --}}
    <div class="text-center mb-16 reveal">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs font-bold uppercase tracking-widest mb-4 border border-yellow-200 dark:border-yellow-800/50 shadow-lg">
            <span class="animate-pulse">🏆</span> Platform Leaderboard
        </div>
        <h1 class="text-5xl lg:text-6xl font-black font-outfit mb-4 text-gray-900 dark:text-white">
            Top <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-orange-500 to-pink-500">Performers</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-lg max-w-2xl mx-auto font-medium">
            Ranked by AI-powered analysis of engagement, profile quality, and growth potential — powered by
            <span class="text-primary-600 dark:text-primary-400 font-bold">Gemini AI ✨</span>
        </p>
    </div>

    {{-- ── Tab switcher ── --}}
    <div class="flex gap-2 p-1.5 bg-gray-100/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl mb-12 max-w-sm mx-auto border border-gray-200 dark:border-gray-800 reveal reveal-delay-1 shadow-inner">
        <button @click="tab='startups'" :class="tab==='startups' ? 'bg-white dark:bg-gray-800 shadow-xl text-primary-600 dark:text-primary-400 font-black border-glow' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'"
            class="tab-btn flex-1 py-3 text-sm rounded-xl transition-all duration-300 font-semibold flex items-center justify-center gap-2">
            🚀 Startups
        </button>
        <button @click="tab='corporates'" :class="tab==='corporates' ? 'bg-white dark:bg-gray-800 shadow-xl text-primary-600 dark:text-primary-400 font-black border-glow' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'"
            class="tab-btn flex-1 py-3 text-sm rounded-xl transition-all duration-300 font-semibold flex items-center justify-center gap-2">
            🏢 Corporates
        </button>
    </div>

    {{-- ── STARTUPS SECTION ── --}}
    <div x-show="tab==='startups'" x-cloak>
        @php $top3 = $startups->take(3); @endphp
        
        {{-- 3D Podium --}}
        @if($top3->count() >= 1)
        <div class="podium-container reveal reveal-delay-2">
            {{-- 2nd Place --}}
            @if($top3->count() >= 2)
            @php $s = $top3[1]; @endphp
            <div class="podium-step podium-2" style="animation-delay: 0.2s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="{{ $s->logoUrl() }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-gray-200 dark:ring-gray-600 shadow-[0_20px_40px_rgba(0,0,0,0.3)] bg-white group-hover:scale-110 transition-transform">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-silver rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">2</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md">{{ $s->companyName() }}</p>
                </div>
                <div class="mt-auto pb-6 text-center">
                    <p class="text-white/90 text-xs font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-2xl drop-shadow-md">{{ $s->lb_score }}</p>
                </div>
            </div>
            @endif

            {{-- 1st Place --}}
            @php $s = $top3[0]; @endphp
            <div class="podium-step podium-1 z-10" style="animation-delay: 0.4s">
                <div class="avatar-container text-center">
                    <div class="text-4xl mb-3 animate-bounce drop-shadow-xl">👑</div>
                    <div class="relative inline-block group">
                        <img src="{{ $s->logoUrl() }}" class="w-28 h-28 rounded-[2rem] object-cover ring-8 ring-yellow-400 shadow-[0_30px_60px_rgba(253,185,49,0.5)] bg-white group-hover:scale-110 transition-transform">
                        <span class="absolute -top-4 -right-4 w-10 h-10 rank-gold rounded-full flex items-center justify-center text-yellow-900 font-black text-lg shadow-xl border-2 border-white">1</span>
                    </div>
                    <p class="font-black text-lg mt-4 text-gray-900 dark:text-white truncate w-40 drop-shadow-md">{{ $s->companyName() }}</p>
                </div>
                <div class="mt-auto pb-8 text-center">
                    <p class="text-yellow-900/80 text-xs font-black uppercase tracking-widest mb-1">Score</p>
                    <p class="text-yellow-900 font-black text-4xl drop-shadow-md">{{ $s->lb_score }}</p>
                </div>
            </div>

            {{-- 3rd Place --}}
            @if($top3->count() >= 3)
            @php $s = $top3[2]; @endphp
            <div class="podium-step podium-3" style="animation-delay: 0.6s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="{{ $s->logoUrl() }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-orange-400 shadow-[0_20px_40px_rgba(237,143,3,0.3)] bg-white group-hover:scale-110 transition-transform">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-bronze rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">3</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md">{{ $s->companyName() }}</p>
                </div>
                <div class="mt-auto pb-4 text-center">
                    <p class="text-white/90 text-[10px] font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-xl drop-shadow-md">{{ $s->lb_score }}</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Table --}}
        @php $maxScore = $startups->first()?->lb_score ?: 1; @endphp
        <div class="glass-card-strong rounded-[2.5rem] overflow-hidden reveal reveal-delay-3 border-glow">
            <div class="grid grid-cols-12 gap-4 px-8 py-5 bg-white/50 dark:bg-gray-900/50 text-xs font-black uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 backdrop-blur-xl">
                <div class="col-span-1 text-center">Rank</div>
                <div class="col-span-4">Startup Profile</div>
                <div class="col-span-3">Performance Score</div>
                <div class="col-span-4 grid grid-cols-4 gap-2 text-center text-[10px]">
                    <span title="Connections">🔗 Conn</span>
                    <span title="Applications">📋 Apps</span>
                    <span title="Shortlisted">⭐ Short</span>
                    <span title="Badges">🏅 Badge</span>
                </div>
            </div>

            @forelse($startups as $rank => $s)
            @php $isMe = $s->id === $viewer->id; @endphp
            <a href="{{ route('profile.show', $s) }}"
                class="leaderboard-row grid grid-cols-12 gap-4 items-center px-8 py-5 border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-all duration-300 group {{ $isMe ? 'bg-primary-50/50 dark:bg-primary-900/20 border-l-4 border-l-primary-500' : '' }}">

                {{-- Rank --}}
                <div class="col-span-1 flex justify-center">
                    @if($rank === 0)
                        <span class="w-10 h-10 rank-gold rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">1</span>
                    @elseif($rank === 1)
                        <span class="w-10 h-10 rank-silver rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">2</span>
                    @elseif($rank === 2)
                        <span class="w-10 h-10 rank-bronze rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">3</span>
                    @else
                        <span class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg group-hover:bg-primary-100 dark:group-hover:bg-primary-900 group-hover:text-primary-600 transition">{{ $rank + 1 }}</span>
                    @endif
                </div>

                {{-- Company --}}
                <div class="col-span-4 flex items-center gap-4 min-w-0">
                    <img src="{{ $s->logoUrl() }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 shadow-md group-hover:scale-110 transition bg-white">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-black text-base text-gray-900 dark:text-white truncate group-hover:text-primary-600 transition">{{ $s->companyName() }}</p>
                            @if($isMe)<span class="you-badge">You</span>@endif
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-0.5">{{ $s->startupProfile?->industry?->name ?? 'Startup' }}</p>
                    </div>
                </div>

                {{-- Animated Score bar --}}
                <div class="col-span-3">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner relative">
                            <div class="h-full rounded-full bg-gradient-to-r from-primary-400 to-purple-500 score-bar relative overflow-hidden"
                                style="width: 0%"
                                x-intersect="$el.style.width = '{{ $maxScore > 0 ? round(($s->lb_score / $maxScore) * 100) : 0 }}%'">
                                <div class="absolute inset-0 bg-white/30 w-1/2 -skew-x-12 translate-x-full animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        <span class="text-lg font-black text-primary-700 dark:text-primary-300 w-10 text-right">{{ $s->lb_score }}</span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="col-span-4 grid grid-cols-4 gap-2 text-center">
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg">{{ $s->lb_connections }}</span>
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg">{{ $s->lb_applications }}</span>
                    <span class="text-sm font-black text-green-600 bg-green-50 dark:bg-green-900/20 py-1 rounded-lg">{{ $s->lb_shortlisted }}</span>
                    <span class="text-sm font-black text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 py-1 rounded-lg">{{ $s->lb_badges }}</span>
                </div>

                {{-- AI Insight (full row) --}}
                @if(!empty($s->lb_ai_insight))
                <div class="col-span-12 px-2 pb-1 flex items-center gap-2">
                    @php
                        $potentialColor = match($s->lb_ai_potential ?? 'Medium') {
                            'High'   => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                            'Low'    => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                            default  => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $potentialColor }}">
                        ✨ AI · {{ $s->lb_ai_potential ?? 'Medium' }}
                        @if(($s->lb_ai_bonus ?? 0) > 0)<span class="ml-1 opacity-70">+{{ $s->lb_ai_bonus }}</span>@endif
                    </span>
                    <span class="text-[11px] text-gray-400 dark:text-gray-500 italic truncate">{{ $s->lb_ai_insight }}</span>
                </div>
                @endif
            </a>
            @empty
            <div class="py-20 text-center text-gray-500 font-bold text-lg">No startups on the leaderboard yet.</div>
            @endforelse
        </div>
    </div>

    {{-- ── CORPORATES SECTION ── --}}
    <div x-show="tab==='corporates'" x-cloak>
        @php $top3C = $corporates->take(3); @endphp
        
        {{-- 3D Podium (Corporates) --}}
        @if($top3C->count() >= 1)
        <div class="podium-container reveal reveal-delay-2">
            @if($top3C->count() >= 2)
            @php $c = $top3C[1]; @endphp
            <div class="podium-step podium-2" style="animation-delay: 0.2s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="{{ $c->logoUrl() }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-gray-200 dark:ring-gray-600 shadow-xl bg-white group-hover:scale-110 transition">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-silver rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">2</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md">{{ $c->companyName() }}</p>
                </div>
                <div class="mt-auto pb-6 text-center">
                    <p class="text-white/90 text-xs font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-2xl drop-shadow-md">{{ $c->lb_score }}</p>
                </div>
            </div>
            @endif

            @php $c = $top3C[0]; @endphp
            <div class="podium-step podium-1 z-10" style="animation-delay: 0.4s">
                <div class="avatar-container text-center">
                    <div class="text-4xl mb-3 animate-bounce drop-shadow-xl">👑</div>
                    <div class="relative inline-block group">
                        <img src="{{ $c->logoUrl() }}" class="w-28 h-28 rounded-[2rem] object-cover ring-8 ring-yellow-400 shadow-2xl bg-white group-hover:scale-110 transition">
                        <span class="absolute -top-4 -right-4 w-10 h-10 rank-gold rounded-full flex items-center justify-center text-yellow-900 font-black text-lg shadow-xl border-2 border-white">1</span>
                    </div>
                    <p class="font-black text-lg mt-4 text-gray-900 dark:text-white truncate w-40 drop-shadow-md">{{ $c->companyName() }}</p>
                </div>
                <div class="mt-auto pb-8 text-center">
                    <p class="text-yellow-900/80 text-xs font-black uppercase tracking-widest mb-1">Score</p>
                    <p class="text-yellow-900 font-black text-4xl drop-shadow-md">{{ $c->lb_score }}</p>
                </div>
            </div>

            @if($top3C->count() >= 3)
            @php $c = $top3C[2]; @endphp
            <div class="podium-step podium-3" style="animation-delay: 0.6s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="{{ $c->logoUrl() }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-orange-400 shadow-xl bg-white group-hover:scale-110 transition">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-bronze rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">3</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md">{{ $c->companyName() }}</p>
                </div>
                <div class="mt-auto pb-4 text-center">
                    <p class="text-white/90 text-[10px] font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-xl drop-shadow-md">{{ $c->lb_score }}</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Table --}}
        @php $maxScoreC = $corporates->first()?->lb_score ?: 1; @endphp
        <div class="glass-card-strong rounded-[2.5rem] overflow-hidden reveal reveal-delay-3 border-glow">
            <div class="grid grid-cols-12 gap-4 px-8 py-5 bg-white/50 dark:bg-gray-900/50 text-xs font-black uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 backdrop-blur-xl">
                <div class="col-span-1 text-center">Rank</div>
                <div class="col-span-4">Corporate Profile</div>
                <div class="col-span-3">Performance Score</div>
                <div class="col-span-4 grid grid-cols-4 gap-2 text-center text-[10px]">
                    <span title="Challenges">📢 Chal</span>
                    <span title="Applications">📥 Apps</span>
                    <span title="Shortlisted">⭐ Short</span>
                    <span title="Badges">🏅 Badge</span>
                </div>
            </div>

            @forelse($corporates as $rank => $c)
            @php $isMe = $c->id === $viewer->id; @endphp
            <a href="{{ route('profile.show', $c) }}"
                class="leaderboard-row grid grid-cols-12 gap-4 items-center px-8 py-5 border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-all duration-300 group {{ $isMe ? 'bg-purple-50/50 dark:bg-purple-900/20 border-l-4 border-l-purple-500' : '' }}">

                <div class="col-span-1 flex justify-center">
                    @if($rank === 0)
                        <span class="w-10 h-10 rank-gold rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">1</span>
                    @elseif($rank === 1)
                        <span class="w-10 h-10 rank-silver rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">2</span>
                    @elseif($rank === 2)
                        <span class="w-10 h-10 rank-bronze rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">3</span>
                    @else
                        <span class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg group-hover:bg-purple-100 dark:group-hover:bg-purple-900 group-hover:text-purple-600 transition">{{ $rank + 1 }}</span>
                    @endif
                </div>

                <div class="col-span-4 flex items-center gap-4 min-w-0">
                    <img src="{{ $c->logoUrl() }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 shadow-md group-hover:scale-110 transition bg-white">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-black text-base text-gray-900 dark:text-white truncate group-hover:text-purple-600 transition">{{ $c->companyName() }}</p>
                            @if($isMe)<span class="you-badge">You</span>@endif
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-0.5">{{ $c->corporateProfile?->industry?->name ?? 'Corporate' }}</p>
                    </div>
                </div>

                <div class="col-span-3">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner relative">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-400 to-cyan-500 score-bar relative overflow-hidden"
                                style="width: 0%"
                                x-intersect="$el.style.width = '{{ $maxScoreC > 0 ? round(($c->lb_score / $maxScoreC) * 100) : 0 }}%'">
                                <div class="absolute inset-0 bg-white/30 w-1/2 -skew-x-12 translate-x-full animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        <span class="text-lg font-black text-blue-700 dark:text-blue-300 w-10 text-right">{{ $c->lb_score }}</span>
                    </div>
                </div>

                <div class="col-span-4 grid grid-cols-4 gap-2 text-center">
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg">{{ $c->lb_challenges }}</span>
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg">{{ $c->lb_apps }}</span>
                    <span class="text-sm font-black text-green-600 bg-green-50 dark:bg-green-900/20 py-1 rounded-lg">{{ $c->lb_shortlisted }}</span>
                    <span class="text-sm font-black text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 py-1 rounded-lg">{{ $c->lb_badges }}</span>
                </div>

                {{-- AI Insight (full row) --}}
                @if(!empty($c->lb_ai_insight))
                <div class="col-span-12 px-2 pb-1 flex items-center gap-2">
                    @php
                        $potentialColorC = match($c->lb_ai_potential ?? 'Medium') {
                            'High'  => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                            'Low'   => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                            default => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $potentialColorC }}">
                        ✨ AI · {{ $c->lb_ai_potential ?? 'Medium' }}
                        @if(($c->lb_ai_bonus ?? 0) > 0)<span class="ml-1 opacity-70">+{{ $c->lb_ai_bonus }}</span>@endif
                    </span>
                    <span class="text-[11px] text-gray-400 dark:text-gray-500 italic truncate">{{ $c->lb_ai_insight }}</span>
                </div>
                @endif
            </a>
            @empty
            <div class="py-20 text-center text-gray-500 font-bold text-lg">No corporates on the leaderboard yet.</div>
            @endforelse
        </div>
    </div>

    {{-- ── Your rank card ── --}}
    @php
        $myRank = $viewer->isStartup()
            ? $startups->search(fn($s) => $s->id === $viewer->id)
            : $corporates->search(fn($c) => $c->id === $viewer->id);
        $myEntry = $viewer->isStartup() ? $startups->get($myRank) : $corporates->get($myRank);
    @endphp
    @if($myRank !== false && $myEntry)
    <div class="mt-8 bg-gradient-to-r from-primary-600 via-purple-600 to-pink-600 rounded-[2rem] p-8 text-white flex items-center justify-between shadow-[0_30px_60px_rgba(168,85,247,0.3)] reveal reveal-delay-4 relative overflow-hidden border-glow">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9ImIiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI2IpIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PC9zdmc+')] opacity-30"></div>
        <div class="absolute inset-0 noise opacity-20"></div>
        
        <div class="flex items-center gap-6 relative z-10">
            <img src="{{ $viewer->logoUrl() }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white/40 shadow-xl bg-white">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-white/80 mb-1">Your Rank</p>
                <p class="text-5xl font-black font-outfit drop-shadow-md">#{{ $myRank + 1 }}</p>
            </div>
        </div>
        <div class="text-right relative z-10">
            <p class="text-sm font-bold uppercase tracking-widest text-white/80 mb-1">Current Score</p>
            <p class="text-5xl font-black font-outfit drop-shadow-md">{{ $myEntry->lb_score }}</p>
        </div>
        <div class="text-right hidden sm:block relative z-10">
            <p class="text-sm font-bold uppercase tracking-widest text-white/80 mb-1">To Next Rank</p>
            @php $nextEntry = $viewer->isStartup() ? $startups->get($myRank - 1) : $corporates->get($myRank - 1); @endphp
            <p class="text-3xl font-black font-outfit text-yellow-300 drop-shadow-md mt-2">
                {{ $myRank > 0 && $nextEntry ? '+'.($nextEntry->lb_score - $myEntry->lb_score) : '🏆 Number 1!' }}
            </p>
        </div>
    </div>
    @endif

</div>
@endsection
