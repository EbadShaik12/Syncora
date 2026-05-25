@extends('layouts.app')
@section('title', 'Chat with ' . $other->companyName())

@push('styles')
<style>
    /* Scrollbar */
    #messages-container::-webkit-scrollbar { width: 6px; }
    #messages-container::-webkit-scrollbar-thumb { background: rgba(168,85,247,0.3); border-radius: 10px; }
    #messages-container:hover::-webkit-scrollbar-thumb { background: rgba(168,85,247,0.6); }

    /* Typing dots */
    .typing-dot { animation: typingBounce 1.2s infinite; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-5px); opacity: 1; }
    }

    /* Message bubble entrance */
    .msg-bubble { animation: msgPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-origin: bottom center; }
    @keyframes msgPop {
        from { transform: scale(0.9) translateY(10px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
    }

    /* Date separator */
    .date-separator {
        display: flex; align-items: center; gap: 12px;
        margin: 24px 0; color: #9ca3af; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
    }
    .date-separator::before, .date-separator::after {
        content: ''; flex: 1; height: 1px; background: currentColor; opacity: 0.2;
    }

    /* Emoji picker */
    .emoji-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 4px; }
    .emoji-btn { font-size: 1.5rem; cursor: pointer; padding: 6px; border-radius: 10px; transition: all 0.2s; text-align: center; }
    .emoji-btn:hover { background: rgba(168,85,247,0.15); transform: scale(1.1); }

    /* Sidebar hover */
    .conv-item { transition: all 0.2s ease; border-left: 3px solid transparent; }
    .conv-item.active { background: linear-gradient(90deg, rgba(168,85,247,0.1) 0%, transparent 100%); border-left: 3px solid #a855f7; }
    .conv-item:hover:not(.active) { background: rgba(168,85,247,0.05); }

    /* Input focus ring */
    #msg-input:focus { outline: none; }
    
    .chat-layout { height: calc(100vh - 5rem); }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 chat-layout relative z-10">
    <div class="flex h-full glass-card-strong rounded-3xl overflow-hidden border-glow shadow-2xl" x-data="chatApp()">

        {{-- ══════════════ SIDEBAR ══════════════ --}}
        <div class="hidden lg:flex flex-col w-80 xl:w-96 bg-white/50 dark:bg-gray-900/50 border-r border-gray-200 dark:border-gray-800/50 flex-shrink-0 backdrop-blur-xl">

            {{-- Sidebar header --}}
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800/50 flex items-center justify-between bg-white/30 dark:bg-gray-900/30">
                <div>
                    <h2 class="font-black text-xl font-outfit text-gray-900 dark:text-white">Messages</h2>
                    <p class="text-[10px] font-bold text-primary-500 uppercase tracking-widest mt-0.5">{{ $connections->count() }} active</p>
                </div>
                <a href="{{ route('chat.index') }}" class="p-2 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition shadow-sm border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
            </div>

            {{-- Search inside sidebar --}}
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800/50">
                <div class="relative group">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search conversations…"
                        x-model="sidebarSearch"
                        class="w-full pl-11 pr-4 py-3 text-sm bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-200 dark:border-gray-700 outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all font-medium text-gray-900 dark:text-white shadow-sm">
                </div>
            </div>

            {{-- Conversations list --}}
            <div class="flex-1 overflow-y-auto hide-scrollbar">
                @foreach($connections as $c)
                    @php $o = $c->otherUser(auth()->id()); @endphp
                    <a href="{{ route('chat.show', $c) }}"
                        class="conv-item flex items-center gap-4 px-5 py-4 border-b border-gray-100 dark:border-gray-800/30 {{ $c->id === $connection->id ? 'active' : '' }}">
                        <div class="relative flex-shrink-0">
                            <img src="{{ $o->logoUrl() }}" alt="" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-white dark:ring-gray-900 shadow-sm bg-white">
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full ring-2 ring-white dark:ring-gray-900 shadow-sm border border-green-400"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="font-bold text-sm truncate text-gray-900 dark:text-white">{{ $o->companyName() }}</p>
                                @if($c->latestMessage)
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider flex-shrink-0">{{ $c->latestMessage->created_at->diffForHumans(null, true) }}</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between gap-2 mt-1">
                                <p class="text-xs font-medium text-gray-500 truncate">
                                    @if($c->latestMessage)
                                        @if($c->latestMessage->sender_id === auth()->id())
                                            <span class="text-gray-400 font-bold">You: </span>
                                        @endif
                                        {{ $c->latestMessage->content }}
                                    @else
                                        <span class="italic font-bold text-primary-500">New match! 👋</span>
                                    @endif
                                </p>
                                @if(($c->unread_count ?? 0) > 0)
                                    <span class="flex-shrink-0 w-5 h-5 bg-gradient-to-br from-primary-500 to-pink-500 text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-md">
                                        {{ $c->unread_count > 9 ? '9+' : $c->unread_count }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ══════════════ MAIN CHAT ══════════════ --}}
        <div class="flex-1 flex flex-col min-w-0 bg-white/80 dark:bg-gray-900/80 relative backdrop-blur-xl">

            {{-- Chat header --}}
            <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-800/50 bg-white/50 dark:bg-gray-900/50 backdrop-blur-md shadow-sm z-10">
                <a href="{{ route('chat.index') }}" class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>

                <div class="relative">
                    <img src="{{ $other->logoUrl() }}" alt="" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-white dark:ring-gray-900 shadow-md bg-white">
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full ring-2 ring-white dark:ring-gray-900 shadow-sm border border-green-400"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-black text-lg truncate text-gray-900 dark:text-white font-outfit">{{ $other->companyName() }}</p>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest truncate mt-0.5" x-show="!isTyping">
                        {{ $other->profile()?->industry?->name ?? ($other->isStartup() ? 'Startup' : 'Corporate') }}
                        @if($other->profile()?->city) <span class="mx-1">·</span> {{ $other->profile()->city }} @endif
                    </p>
                    <p class="text-xs font-black text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-purple-500 flex items-center gap-1.5 mt-0.5" x-show="isTyping" x-cloak>
                        <span class="uppercase tracking-widest">Typing</span>
                        <span class="inline-flex gap-0.5 items-end">
                            <span class="typing-dot w-1 h-1 bg-primary-500 rounded-full inline-block"></span>
                            <span class="typing-dot w-1 h-1 bg-primary-500 rounded-full inline-block"></span>
                            <span class="typing-dot w-1 h-1 bg-primary-500 rounded-full inline-block"></span>
                        </span>
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(auth()->user()->isCorporate() && $other->isStartup())
                        <a href="{{ route('profile.pdf', $other) }}"
                            class="hidden sm:flex items-center gap-2 px-4 py-2 text-xs font-bold bg-purple-600 hover:bg-purple-700 text-white rounded-xl transition uppercase tracking-wider shadow-md shadow-purple-600/20" title="Download Startup PDF Report">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download PDF
                        </a>
                    @endif
                    <a href="{{ route('profile.show', $other) }}"
                        class="hidden sm:flex items-center gap-2 px-4 py-2 text-xs font-bold bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-xl hover:bg-primary-100 dark:hover:bg-primary-800/50 transition uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profile
                    </a>
                    @if($other->isStartup() && $other->startupProfile?->website)
                        <a href="{{ $other->startupProfile->website }}" target="_blank"
                            class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="Visit website">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- ══════════════ PHASE 5: RATING & REVIEW ══════════════ --}}
            <div class="px-6 py-3 bg-gray-50/40 dark:bg-[#0d0d12]/30 border-b border-gray-200 dark:border-gray-800/50 flex flex-col gap-3 relative z-20">
                <div x-data="{ openReview: false }" class="w-full">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black uppercase tracking-widest text-primary-500">Phase 5: Review & Feedback</span>
                            @if($connection->hasRated(auth()->id()))
                                <span class="px-2 py-0.5 rounded-lg bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-300 text-[10px] font-black uppercase tracking-wider">Completed</span>
                            @else
                                <span class="px-2 py-0.5 rounded-lg bg-yellow-100 dark:bg-yellow-950/40 text-yellow-700 dark:text-yellow-300 text-[10px] font-black uppercase tracking-wider animate-pulse">Pending Review</span>
                            @endif
                        </div>
                        
                        <button @click="openReview = !openReview" class="text-xs font-bold text-primary-600 hover:text-primary-700 transition flex items-center gap-1">
                            <span x-text="openReview ? 'Hide Reviews' : 'Show Reviews & Ratings'"></span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="openReview ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <div x-show="openReview" x-cloak x-transition class="mt-4 grid md:grid-cols-2 gap-4 pt-3 border-t border-gray-100 dark:border-gray-800/40">
                        
                        {{-- Column 1: My Review of Partner --}}
                        <div class="glass-card rounded-2xl p-4 border border-gray-100 dark:border-gray-850/80">
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-2">My Review of {{ $other->companyName() }}</h4>
                            
                            @if($connection->hasRated(auth()->id()))
                                @php $myReviewData = $connection->getRatingAndReview(auth()->id()); @endphp
                                <div class="space-y-2">
                                    <div class="flex items-center gap-1 text-yellow-500 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span>{{ $i <= $myReviewData['rating'] ? '★' : '☆' }}</span>
                                        @endfor
                                        <span class="text-xs font-bold text-gray-400 ml-1">({{ $myReviewData['rating'] }}/5)</span>
                                    </div>
                                    @if($myReviewData['review'])
                                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium italic">"{{ $myReviewData['review'] }}"</p>
                                    @endif
                                </div>
                            @else
                                <form method="POST" action="{{ route('connections.rate', $connection) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Rating</label>
                                        <div class="flex items-center gap-1.5" x-data="{ hoverRating: 0, activeRating: 5 }">
                                            <input type="hidden" name="rating" :value="activeRating">
                                            <template x-for="star in [1, 2, 3, 4, 5]">
                                                <button type="button" 
                                                        @click="activeRating = star"
                                                        @mouseover="hoverRating = star"
                                                        @mouseleave="hoverRating = 0"
                                                        class="text-xl transition-all duration-150 focus:outline-none"
                                                        :class="star <= (hoverRating || activeRating) ? 'text-yellow-500 scale-110' : 'text-gray-300 dark:text-gray-700'">
                                                    ★
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Review Comments</label>
                                        <textarea name="review" rows="2" placeholder="Share your experience working with this partner..." 
                                                  class="w-full px-3 py-1.5 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 outline-none focus:border-primary-500 text-gray-900 dark:text-white font-medium"></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-purple-600 text-white font-bold py-2 rounded-xl text-xs hover:scale-[1.01] transition shadow-md shadow-primary-500/10">
                                        Submit Rating & Review
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Column 2: Partner's Review of Me --}}
                        <div class="glass-card rounded-2xl p-4 border border-gray-100 dark:border-gray-855/80 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-2">Feedback From {{ $other->companyName() }}</h4>
                                
                                @php $partnerReviewData = $connection->getRatingOfUser(auth()->id()); @endphp
                                @if(!is_null($partnerReviewData['rating']))
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-1 text-yellow-500 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span>{{ $i <= $partnerReviewData['rating'] ? '★' : '☆' }}</span>
                                            @endfor
                                            <span class="text-xs font-bold text-gray-400 ml-1">({{ $partnerReviewData['rating'] }}/5)</span>
                                        </div>
                                        @if($partnerReviewData['review'])
                                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium italic">"{{ $partnerReviewData['review'] }}"</p>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <span class="text-2xl mb-1 block animate-pulse">⏳</span>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Awaiting partner review</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Gamification Badge Indicator --}}
                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800/40 flex items-center justify-between text-[10px] font-bold text-gray-400">
                                <span>🌟 Score Contribution</span>
                                <span class="text-green-500 font-extrabold uppercase">+10 LB Points</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Messages area --}}
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-2 relative z-0" id="messages-container" x-ref="messages">
                
                {{-- Decorative background element --}}
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-primary-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

                <template x-for="(msg, idx) in messages" :key="msg.id">
                    <div>
                        {{-- Date separator --}}
                        <template x-if="idx === 0 || messages[idx-1].date_label !== msg.date_label">
                            <div class="date-separator" x-text="msg.date_label"></div>
                        </template>

                        {{-- Message bubble --}}
                        <div :class="msg.is_mine ? 'justify-end' : 'justify-start'" class="flex items-end gap-3 mb-2 msg-bubble">
                            
                            {{-- Avatar for other person --}}
                            <template x-if="!msg.is_mine">
                                <div class="flex-shrink-0 w-8 h-8"
                                    :style="(idx === messages.length - 1 || messages[idx+1]?.is_mine || messages[idx+1]?.date_label !== msg.date_label) ? '' : 'visibility:hidden'">
                                    <img src="{{ $other->logoUrl() }}" class="w-8 h-8 rounded-xl object-cover ring-2 ring-white dark:ring-gray-900 shadow-sm bg-white">
                                </div>
                            </template>

                            <div :class="msg.is_mine ? 'items-end' : 'items-start'" class="flex flex-col max-w-[75%] sm:max-w-[60%]">
                                <div :class="msg.is_mine
                                    ? 'bg-gradient-to-br from-primary-600 to-purple-600 text-white rounded-t-2xl rounded-bl-2xl rounded-br-sm shadow-md shadow-primary-500/20'
                                    : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-t-2xl rounded-br-2xl rounded-bl-sm shadow-md border border-gray-100 dark:border-gray-700/50'"
                                    class="px-5 py-3">
                                    <p class="text-[15px] font-medium leading-relaxed whitespace-pre-wrap break-words" x-text="msg.content"></p>
                                </div>
                                <div :class="msg.is_mine ? 'justify-end' : 'justify-start'" class="flex items-center gap-1.5 mt-1 px-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider" x-text="msg.created_at"></span>
                                    <template x-if="msg.is_mine">
                                        <span>
                                            <svg x-show="msg.read_at" class="w-3.5 h-3.5 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <svg x-show="!msg.read_at" class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Empty state --}}
                <div x-show="messages.length === 0" class="flex flex-col items-center justify-center h-full py-24 text-center">
                    <div class="text-6xl mb-6 drop-shadow-xl animate-bounce">🎉</div>
                    <p class="font-black text-2xl mb-2 font-outfit text-gray-900 dark:text-white">You matched with {{ $other->companyName() }}!</p>
                    <p class="text-base text-gray-500 font-medium max-w-sm leading-relaxed">Break the ice — introduce yourself or share what you're looking for in a partnership.</p>
                </div>

                {{-- Typing indicator --}}
                <div x-show="isTyping" x-cloak class="flex items-end gap-3 mt-4">
                    <img src="{{ $other->logoUrl() }}" class="w-8 h-8 rounded-xl object-cover flex-shrink-0 bg-white ring-2 ring-white dark:ring-gray-900 shadow-sm">
                    <div class="bg-white dark:bg-gray-800 px-5 py-4 rounded-t-2xl rounded-br-2xl rounded-bl-sm shadow-md border border-gray-100 dark:border-gray-700/50">
                        <div class="flex gap-1.5 items-center">
                            <span class="typing-dot w-2 h-2 bg-primary-400 rounded-full"></span>
                            <span class="typing-dot w-2 h-2 bg-primary-400 rounded-full"></span>
                            <span class="typing-dot w-2 h-2 bg-primary-400 rounded-full"></span>
                        </div>
                    </div>
                </div>

                <div id="bottom-anchor" class="h-4"></div>
            </div>

            {{-- Emoji picker panel --}}
            <div x-show="showEmoji" x-cloak @click.outside="showEmoji = false"
                class="absolute bottom-full mb-4 left-6 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50">
                <div class="emoji-grid w-64 max-h-48 overflow-y-auto hide-scrollbar">
                    <template x-for="e in emojis" :key="e">
                        <button @click="addEmoji(e)" type="button" class="emoji-btn" x-text="e"></button>
                    </template>
                </div>
            </div>

            {{-- Input area --}}
            <div class="border-t border-gray-200 dark:border-gray-800/50 bg-white/80 dark:bg-gray-900/80 px-6 py-4 backdrop-blur-md">
                <form @submit.prevent="sendMessage()" class="relative flex items-end gap-3">
                    
                    {{-- Input Wrapper --}}
                    <div class="flex-1 relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm focus-within:ring-4 focus-within:ring-primary-500/20 focus-within:border-primary-500 transition-all flex items-end pr-2">
                        
                        <button type="button" @click="showEmoji = !showEmoji"
                            :class="showEmoji ? 'text-primary-500' : 'text-gray-400 hover:text-primary-500'"
                            class="p-3 transition-colors flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>

                        <textarea
                            id="msg-input"
                            x-model="newMessage"
                            @keydown.enter.prevent="if (!$event.shiftKey) { sendMessage(); } else { newMessage += '\n'; }"
                            @input="handleTyping()"
                            placeholder="Type your message..."
                            rows="1"
                            class="w-full resize-none py-3.5 px-2 bg-transparent text-[15px] font-medium outline-none max-h-32 overflow-y-auto dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 hide-scrollbar"
                            style="min-height: 52px;"
                            :style="'height: auto; height: ' + Math.min(newMessage.split('\n').length, 4) * 24 + 52 + 'px'"
                        ></textarea>
                    </div>

                    {{-- Send button --}}
                    <button type="submit"
                        :disabled="!newMessage.trim() || sending"
                        :class="newMessage.trim() ? 'bg-gradient-to-r from-primary-600 to-purple-600 shadow-lg shadow-primary-500/30 hover:scale-105 hover:-translate-y-1' : 'bg-gray-200 dark:bg-gray-700 cursor-not-allowed'"
                        class="h-[52px] w-[52px] rounded-2xl text-white transition-all duration-300 flex-shrink-0 flex items-center justify-center">
                        <svg x-show="!sending" class="w-6 h-6 translate-x-px" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                        <svg x-show="sending" x-cloak class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                    </button>
                </form>
                <div class="flex justify-between items-center mt-2 px-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Shift + Enter for new line</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1"><svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg> End-to-end secured</p>
                </div>
            </div>
        </div>
    </div>
