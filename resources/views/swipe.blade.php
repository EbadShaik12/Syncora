@extends('layouts.app')
@section('title', 'Discover Partners')

@push('styles')
<style>
    .swipe-container { perspective: 1200px; transform-style: preserve-3d; }
    
    .swipe-card {
        position: absolute; inset: 0;
        transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.4s ease, box-shadow 0.4s ease;
        will-change: transform, opacity;
        transform-origin: bottom center;
    }
    
    /* Stack effect logic (handled via x-bind:style in Alpine, but defaults here for fallback) */
    .swipe-card.is-active { z-index: 10; transform: translateZ(0) translateY(0) scale(1); opacity: 1; }
    .swipe-card.is-next-1 { z-index: 9; transform: translateZ(-50px) translateY(20px) scale(0.95); opacity: 0.8; }
    .swipe-card.is-next-2 { z-index: 8; transform: translateZ(-100px) translateY(40px) scale(0.90); opacity: 0.5; }
    .swipe-card.is-hidden { z-index: 1; opacity: 0; pointer-events: none; }
    
    /* Swipe Animations */
    @keyframes swipe-left { to { transform: translateX(-150%) rotate(-15deg) translateY(50px); opacity: 0; } }
    @keyframes swipe-right { to { transform: translateX(150%) rotate(15deg) translateY(50px); opacity: 0; } }
    
    .animate-swipe-left { animation: swipe-left 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .animate-swipe-right { animation: swipe-right 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    
    .match-ring-1 { animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite; }
    .match-ring-2 { animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite 0.5s; }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
@endpush

@section('content')

<div class="max-w-xl mx-auto px-4 py-8 relative z-10" x-data="swipeApp()">
    @include('components.back-button', ['fallback' => route('dashboard'), 'label' => 'Back to Dashboard'])
    
    <div class="text-center mb-10 reveal">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 text-xs font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400 mb-4 border border-primary-200 dark:border-primary-800/50">
            Intelligent Matching
        </div>
        <h1 class="text-4xl font-black font-outfit text-gray-900 dark:text-white mb-2">
            Discover <span class="text-gradient">{{ auth()->user()->isStartup() ? 'Corporates' : 'Startups' }}</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Swipe right to express interest. Swipe left to pass.</p>
    </div>

    <div class="relative h-[650px] swipe-container reveal reveal-delay-1 flex items-center justify-center">
        
        {{-- Empty State --}}
        <div x-show="currentIndex >= cards.length" x-cloak class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
            <div class="text-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-sm p-12">
                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center mb-6 shadow-xl shadow-primary-500/30">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-3xl font-black font-outfit mb-3 text-gray-900 dark:text-white">You're caught up!</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 font-medium">You've reviewed all available {{ auth()->user()->isStartup() ? 'corporates' : 'startups' }} in your queue.</p>
                <div class="flex flex-col gap-4 mt-8 pointer-events-auto">
                    <a href="{{ route('search') }}" class="shimmer-btn bg-gradient-to-r from-primary-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:scale-105 transition text-center">Explore Database</a>
                    <form action="{{ route('swipe.reset') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-full glass-card border border-white/20 text-gray-900 dark:text-white px-8 py-4 rounded-2xl font-bold hover:bg-white/10 hover:scale-105 transition text-center">
                            Reset Swipes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cards --}}
        @foreach($cards as $index => $card)
            @php 
                // Cycle gradients for header
                $gradients = ['from-primary-500 to-purple-600', 'from-blue-500 to-cyan-500', 'from-purple-500 to-pink-500', 'from-orange-500 to-red-500'];
                $bgGradient = $gradients[$card['user']->id % count($gradients)];
            @endphp
            <div id="card-{{ $index }}"
                 class="swipe-card bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.15)] border border-gray-100 dark:border-gray-800 overflow-hidden absolute inset-x-0 mx-auto max-w-sm"
                 :class="{
                     'is-active': currentIndex === {{ $index }},
                     'is-next-1': currentIndex === {{ $index }} - 1,
                     'is-next-2': currentIndex === {{ $index }} - 2,
                     'is-hidden': currentIndex > {{ $index }} || currentIndex < {{ $index }} - 2,
                     'animate-swipe-left': isSwipingLeft === {{ $index }},
                     'animate-swipe-right': isSwipingRight === {{ $index }}
                 }">
                
                {{-- Card Header Gradient --}}
                <div class="h-40 bg-gradient-to-br {{ $bgGradient }} relative overflow-hidden">
                    <div class="absolute inset-0 noise opacity-20"></div>
                    <div class="absolute inset-0 bg-white/10 mesh-gradient opacity-30"></div>
                    
                    {{-- Compatibility Ring --}}
                    <div class="absolute top-6 right-6">
                        <div class="relative w-16 h-16 bg-white/20 backdrop-blur-md rounded-full shadow-lg flex items-center justify-center border border-white/30">
                            <svg class="w-16 h-16 absolute inset-0 -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15" fill="none"
                                    stroke="@if($card['score']['color'] === 'green') #10b981 @elseif($card['score']['color'] === 'blue') #3b82f6 @elseif($card['score']['color'] === 'yellow') #eab308 @else #ef4444 @endif"
                                    stroke-width="3" stroke-dasharray="{{ $card['score']['score'] }}, 100" stroke-linecap="round"/>
                            </svg>
                            <div class="flex flex-col items-center">
                                <span class="text-xl font-black text-white drop-shadow-md leading-none">{{ $card['score']['score'] }}</span>
                                <span class="text-[8px] text-white/90 uppercase font-bold tracking-wider mt-0.5">Match</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Avatar (Absolute to card to prevent clipping by overflow-y-auto body) --}}
                <div class="absolute top-28 left-1/2 -translate-x-1/2 z-20 pointer-events-none">
                    <div class="w-24 h-24 rounded-[1.5rem] bg-white ring-4 ring-white dark:ring-gray-900 shadow-xl overflow-hidden p-1">
                        <img src="{{ $card['user']->logoUrl() }}" alt="" class="w-full h-full object-cover rounded-xl">
                    </div>
                </div>
                
                {{-- Card Body --}}
                <div class="px-8 pb-8 h-[calc(100%-160px)] flex flex-col overflow-y-auto hide-scrollbar relative">
                    {{-- Spacer to account for absolute avatar --}}
                    <div class="h-[72px] flex-shrink-0"></div>
                    
                    {{-- Title & Info --}}
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-black font-outfit text-gray-900 dark:text-white">{{ $card['user']->companyName() }}</h2>
                        <p class="text-sm font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mt-1">{{ $card['profile']?->industry?->name ?? 'Industry' }}</p>
                        @if($card['profile']?->city)
                            <p class="text-xs font-semibold text-gray-500 mt-2 bg-gray-100 dark:bg-gray-800 inline-block px-3 py-1 rounded-full">📍 {{ $card['profile']->city }}{{ $card['profile']->state ? ', '.$card['profile']->state : '' }}</p>
                        @endif
                    </div>

                    {{-- Dynamic Content (Startup vs Corporate) --}}
                    @if($card['user']->isStartup() && $card['profile'])
                        <div class="flex flex-wrap justify-center gap-2 mb-6">
                            <span class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-xl {{ $card['profile']->stageColor() }}">{{ $card['profile']->stageLabel() }}</span>
                            @if($card['profile']->team_size)<span class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">👥 {{ $card['profile']->team_size }}</span>@endif
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 mb-6">
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed italic text-center">"{{ $card['profile']->elevator_pitch }}"</p>
                        </div>
                        @if($card['profile']->tech_tags && count($card['profile']->tech_tags) > 0)
                            <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                                @foreach(array_slice($card['profile']->tech_tags, 0, 5) as $tag)
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 border border-primary-100 dark:border-primary-800/50">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    @elseif($card['user']->isCorporate() && $card['profile'])
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-5 mb-6 border border-purple-100 dark:border-purple-800/50 relative">
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-purple-600 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md">Looking For</div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed text-center mt-2">{{ $card['profile']->problem_statement }}</p>
                        </div>
                        @if($card['profile']->seeking_technologies && count($card['profile']->seeking_technologies) > 0)
                            <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                                @foreach(array_slice($card['profile']->seeking_technologies, 0, 5) as $tag)
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-100 dark:border-purple-800/50">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    {{-- AI Insights Accordion --}}
                    <details class="mt-auto group bg-gray-50 dark:bg-gray-800/50 rounded-2xl overflow-hidden transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <summary class="cursor-pointer text-xs font-bold p-4 list-none flex items-center justify-between text-gray-900 dark:text-white select-none">
                            <span class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 flex items-center justify-center">🤖</span>
                                AI Compatibility Insights
                            </span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div class="px-4 pb-4 space-y-3 border-t border-gray-200 dark:border-gray-700 pt-3">
                            @foreach($card['score']['breakdown'] as $key => $item)
                                <div class="flex items-center gap-3 text-xs font-medium">
                                    <span class="w-24 text-gray-500 uppercase tracking-wider text-[10px] font-bold">{{ $item['label'] }}</span>
                                    <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-primary-400 to-purple-500 rounded-full transition-all" style="width: {{ $item['max'] > 0 ? ($item['score'] / $item['max']) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-white w-8 text-right">{{ round($item['score']) }}/{{ $item['max'] }}</span>
                                </div>
                            @endforeach
                            <div class="text-center mt-3 pt-3 border-t border-gray-200 dark:border-gray-700/50">
                                <a href="{{ route('profile.show', $card['user']) }}" target="_blank" class="text-xs font-bold text-primary-600 hover:text-primary-700 uppercase tracking-wider">View Full Profile ↗</a>
                            </div>
                        </div>
                    </details>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Controls --}}
    @if(count($cards) > 0)
    <div class="flex items-center justify-center gap-8 mt-12 relative z-20 reveal reveal-delay-2" x-show="currentIndex < cards.length">
        <button @click="swipeAction('skipped')" class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border-2 border-gray-100 dark:border-gray-700 flex items-center justify-center hover:scale-110 hover:border-red-400 transition-all group group-hover:rotate-12">
            <svg class="w-8 h-8 text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <button @click="swipeAction('interested')" class="w-20 h-20 rounded-[2rem] bg-gradient-to-br from-primary-500 to-pink-500 shadow-[0_15px_35px_rgba(236,72,153,0.4)] flex items-center justify-center hover:scale-110 transition-all group group-hover:-rotate-12">
            <svg class="w-10 h-10 text-white group-hover:scale-110 transition-transform drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </button>
    </div>
    <div class="text-center mt-6 reveal reveal-delay-3" x-show="currentIndex < cards.length">
        <p class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-white/50 dark:bg-gray-800/50 backdrop-blur border border-gray-200 dark:border-gray-700 text-xs font-bold text-gray-500">
            <span class="text-primary-600 dark:text-primary-400 mr-1" x-text="cards.length - currentIndex"></span> cards remaining
        </p>
    </div>
    @endif

    {{-- Match Modal (Premium Overlay) --}}
    <div x-show="matchShown" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-xl">
        <div class="relative w-full max-w-lg" x-show="matchShown" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-50 translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Glowing backdrop rings --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="absolute w-96 h-96 border border-pink-500/30 rounded-full match-ring-1"></div>
                <div class="absolute w-72 h-72 border border-primary-500/30 rounded-full match-ring-2"></div>
                <div class="absolute w-[500px] h-[500px] bg-gradient-to-tr from-primary-500/20 to-pink-500/20 rounded-full blur-[100px]"></div>
            </div>

            <div class="relative z-10 glass-card-strong border-glow rounded-[3rem] p-10 text-center shadow-[0_50px_100px_rgba(0,0,0,0.5)]">
                <div class="text-7xl mb-6 drop-shadow-xl animate-bounce">🎉</div>
                <h2 class="text-5xl font-black font-outfit bg-gradient-to-r from-primary-400 via-purple-400 to-pink-400 bg-clip-text text-transparent mb-4 drop-shadow-sm">It's a Match!</h2>
                <p class="text-gray-300 mb-10 text-lg font-medium leading-relaxed">You and <span class="font-bold text-white text-xl" x-text="matchName"></span> have shown mutual interest. It's time to build the future together.</p>
                
                <div class="flex flex-col gap-4">
                    <a :href="`/chat/${matchConnectionId}`" class="shimmer-btn w-full bg-gradient-to-r from-primary-600 to-pink-600 text-white px-8 py-4 rounded-2xl font-black text-lg shadow-[0_10px_30px_rgba(236,72,153,0.4)] hover:scale-105 transition flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        Start Conversation
                    </a>
                    <button @click="closeMatch()" class="w-full glass-card border border-white/20 text-white px-8 py-4 rounded-2xl font-bold hover:bg-white/10 transition uppercase tracking-widest text-sm">
                        Keep Swiping
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function swipeApp() {
    return {
        currentIndex: 0,
        isSwipingLeft: -1,
        isSwipingRight: -1,
        matchShown: false,
        matchName: '',
        matchConnectionId: null,
        cards: @json($cards->map(fn($c) => ['id' => $c['user']->id])),
        
        async swipeAction(action) {
            if (this.currentIndex >= this.cards.length) return;
            
            const cardIndex = this.currentIndex;
            const card = this.cards[cardIndex];
            
            // Trigger animation class
            if (action === 'skipped') this.isSwipingLeft = cardIndex;
            else this.isSwipingRight = cardIndex;
            
            // Wait for animation slightly before moving index
            setTimeout(() => {
                this.currentIndex++;
            }, 300);

            try {
                const res = await fetch('{{ route("swipe.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ target_id: card.id, action: action })
                });
                const data = await res.json();
                
                if (data.matched) {
                    setTimeout(() => {
                        this.matchName = data.target_name;
                        this.matchConnectionId = data.connection_id;
                        this.matchShown = true;
                        if(typeof confetti === 'function') {
                            confetti({ particleCount: 150, spread: 100, origin: { y: 0.6 }, colors: ['#6366f1', '#a855f7', '#ec4899', '#ffffff'] });
                        }
                    }, 400); // Wait for card to fly off before showing match
                }
            } catch (e) {
                console.error("Swipe failed", e);
            }
        },
        closeMatch() {
            this.matchShown = false;
        }
    }
}
</script>
@endpush
@endsection
