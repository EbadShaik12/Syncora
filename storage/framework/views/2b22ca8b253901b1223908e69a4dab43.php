<?php $__env->startSection('title', 'Discover Partners'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-xl mx-auto px-4 py-8 relative z-10" x-data="swipeApp()">
    <?php echo $__env->make('components.back-button', ['fallback' => route('dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="text-center mb-10 reveal">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 text-xs font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400 mb-4 border border-primary-200 dark:border-primary-800/50">
            Intelligent Matching
        </div>
        <h1 class="text-4xl font-black font-outfit text-gray-900 dark:text-white mb-2">
            Discover <span class="text-gradient"><?php echo e(auth()->user()->isStartup() ? 'Corporates' : 'Startups'); ?></span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Swipe right to express interest. Swipe left to pass.</p>
    </div>

    <div class="relative h-[650px] swipe-container reveal reveal-delay-1 flex items-center justify-center">
        
        
        <div x-show="currentIndex >= cards.length" x-cloak class="absolute inset-0 flex items-center justify-center z-20">
            <div class="text-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-sm p-12">
                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center mb-6 shadow-xl shadow-primary-500/30">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-3xl font-black font-outfit mb-3 text-gray-900 dark:text-white">You're caught up!</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 font-medium">You've reviewed all available <?php echo e(auth()->user()->isStartup() ? 'corporates' : 'startups'); ?> in your queue.</p>
                <div class="flex flex-col gap-4 mt-8 pointer-events-auto">
                    <a href="<?php echo e(route('search')); ?>" class="shimmer-btn bg-gradient-to-r from-primary-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:scale-105 transition text-center">Explore Database</a>
                    <form action="<?php echo e(route('swipe.reset')); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full glass-card border border-white/20 text-gray-900 dark:text-white px-8 py-4 rounded-2xl font-bold hover:bg-white/10 hover:scale-105 transition text-center">
                            Reset Swipes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php 
                // Cycle gradients for header
                $gradients = ['from-primary-500 to-purple-600', 'from-blue-500 to-cyan-500', 'from-purple-500 to-pink-500', 'from-orange-500 to-red-500'];
                $bgGradient = $gradients[$card['user']->id % count($gradients)];
            ?>
            <div id="card-<?php echo e($index); ?>"
                 class="swipe-card bg-white dark:bg-[#0d0d12] rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.15)] border border-slate-200 dark:border-zinc-800 overflow-hidden absolute inset-x-0 mx-auto max-w-sm"
                 :class="{
                     'is-active': currentIndex === <?php echo e($index); ?>,
                     'is-next-1': currentIndex === <?php echo e($index); ?> - 1,
                     'is-next-2': currentIndex === <?php echo e($index); ?> - 2,
                     'is-hidden': currentIndex > <?php echo e($index); ?> || currentIndex < <?php echo e($index); ?> - 2,
                     'animate-swipe-left': isSwipingLeft === <?php echo e($index); ?>,
                     'animate-swipe-right': isSwipingRight === <?php echo e($index); ?>

                 }">
                
                <?php if(auth()->user()->isStartup()): ?>
                    
                    
                    
                    <div class="p-4 flex items-center justify-between border-b border-slate-100 dark:border-zinc-800/80 bg-white dark:bg-[#0d0d12] flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <img src="<?php echo e($card['user']->logoUrl()); ?>" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-zinc-800">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1">
                                    <span class="font-bold text-xs text-slate-900 dark:text-white truncate"><?php echo e($card['user']->companyName()); ?></span>
                                    <span class="text-blue-500 text-[10px]" title="Verified Corporate Partner">✔</span>
                                </div>
                                <p class="text-[9px] text-slate-500 dark:text-zinc-400 font-semibold truncate"><?php echo e($card['profile']?->industry?->name ?? 'Corporate Partner'); ?></p>
                                <p class="text-[8px] text-slate-400 dark:text-zinc-500 flex items-center gap-1 mt-0.5 font-medium">
                                    <?php echo e($card['challenge']->created_at ? $card['challenge']->created_at->diffForHumans() : 'Recently'); ?> • 🌍
                                </p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-100 dark:border-purple-800/40">Challenge</span>
                    </div>

                    
                    <div class="px-5 py-4 h-[calc(100%-72px)] flex flex-col overflow-y-auto hide-scrollbar bg-white dark:bg-[#0d0d12]">
                        
                        
                        <p class="text-xs text-slate-600 dark:text-zinc-300 leading-relaxed mb-4 whitespace-pre-line font-medium">
                            <?php echo e(Str::limit($card['challenge']->description, 160)); ?>

                        </p>

                        
                        <div class="border border-slate-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm bg-slate-50/50 dark:bg-zinc-900/10 mb-4 hover:border-purple-400 dark:hover:border-purple-500/50 transition duration-200 flex flex-col flex-shrink-0 cursor-pointer"
                             @click="openChallengeDetails(<?php echo e($index); ?>)">
                            
                            
                            <div class="h-20 bg-gradient-to-br <?php echo e($bgGradient); ?> relative overflow-hidden flex items-center justify-center p-3 text-center">
                                <div class="absolute inset-0 noise opacity-20"></div>
                                <div class="absolute inset-0 bg-white/10 mesh-gradient opacity-30"></div>
                                <h4 class="text-xs font-black font-outfit text-white drop-shadow-md line-clamp-2 relative z-10 pr-6"><?php echo e($card['challenge']->title); ?></h4>
                                
                                
                                <div class="absolute top-2 right-2">
                                    <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-full border border-white/30 flex flex-col items-center justify-center shadow">
                                        <span class="text-[10px] font-black text-white leading-none"><?php echo e($card['score']['score']); ?>%</span>
                                        <span class="text-[4px] text-white/90 uppercase font-extrabold tracking-widest leading-none mt-0.5">Match</span>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="p-3 space-y-2 text-[10px]">
                                <div class="flex justify-between items-center border-b border-slate-100 dark:border-zinc-800/80 pb-1.5">
                                    <span class="text-slate-400 dark:text-zinc-500 font-bold uppercase tracking-wider text-[9px]">Budget Pool</span>
                                    <span class="font-extrabold text-purple-600 dark:text-purple-400">₹<?php echo e(number_format($card['challenge']->budget_min)); ?> - ₹<?php echo e(number_format($card['challenge']->budget_max)); ?></span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-100 dark:border-zinc-800/80 pb-1.5">
                                    <span class="text-slate-400 dark:text-zinc-500 font-bold uppercase tracking-wider text-[9px]">Deadline</span>
                                    <span class="font-bold text-slate-700 dark:text-zinc-300"><?php echo e($card['challenge']->deadline->format('M d, Y')); ?></span>
                                </div>

                                <?php if(is_array($card['challenge']->required_tags) && count($card['challenge']->required_tags) > 0): ?>
                                    <div class="flex flex-wrap gap-1 pt-1">
                                        <?php $__currentLoopData = array_slice($card['challenge']->required_tags, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-[9px] font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-1.5 py-0.5 rounded border border-primary-100 dark:border-primary-800/40">#<?php echo e($tag); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <?php if($card['challenge']->attachment_path): ?>
                            <div class="mb-4 flex-shrink-0">
                                <a href="<?php echo e(asset('storage/' . $card['challenge']->attachment_path)); ?>" target="_blank" class="inline-flex items-center gap-1.5 w-full p-2.5 bg-purple-50 dark:bg-purple-950/20 border border-purple-200/50 dark:border-purple-800/50 rounded-xl text-[10px] font-bold text-purple-700 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition shadow-sm">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Brief: <?php echo e(Str::limit($card['challenge']->attachment_filename, 24)); ?>

                                </a>
                            </div>
                        <?php endif; ?>

                        
                        <div class="flex items-center justify-between text-[10px] text-slate-450 dark:text-zinc-550 border-t border-slate-100 dark:border-zinc-800/80 pt-3 mt-auto flex-shrink-0 font-medium">
                            <div class="flex items-center gap-1">
                                <span class="text-xs">👍❤️</span>
                                <span><?php echo e(4 + ($card['challenge']->id * 2)); ?> applicants • 3 comments</span>
                            </div>
                            <span>8 shares</span>
                        </div>
                    </div>

                <?php else: ?>
                    
                    
                    
                    <div class="h-40 bg-gradient-to-br <?php echo e($bgGradient); ?> relative overflow-hidden">
                        <div class="absolute inset-0 noise opacity-20"></div>
                        <div class="absolute inset-0 bg-white/10 mesh-gradient opacity-30"></div>
                        
                        
                        <div class="absolute top-6 right-6">
                            <div class="relative w-16 h-16 bg-white/20 backdrop-blur-md rounded-full shadow-lg flex items-center justify-center border border-white/30">
                                <svg class="w-16 h-16 absolute inset-0 -rotate-90" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="15" fill="none"
                                        stroke="<?php if($card['score']['color'] === 'green'): ?> #10b981 <?php elseif($card['score']['color'] === 'blue'): ?> #3b82f6 <?php elseif($card['score']['color'] === 'yellow'): ?> #eab308 <?php else: ?> #ef4444 <?php endif; ?>"
                                        stroke-width="3" stroke-dasharray="<?php echo e($card['score']['score']); ?>, 100" stroke-linecap="round"/>
                                </svg>
                                <div class="flex flex-col items-center">
                                    <span class="text-xl font-black text-white drop-shadow-md leading-none"><?php echo e($card['score']['score']); ?></span>
                                    <span class="text-[8px] text-white/90 uppercase font-bold tracking-wider mt-0.5">Match</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="absolute top-28 left-1/2 -translate-x-1/2 z-20 pointer-events-none">
                        <div class="w-24 h-24 rounded-[1.5rem] bg-white ring-4 ring-white dark:ring-gray-900 shadow-xl overflow-hidden p-1">
                            <img src="<?php echo e($card['user']->logoUrl()); ?>" alt="" class="w-full h-full object-cover rounded-xl">
                        </div>
                    </div>
                    
                    
                    <div class="px-8 pb-8 h-[calc(100%-160px)] flex flex-col overflow-y-auto hide-scrollbar relative">
                        <div class="h-[72px] flex-shrink-0"></div>
                        
                        
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-black font-outfit text-gray-900 dark:text-white"><?php echo e($card['user']->companyName()); ?></h2>
                            <p class="text-sm font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mt-1"><?php echo e($card['profile']?->industry?->name ?? 'Industry'); ?></p>
                            <?php if($card['profile']?->city): ?>
                                <p class="text-xs font-semibold text-gray-500 mt-2 bg-gray-100 dark:bg-gray-800 inline-block px-3 py-1 rounded-full font-outfit">📍 <?php echo e($card['profile']->city); ?><?php echo e($card['profile']->state ? ', '.$card['profile']->state : ''); ?></p>
                            <?php endif; ?>
                        </div>

                        
                        <?php if($card['user']->isStartup() && $card['profile']): ?>
                            <div class="flex flex-wrap justify-center gap-2 mb-6">
                                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-xl <?php echo e($card['profile']->stageColor()); ?>"><?php echo e($card['profile']->stageLabel()); ?></span>
                                <?php if($card['profile']->team_size): ?><span class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">👥 <?php echo e($card['profile']->team_size); ?></span><?php endif; ?>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-805/50 rounded-2xl p-4 mb-6">
                                <p class="text-sm text-gray-750 dark:text-gray-300 font-medium leading-relaxed italic text-center">"<?php echo e($card['profile']->elevator_pitch); ?>"</p>
                            </div>
                            <?php if($card['profile']->tech_tags && count($card['profile']->tech_tags) > 0): ?>
                                <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                                    <?php $__currentLoopData = array_slice($card['profile']->tech_tags, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 border border-primary-100 dark:border-primary-800/40">#<?php echo e($tag); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        
                        <details class="mt-auto group bg-gray-50 dark:bg-gray-800/50 rounded-2xl overflow-hidden transition-all duration-300 border border-gray-100 dark:border-gray-700">
                            <summary class="cursor-pointer text-xs font-bold p-4 list-none flex items-center justify-between text-gray-900 dark:text-white select-none">
                                <span class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 flex items-center justify-center">🤖</span>
                                    AI Compatibility Insights
                                </span>
                                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </summary>
                            <div class="px-4 pb-4 space-y-3 border-t border-gray-200 dark:border-gray-700 pt-3">
                                <?php $__currentLoopData = $card['score']['breakdown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center gap-3 text-xs font-medium">
                                        <span class="w-24 text-gray-500 uppercase tracking-wider text-[10px] font-bold"><?php echo e($item['label']); ?></span>
                                        <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden shadow-inner">
                                            <div class="h-full bg-gradient-to-r from-primary-400 to-purple-500 rounded-full transition-all" style="width: <?php echo e($item['max'] > 0 ? ($item['score'] / $item['max']) * 100 : 0); ?>%"></div>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white w-8 text-right"><?php echo e(round($item['score'])); ?>/<?php echo e($item['max']); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="text-center mt-3 pt-3 border-t border-gray-200 dark:border-gray-700/50">
                                    <a href="<?php echo e(route('profile.show', $card['user'])); ?>" target="_blank" class="text-xs font-bold text-primary-600 hover:text-primary-700 uppercase tracking-wider">View Full Profile ↗</a>
                                </div>
                            </div>
                        </details>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if(count($cards) > 0): ?>
    <div class="flex items-center justify-center gap-8 mt-12 relative z-20 reveal reveal-delay-2" x-show="currentIndex < cards.length">
        <button @click="swipeAction('skipped')" class="w-16 h-16 rounded-2xl bg-white dark:bg-[#0d0d12] shadow-xl shadow-gray-200/50 dark:shadow-none border-2 border-slate-200 dark:border-zinc-800 flex items-center justify-center hover:scale-110 hover:border-red-400 transition-all group group-hover:rotate-12">
            <svg class="w-8 h-8 text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <button @click="swipeAction('interested')" class="w-20 h-20 rounded-[2rem] bg-gradient-to-br from-primary-500 to-pink-500 shadow-[0_15px_35px_rgba(236,72,153,0.4)] flex items-center justify-center hover:scale-110 transition-all group group-hover:-rotate-12">
            <svg class="w-10 h-10 text-white group-hover:scale-110 transition-transform drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </button>
    </div>
    <div class="text-center mt-6 reveal reveal-delay-3" x-show="currentIndex < cards.length">
        <p class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-white/50 dark:bg-zinc-900/50 backdrop-blur border border-slate-200 dark:border-zinc-800 text-xs font-bold text-slate-500">
            <span class="text-primary-600 dark:text-primary-400 mr-1" x-text="cards.length - currentIndex"></span> cards remaining
        </p>
    </div>
    <?php endif; ?>

    
    <div x-show="matchShown" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-xl">
        <div class="relative w-full max-w-lg" x-show="matchShown" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-50 translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            
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

    
    <div x-show="detailsOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-md animate-fade-in"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="detailsOpen = false">
        
        <div class="relative w-full max-w-lg glass-card-strong border-glow rounded-[2.5rem] p-8 shadow-2xl bg-white dark:bg-[#0d0d12]/95 overflow-hidden flex flex-col max-h-[85vh] scale-95 transition-all duration-300 animate-slide-up"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="scale-90"
             x-transition:enter-end="scale-100"
             @click.outside="detailsOpen = false">
            
            <button @click="detailsOpen = false" class="absolute top-6 right-6 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="flex items-center gap-3 mb-4 flex-shrink-0">
                <template x-if="activeChallenge && activeChallenge.logo_url">
                    <img :src="activeChallenge.logo_url" class="w-12 h-12 rounded-xl object-cover border border-slate-200 dark:border-zinc-800">
                </template>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="font-black text-xs text-slate-800 dark:text-zinc-200 truncate" x-text="activeChallenge?.corporate_name"></span>
                        <span class="text-blue-500 text-[10px]">✔</span>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 px-2 py-0.5 rounded" x-text="activeChallenge?.industry_name"></span>
                </div>
            </div>

            <h3 class="text-xl font-black font-outfit text-slate-900 dark:text-white leading-snug mb-4 flex-shrink-0" x-text="activeChallenge?.title"></h3>

            <div class="flex-1 overflow-y-auto hide-scrollbar space-y-4 pr-1 text-xs sm:text-sm text-slate-600 dark:text-zinc-300 font-medium leading-relaxed mb-6">
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[10px] mb-2">Challenge Overview</h4>
                    <p class="whitespace-pre-line" x-text="activeChallenge?.description"></p>
                </div>
                
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[10px] mb-2">Key Requirements</h4>
                    <p class="whitespace-pre-line bg-yellow-50/50 dark:bg-yellow-950/10 border border-yellow-200/50 dark:border-yellow-800/30 rounded-xl p-3.5" x-text="activeChallenge?.requirements"></p>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-900/40 border border-slate-100 dark:border-zinc-800/50 rounded-2xl p-4 text-[11px] font-bold">
                    <div>
                        <span class="text-gray-400 dark:text-zinc-500 block mb-0.5">Budget Pool</span>
                        <span class="text-primary-600 dark:text-primary-400 text-xs sm:text-sm font-extrabold" x-text="'₹' + activeChallenge?.budget_min + ' - ₹' + activeChallenge?.budget_max"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 dark:text-zinc-500 block mb-0.5">Deadline</span>
                        <span class="text-slate-800 dark:text-zinc-200 text-xs sm:text-sm font-black" x-text="activeChallenge?.deadline"></span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 flex-shrink-0 pt-4 border-t border-slate-100 dark:border-zinc-800/80">
                <button @click="detailsOpen = false" class="flex-1 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 font-extrabold text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 transition">
                    Close Details
                </button>
                <button @click="detailsOpen = false; applyOpen = true; cover_letter = ''; approach = ''; submissionErrors = {};" 
                        class="flex-grow py-3.5 rounded-xl bg-primary-600 hover:bg-primary-750 text-white font-extrabold text-xs shadow-md shadow-primary-500/25 transition hover:scale-[1.01]">
                    Apply & Give Solution 🚀
                </button>
            </div>
        </div>
    </div>

    
    <div x-show="applyOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-950/85 backdrop-blur-md animate-fade-in"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="applyOpen = false">
        
        <div class="relative w-full max-w-lg glass-card-strong border-glow rounded-[2.5rem] p-8 shadow-2xl bg-white dark:bg-[#0d0d12]/95 overflow-hidden flex flex-col max-h-[90vh] scale-95 transition-all duration-300 animate-slide-up"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="scale-90"
             x-transition:enter-end="scale-100"
             @click.outside="if(!isSubmitting) applyOpen = false">
            
            <button @click="applyOpen = false" x-show="!isSubmitting" class="absolute top-6 right-6 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="mb-4 flex-shrink-0">
                <h3 class="text-xl font-black font-outfit text-slate-900 dark:text-white mb-1">Give Your Solution</h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 font-semibold" x-text="'For: ' + activeChallenge?.title"></p>
            </div>

            <div class="flex-1 overflow-y-auto hide-scrollbar space-y-4 pr-1 pb-4 flex flex-col">
                
                <div class="text-xs">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block font-bold text-slate-700 dark:text-zinc-300">Cover Letter <span class="text-red-500">*</span></label>
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded animate-pulse-glow"
                              :class="cover_letter.length >= 50 ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400'"
                              x-text="cover_letter.length + '/2000 chars'">
                        </span>
                    </div>
                    <textarea x-model="cover_letter" :disabled="isSubmitting" rows="4" required 
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900/60 focus:border-primary-500 outline-none text-slate-800 dark:text-zinc-200 font-medium"
                              placeholder="Describe why your team is exceptionally qualified to solve this challenge (min 50 characters)..."></textarea>
                    <template x-if="submissionErrors.cover_letter">
                        <p class="text-red-500 text-[10px] mt-1 font-bold animate-bounce-in" x-text="submissionErrors.cover_letter[0]"></p>
                    </template>
                </div>

                
                <div class="text-xs">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block font-bold text-slate-700 dark:text-zinc-300">Technical Approach / Solution <span class="text-gray-400 font-medium">(optional)</span></label>
                        <span class="text-[9px] text-gray-400" x-text="approach.length + '/3000 chars'"></span>
                    </div>
                    <textarea x-model="approach" :disabled="isSubmitting" rows="5" 
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900/60 focus:border-primary-500 outline-none text-slate-800 dark:text-zinc-200 font-medium"
                              placeholder="Detail your proposed approach, target architecture, technology stack, and project milestones..."></textarea>
                    <template x-if="submissionErrors.approach">
                        <p class="text-red-500 text-[10px] mt-1 font-bold animate-bounce-in" x-text="submissionErrors.approach[0]"></p>
                    </template>
                </div>

                
                <div class="text-xs">
                    <label class="block font-bold text-slate-700 dark:text-zinc-300 mb-1.5">Proposal Document <span class="text-gray-400 font-medium">(PDF/DOC, max 5MB, optional)</span></label>
                    <div class="p-4 rounded-xl border border-dashed border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900/40 hover:bg-slate-100 dark:hover:bg-zinc-900/60 transition cursor-pointer relative flex flex-col items-center justify-center text-center">
                        <input type="file" x-ref="proposalFile" :disabled="isSubmitting" accept=".pdf,.doc,.docx" 
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                        <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <span class="text-[10px] font-bold text-slate-500 dark:text-zinc-400">Click to upload proposal brief</span>
                        <span class="text-[8px] text-gray-400 mt-0.5">Supports PDF, DOC, DOCX up to 5MB</span>
                    </div>
                    <template x-if="submissionErrors.proposal_file">
                        <p class="text-red-500 text-[10px] mt-1 font-bold animate-bounce-in" x-text="submissionErrors.proposal_file[0]"></p>
                    </template>
                </div>
            </div>

            <div class="flex gap-3 flex-shrink-0 pt-4 border-t border-slate-100 dark:border-zinc-800/80">
                <button @click="applyOpen = false" :disabled="isSubmitting" class="flex-1 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 font-extrabold text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 transition disabled:opacity-50">
                    Cancel
                </button>
                <button @click="submitSolution()" :disabled="isSubmitting || cover_letter.length < 50" 
                        class="flex-grow py-3.5 rounded-xl bg-primary-600 hover:bg-primary-750 text-white font-extrabold text-xs shadow-md shadow-primary-500/25 transition hover:scale-[1.01] flex items-center justify-center gap-2 disabled:opacity-50 disabled:scale-100 disabled:hover:bg-primary-600">
                    <template x-if="isSubmitting">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </template>
                    <span x-text="isSubmitting ? 'Submitting Proposal...' : 'Submit & Swipe Right 🚀'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function swipeApp() {
    return {
        currentIndex: 0,
        isSwipingLeft: -1,
        isSwipingRight: -1,
        matchShown: false,
        matchName: '',
        matchConnectionId: null,
        isStartup: <?php echo e(auth()->user()->isStartup() ? 'true' : 'false'); ?>,
        
        // Modals & Application state
        detailsOpen: false,
        applyOpen: false,
        activeChallenge: null,
        cover_letter: '',
        approach: '',
        isSubmitting: false,
        submissionErrors: {},

        cards: <?php echo json_encode($cards->map(fn($c) => [
            'id' => auth()->user()->isStartup() ? $c['challenge']->id : $c['user']->id,
            'is_challenge' => auth()->user()->isStartup() ? true : false,
            'challenge' => auth()->user()->isStartup() ? [
                'id' => $c['challenge']->id,
                'title' => $c['challenge']->title,
                'description' => $c['challenge']->description,
                'requirements' => $c['challenge']->requirements ?? 'None listed by partner.',
                'budget_min' => number_format($c['challenge']->budget_min),
                'budget_max' => number_format($c['challenge']->budget_max),
                'deadline' => $c['challenge']->deadline->format('M d, Y'),
                'attachment_path' => $c['challenge']->attachment_path ? asset('storage/' . $c['challenge']->attachment_path) : null,
                'attachment_filename' => $c['challenge']->attachment_filename,
                'corporate_name' => $c['user']->companyName(),
                'logo_url' => $c['user']->logoUrl(),
                'industry_name' => $c['profile']?->industry?->name ?? 'Corporate Partner',
                'match_score' => $c['score']['score'] ?? 0
            ] : null
        ])); ?>,
        
        openChallengeDetails(index) {
            this.activeChallenge = this.cards[index].challenge;
            this.detailsOpen = true;
        },

        async submitSolution() {
            if (!this.activeChallenge) return;
            const challengeId = this.activeChallenge.id;
            
            const formData = new FormData();
            formData.append('cover_letter', this.cover_letter);
            formData.append('approach', this.approach);
            if (this.$refs.proposalFile && this.$refs.proposalFile.files[0]) {
                formData.append('proposal_file', this.$refs.proposalFile.files[0]);
            }

            try {
                this.isSubmitting = true;
                this.submissionErrors = {};

                const res = await fetch(`/startup/challenges/${challengeId}/apply`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (res.status === 422) {
                    const data = await res.json();
                    this.submissionErrors = data.errors;
                    this.isSubmitting = false;
                    return;
                }

                if (res.ok) {
                    this.applyOpen = false;
                    this.isSubmitting = false;
                    this.cover_letter = '';
                    this.approach = '';
                    if (this.$refs.proposalFile) this.$refs.proposalFile.value = '';
                    
                    // Trigger the swipe right animation and register swipe signal in DB
                    await this.executeSwipe(this.currentIndex, 'interested');
                } else {
                    alert("Submission failed. Please check your fields and try again.");
                    this.isSubmitting = false;
                }
            } catch (e) {
                console.error("Proposal submission failed", e);
                this.isSubmitting = false;
            }
        },

        async swipeAction(action) {
            if (this.currentIndex >= this.cards.length) return;
            
            const cardIndex = this.currentIndex;
            const card = this.cards[cardIndex];
            
            if (action === 'interested' && card.is_challenge) {
                // Intercept right swipe for challenges to request solution proposal
                this.activeChallenge = card.challenge;
                this.cover_letter = '';
                this.approach = '';
                this.submissionErrors = {};
                this.applyOpen = true;
                return;
            }
            
            await this.executeSwipe(cardIndex, action);
        },

        async executeSwipe(cardIndex, action) {
            const card = this.cards[cardIndex];
            
            // Trigger animation class
            if (action === 'skipped') this.isSwipingLeft = cardIndex;
            else this.isSwipingRight = cardIndex;
            
            // Wait for animation slightly before moving index
            setTimeout(() => {
                this.currentIndex++;
            }, 300);

            try {
                const res = await fetch('<?php echo e(route("swipe.store")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        target_id: card.id, 
                        action: action, 
                        is_challenge: card.is_challenge 
                    })
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\swipe.blade.php ENDPATH**/ ?>