</div>

@php
$messagesJson = $messages->map(function($m) {
    return [
        'id'         => $m->id,
        'content'    => $m->content,
        'sender_id'  => $m->sender_id,
        'created_at' => $m->created_at->format('h:i A'),
        'date_label' => $m->created_at->isToday() ? 'Today' : ($m->created_at->isYesterday() ? 'Yesterday' : $m->created_at->format('M d, Y')),
        'is_mine'    => $m->sender_id === auth()->id(),
        'read_at'    => $m->read_at ? $m->read_at->format('h:i A') : null,
    ];
})->values()->toArray();
@endphp
@push('scripts')
<script>
function chatApp() {
    return {
        messages: @json($messagesJson),
        newMessage:    '',
        lastId:        {{ $messages->last()?->id ?? 0 }},
        isTyping:      false,
        showEmoji:     false,
        sending:       false,
        sidebarSearch: '',
        typingTimer:   null,
        sendUrl:       '{{ route("chat.send", $connection) }}',
        fetchUrl:      '{{ route("chat.fetch", $connection) }}',
        typingSetUrl:  '{{ route("chat.typing", $connection) }}',
        typingGetUrl:  '{{ route("chat.typing.get", $connection) }}',
        csrfToken:     document.querySelector('meta[name="csrf-token"]').content,

        emojis: ['😊','😂','🎉','👍','🔥','💡','🚀','💼','🤝','✅','❤️','⭐','🎯','💪','🙏',
                 '😎','🤔','💰','📈','🌟','👏','🎊','🏆','💬','📧','🔗','🎨','📱','💻','🌍'],

        init() {
            this.scrollDown();
            this.startPolling();
            this.pollTyping();
        },

        scrollDown(smooth = false) {
            this.$nextTick(() => {
                const el = this.$refs.messages;
                if (!el) return;
                el.scrollTo({ top: el.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
            });
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.sending) return;
            const content = this.newMessage.trim();
            this.newMessage = '';
            this.showEmoji = false;
            this.sending   = true;

            // Optimistic UI
            const tempMsg = {
                id: 'temp-' + Date.now(),
                content,
                sender_id:  {{ auth()->id() }},
                created_at: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
                date_label: 'Today',
                is_mine:    true,
                read_at:    null,
            };
            this.messages.push(tempMsg);
            this.scrollDown(true);

            try {
                const res  = await fetch(this.sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({ content }),
                });
                const data = await res.json();
                // Replace temp with real
                const idx = this.messages.findIndex(m => m.id === tempMsg.id);
                if (idx !== -1) this.messages.splice(idx, 1, data.message);
                this.lastId = data.message.id;
            } catch (e) {
                // Restore on failure
                this.newMessage = content;
                this.messages = this.messages.filter(m => m.id !== tempMsg.id);
            } finally {
                this.sending = false;
            }
        },

        handleTyping() {
            clearTimeout(this.typingTimer);
            fetch(this.typingSetUrl, {
                method:  'POST',
                headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Content-Type': 'application/json' },
                body:    JSON.stringify({}),
            }).catch(() => {});
            this.typingTimer = setTimeout(() => {}, 3000);
        },

        async startPolling() {
            setInterval(async () => {
                try {
                    const res  = await fetch(`${this.fetchUrl}?after=${this.lastId}`);
                    const data = await res.json();

                    if (data.messages?.length) {
                        data.messages.forEach(m => {
                            if (!this.messages.find(x => x.id === m.id)) {
                                this.messages.push(m);
                                this.lastId = Math.max(this.lastId, m.id);
                            }
                        });
                        this.scrollDown(true);
                    }

                    // Update read receipts
                    if (data.last_read_sent_id) {
                        this.messages.forEach(m => {
                            if (m.is_mine && m.id <= data.last_read_sent_id && !m.read_at) {
                                m.read_at = '✓';
                            }
                        });
                    }
                } catch (e) {}
            }, 1500);
        },

        async pollTyping() {
            setInterval(async () => {
                try {
                    const res  = await fetch(this.typingGetUrl);
                    const data = await res.json();
                    this.isTyping = data.typing;
                } catch (e) {}
            }, 2000);
        },

        addEmoji(e) {
            this.newMessage += e;
            this.showEmoji = false;
            document.getElementById('msg-input').focus();
        },
    };
}
</script>
@endpush
@endsection
