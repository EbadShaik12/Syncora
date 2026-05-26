<?php $__env->startSection('title', 'Leaderboard — Syncora'); ?>

<?php $__env->startPush('styles'); ?>
<style>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 relative z-10" x-data="{ tab: '<?php echo e($viewer->isStartup() ? 'corporates' : 'startups'); ?>' }">
    <?php echo $__env->make('components.back-button', ['fallback' => route('dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
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

    
    <div x-show="tab==='startups'" x-cloak>
        <?php $top3 = $startups->take(3); ?>
        
        
        <?php if($top3->count() >= 1): ?>
        <div class="podium-container reveal reveal-delay-2">
            
            <?php if($top3->count() >= 2): ?>
            <?php $s = $top3[1]; ?>
            <div class="podium-step podium-2" style="animation-delay: 0.2s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="<?php echo e($s->logoUrl()); ?>" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-gray-200 dark:ring-gray-600 shadow-[0_20px_40px_rgba(0,0,0,0.3)] bg-white group-hover:scale-110 transition-transform">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-silver rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">2</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md"><?php echo e($s->companyName()); ?></p>
                </div>
                <div class="mt-auto pb-6 text-center">
                    <p class="text-white/90 text-xs font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-2xl drop-shadow-md"><?php echo e($s->lb_score); ?></p>
                </div>
            </div>
            <?php endif; ?>

            
            <?php $s = $top3[0]; ?>
            <div class="podium-step podium-1 z-10" style="animation-delay: 0.4s">
                <div class="avatar-container text-center">
                    <div class="text-4xl mb-3 animate-bounce drop-shadow-xl">👑</div>
                    <div class="relative inline-block group">
                        <img src="<?php echo e($s->logoUrl()); ?>" class="w-28 h-28 rounded-[2rem] object-cover ring-8 ring-yellow-400 shadow-[0_30px_60px_rgba(253,185,49,0.5)] bg-white group-hover:scale-110 transition-transform">
                        <span class="absolute -top-4 -right-4 w-10 h-10 rank-gold rounded-full flex items-center justify-center text-yellow-900 font-black text-lg shadow-xl border-2 border-white">1</span>
                    </div>
                    <p class="font-black text-lg mt-4 text-gray-900 dark:text-white truncate w-40 drop-shadow-md"><?php echo e($s->companyName()); ?></p>
                </div>
                <div class="mt-auto pb-8 text-center">
                    <p class="text-yellow-900/80 text-xs font-black uppercase tracking-widest mb-1">Score</p>
                    <p class="text-yellow-900 font-black text-4xl drop-shadow-md"><?php echo e($s->lb_score); ?></p>
                </div>
            </div>

            
            <?php if($top3->count() >= 3): ?>
            <?php $s = $top3[2]; ?>
            <div class="podium-step podium-3" style="animation-delay: 0.6s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="<?php echo e($s->logoUrl()); ?>" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-orange-400 shadow-[0_20px_40px_rgba(237,143,3,0.3)] bg-white group-hover:scale-110 transition-transform">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-bronze rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">3</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md"><?php echo e($s->companyName()); ?></p>
                </div>
                <div class="mt-auto pb-4 text-center">
                    <p class="text-white/90 text-[10px] font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-xl drop-shadow-md"><?php echo e($s->lb_score); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <?php $maxScore = $startups->first()?->lb_score ?: 1; ?>
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

            <?php $__empty_1 = true; $__currentLoopData = $startups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $isMe = $s->id === $viewer->id; ?>
            <a href="<?php echo e(route('profile.show', $s)); ?>"
                class="leaderboard-row grid grid-cols-12 gap-4 items-center px-8 py-5 border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-all duration-300 group <?php echo e($isMe ? 'bg-primary-50/50 dark:bg-primary-900/20 border-l-4 border-l-primary-500' : ''); ?>">

                
                <div class="col-span-1 flex justify-center">
                    <?php if($rank === 0): ?>
                        <span class="w-10 h-10 rank-gold rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">1</span>
                    <?php elseif($rank === 1): ?>
                        <span class="w-10 h-10 rank-silver rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">2</span>
                    <?php elseif($rank === 2): ?>
                        <span class="w-10 h-10 rank-bronze rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">3</span>
                    <?php else: ?>
                        <span class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg group-hover:bg-primary-100 dark:group-hover:bg-primary-900 group-hover:text-primary-600 transition"><?php echo e($rank + 1); ?></span>
                    <?php endif; ?>
                </div>

                
                <div class="col-span-4 flex items-center gap-4 min-w-0">
                    <img src="<?php echo e($s->logoUrl()); ?>" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 shadow-md group-hover:scale-110 transition bg-white">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-black text-base text-gray-900 dark:text-white truncate group-hover:text-primary-600 transition"><?php echo e($s->companyName()); ?></p>
                            <?php if($isMe): ?><span class="you-badge">You</span><?php endif; ?>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-0.5"><?php echo e($s->startupProfile?->industry?->name ?? 'Startup'); ?></p>
                    </div>
                </div>

                
                <div class="col-span-3">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner relative">
                            <div class="h-full rounded-full bg-gradient-to-r from-primary-400 to-purple-500 score-bar relative overflow-hidden"
                                style="width: 0%"
                                x-intersect="$el.style.width = '<?php echo e($maxScore > 0 ? round(($s->lb_score / $maxScore) * 100) : 0); ?>%'">
                                <div class="absolute inset-0 bg-white/30 w-1/2 -skew-x-12 translate-x-full animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        <span class="text-lg font-black text-primary-700 dark:text-primary-300 w-10 text-right"><?php echo e($s->lb_score); ?></span>
                    </div>
                </div>

                
                <div class="col-span-4 grid grid-cols-4 gap-2 text-center">
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg"><?php echo e($s->lb_connections); ?></span>
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg"><?php echo e($s->lb_applications); ?></span>
                    <span class="text-sm font-black text-green-600 bg-green-50 dark:bg-green-900/20 py-1 rounded-lg"><?php echo e($s->lb_shortlisted); ?></span>
                    <span class="text-sm font-black text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 py-1 rounded-lg"><?php echo e($s->lb_badges); ?></span>
                </div>

                
                <?php if(!empty($s->lb_ai_insight)): ?>
                <div class="col-span-12 px-2 pb-1 flex items-center gap-2">
                    <?php
                        $potentialColor = match($s->lb_ai_potential ?? 'Medium') {
                            'High'   => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                            'Low'    => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                            default  => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                        };
                    ?>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border <?php echo e($potentialColor); ?>">
                        ✨ AI · <?php echo e($s->lb_ai_potential ?? 'Medium'); ?>

                        <?php if(($s->lb_ai_bonus ?? 0) > 0): ?><span class="ml-1 opacity-70">+<?php echo e($s->lb_ai_bonus); ?></span><?php endif; ?>
                    </span>
                    <span class="text-[11px] text-gray-400 dark:text-gray-500 italic truncate"><?php echo e($s->lb_ai_insight); ?></span>
                </div>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-20 text-center text-gray-500 font-bold text-lg">No startups on the leaderboard yet.</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div x-show="tab==='corporates'" x-cloak>
        <?php $top3C = $corporates->take(3); ?>
        
        
        <?php if($top3C->count() >= 1): ?>
        <div class="podium-container reveal reveal-delay-2">
            <?php if($top3C->count() >= 2): ?>
            <?php $c = $top3C[1]; ?>
            <div class="podium-step podium-2" style="animation-delay: 0.2s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="<?php echo e($c->logoUrl()); ?>" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-gray-200 dark:ring-gray-600 shadow-xl bg-white group-hover:scale-110 transition">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-silver rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">2</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md"><?php echo e($c->companyName()); ?></p>
                </div>
                <div class="mt-auto pb-6 text-center">
                    <p class="text-white/90 text-xs font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-2xl drop-shadow-md"><?php echo e($c->lb_score); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php $c = $top3C[0]; ?>
            <div class="podium-step podium-1 z-10" style="animation-delay: 0.4s">
                <div class="avatar-container text-center">
                    <div class="text-4xl mb-3 animate-bounce drop-shadow-xl">👑</div>
                    <div class="relative inline-block group">
                        <img src="<?php echo e($c->logoUrl()); ?>" class="w-28 h-28 rounded-[2rem] object-cover ring-8 ring-yellow-400 shadow-2xl bg-white group-hover:scale-110 transition">
                        <span class="absolute -top-4 -right-4 w-10 h-10 rank-gold rounded-full flex items-center justify-center text-yellow-900 font-black text-lg shadow-xl border-2 border-white">1</span>
                    </div>
                    <p class="font-black text-lg mt-4 text-gray-900 dark:text-white truncate w-40 drop-shadow-md"><?php echo e($c->companyName()); ?></p>
                </div>
                <div class="mt-auto pb-8 text-center">
                    <p class="text-yellow-900/80 text-xs font-black uppercase tracking-widest mb-1">Score</p>
                    <p class="text-yellow-900 font-black text-4xl drop-shadow-md"><?php echo e($c->lb_score); ?></p>
                </div>
            </div>

            <?php if($top3C->count() >= 3): ?>
            <?php $c = $top3C[2]; ?>
            <div class="podium-step podium-3" style="animation-delay: 0.6s">
                <div class="avatar-container text-center">
                    <div class="relative inline-block group">
                        <img src="<?php echo e($c->logoUrl()); ?>" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-orange-400 shadow-xl bg-white group-hover:scale-110 transition">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rank-bronze rounded-full flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white">3</span>
                    </div>
                    <p class="font-black text-sm mt-3 text-gray-900 dark:text-white truncate w-32 drop-shadow-md"><?php echo e($c->companyName()); ?></p>
                </div>
                <div class="mt-auto pb-4 text-center">
                    <p class="text-white/90 text-[10px] font-bold uppercase tracking-widest mb-1">Score</p>
                    <p class="text-white font-black text-xl drop-shadow-md"><?php echo e($c->lb_score); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <?php $maxScoreC = $corporates->first()?->lb_score ?: 1; ?>
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

            <?php $__empty_1 = true; $__currentLoopData = $corporates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $isMe = $c->id === $viewer->id; ?>
            <a href="<?php echo e(route('profile.show', $c)); ?>"
                class="leaderboard-row grid grid-cols-12 gap-4 items-center px-8 py-5 border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-all duration-300 group <?php echo e($isMe ? 'bg-purple-50/50 dark:bg-purple-900/20 border-l-4 border-l-purple-500' : ''); ?>">

                <div class="col-span-1 flex justify-center">
                    <?php if($rank === 0): ?>
                        <span class="w-10 h-10 rank-gold rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">1</span>
                    <?php elseif($rank === 1): ?>
                        <span class="w-10 h-10 rank-silver rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">2</span>
                    <?php elseif($rank === 2): ?>
                        <span class="w-10 h-10 rank-bronze rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg">3</span>
                    <?php else: ?>
                        <span class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg group-hover:bg-purple-100 dark:group-hover:bg-purple-900 group-hover:text-purple-600 transition"><?php echo e($rank + 1); ?></span>
                    <?php endif; ?>
                </div>

                <div class="col-span-4 flex items-center gap-4 min-w-0">
                    <img src="<?php echo e($c->logoUrl()); ?>" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 shadow-md group-hover:scale-110 transition bg-white">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-black text-base text-gray-900 dark:text-white truncate group-hover:text-purple-600 transition"><?php echo e($c->companyName()); ?></p>
                            <?php if($isMe): ?><span class="you-badge">You</span><?php endif; ?>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-0.5"><?php echo e($c->corporateProfile?->industry?->name ?? 'Corporate'); ?></p>
                    </div>
                </div>

                <div class="col-span-3">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner relative">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-400 to-cyan-500 score-bar relative overflow-hidden"
                                style="width: 0%"
                                x-intersect="$el.style.width = '<?php echo e($maxScoreC > 0 ? round(($c->lb_score / $maxScoreC) * 100) : 0); ?>%'">
                                <div class="absolute inset-0 bg-white/30 w-1/2 -skew-x-12 translate-x-full animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        <span class="text-lg font-black text-blue-700 dark:text-blue-300 w-10 text-right"><?php echo e($c->lb_score); ?></span>
                    </div>
                </div>

                <div class="col-span-4 grid grid-cols-4 gap-2 text-center">
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg"><?php echo e($c->lb_challenges); ?></span>
                    <span class="text-sm font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 py-1 rounded-lg"><?php echo e($c->lb_apps); ?></span>
                    <span class="text-sm font-black text-green-600 bg-green-50 dark:bg-green-900/20 py-1 rounded-lg"><?php echo e($c->lb_shortlisted); ?></span>
                    <span class="text-sm font-black text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 py-1 rounded-lg"><?php echo e($c->lb_badges); ?></span>
                </div>

                
                <?php if(!empty($c->lb_ai_insight)): ?>
                <div class="col-span-12 px-2 pb-1 flex items-center gap-2">
                    <?php
                        $potentialColorC = match($c->lb_ai_potential ?? 'Medium') {
                            'High'  => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                            'Low'   => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                            default => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                        };
                    ?>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border <?php echo e($potentialColorC); ?>">
                        ✨ AI · <?php echo e($c->lb_ai_potential ?? 'Medium'); ?>

                        <?php if(($c->lb_ai_bonus ?? 0) > 0): ?><span class="ml-1 opacity-70">+<?php echo e($c->lb_ai_bonus); ?></span><?php endif; ?>
                    </span>
                    <span class="text-[11px] text-gray-400 dark:text-gray-500 italic truncate"><?php echo e($c->lb_ai_insight); ?></span>
                </div>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-20 text-center text-gray-500 font-bold text-lg">No corporates on the leaderboard yet.</div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php
        $myRank = $viewer->isStartup()
            ? $startups->search(fn($s) => $s->id === $viewer->id)
            : $corporates->search(fn($c) => $c->id === $viewer->id);
        $myEntry = $viewer->isStartup() ? $startups->get($myRank) : $corporates->get($myRank);
    ?>
    <?php if($myRank !== false && $myEntry): ?>
    <div class="mt-8 bg-gradient-to-r from-primary-600 via-purple-600 to-pink-600 rounded-[2rem] p-8 text-white flex items-center justify-between shadow-[0_30px_60px_rgba(168,85,247,0.3)] reveal reveal-delay-4 relative overflow-hidden border-glow">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9ImIiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI2IpIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PC9zdmc+')] opacity-30"></div>
        <div class="absolute inset-0 noise opacity-20"></div>
        
        <div class="flex items-center gap-6 relative z-10">
            <img src="<?php echo e($viewer->logoUrl()); ?>" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white/40 shadow-xl bg-white">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-white/80 mb-1">Your Rank</p>
                <p class="text-5xl font-black font-outfit drop-shadow-md">#<?php echo e($myRank + 1); ?></p>
            </div>
        </div>
        <div class="text-right relative z-10">
            <p class="text-sm font-bold uppercase tracking-widest text-white/80 mb-1">Current Score</p>
            <p class="text-5xl font-black font-outfit drop-shadow-md"><?php echo e($myEntry->lb_score); ?></p>
        </div>
        <div class="text-right hidden sm:block relative z-10">
            <p class="text-sm font-bold uppercase tracking-widest text-white/80 mb-1">To Next Rank</p>
            <?php $nextEntry = $viewer->isStartup() ? $startups->get($myRank - 1) : $corporates->get($myRank - 1); ?>
            <p class="text-3xl font-black font-outfit text-yellow-300 drop-shadow-md mt-2">
                <?php echo e($myRank > 0 && $nextEntry ? '+'.($nextEntry->lb_score - $myEntry->lb_score) : '🏆 Number 1!'); ?>

            </p>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\leaderboard.blade.php ENDPATH**/ ?>