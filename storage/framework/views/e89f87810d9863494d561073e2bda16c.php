<?php $__env->startSection('title', $user->companyName() . ' — Profile'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.cover-gradient { background: linear-gradient(135deg, #4f46e5 0%, #a855f7 50%, #ec4899 100%); }
.stat-card { @apply bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white text-center shadow-lg; }
.info-row { @apply flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800/50 last:border-0; }
.tag-chip { @apply text-xs font-bold px-3 py-1.5 rounded-xl border transition-colors; }

/* Timeline animations */
.timeline-dot { transition: all 0.3s ease; }
.timeline-item:hover .timeline-dot { transform: scale(1.3); background-color: #a855f7; box-shadow: 0 0 15px rgba(168,85,247,0.6); }

/* Compatibility Ring Animation */
@keyframes drawCircle {
    from { stroke-dashoffset: 125.6; }
    to { stroke-dashoffset: var(--target-offset); }
}
.animated-ring {
    stroke-dasharray: 125.6;
    stroke-dashoffset: 125.6;
    animation: drawCircle 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards 0.5s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php $profile = $user->profile(); ?>

<div class="max-w-6xl mx-auto px-4 py-10 space-y-8 relative z-10">
    <?php echo $__env->make('components.back-button', ['fallback' => route('search'), 'label' => 'Back to Search'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="reveal glass-card-strong rounded-[2.5rem] overflow-hidden shadow-2xl border-glow">
        
        
        <div class="cover-gradient h-64 relative overflow-hidden">
            <div class="absolute inset-0 noise opacity-20"></div>
            <div class="absolute inset-0 bg-white/10 mesh-gradient opacity-40"></div>
            
            
            <?php if($compatibility): ?>
            <div class="absolute top-6 right-8 bg-white/20 backdrop-blur-xl rounded-[2rem] p-4 border border-white/30 text-white text-center shadow-2xl flex items-center gap-4">
                <div class="relative w-16 h-16">
                    <svg class="w-full h-full" viewBox="0 0 44 44">
                        <circle cx="22" cy="22" r="20" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="4"></circle>
                        <circle cx="22" cy="22" r="20" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" 
                                style="--target-offset: <?php echo e(125.6 - (125.6 * $compatibility['score']) / 100); ?>" class="animated-ring"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-black drop-shadow-md"><?php echo e($compatibility['score']); ?></span>
                    </div>
                </div>
                <div class="text-left pr-2">
                    <div class="text-[10px] font-black uppercase tracking-widest opacity-90 mb-0.5">Match Score</div>
                    <div class="text-sm font-bold text-white drop-shadow-sm"><?php echo e($compatibility['label']); ?></div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($user->isStartup() && $profile): ?>
            <div class="absolute bottom-6 left-8 flex gap-3 flex-wrap">
                <div class="stat-card hover:bg-white/30 transition-colors">
                    <div class="font-black text-lg"><?php echo e($profile->stageLabel()); ?></div>
                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Stage</div>
                </div>
                <?php if($profile->team_size): ?>
                <div class="stat-card hover:bg-white/30 transition-colors">
                    <div class="font-black text-lg"><?php echo e($profile->team_size); ?></div>
                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Team</div>
                </div>
                <?php endif; ?>
                <?php if($profile->founded_year): ?>
                <div class="stat-card hover:bg-white/30 transition-colors">
                    <div class="font-black text-lg"><?php echo e($profile->founded_year); ?></div>
                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Founded</div>
                </div>
                <?php endif; ?>
                <?php if($profile->funding_amount > 0): ?>
                <div class="stat-card hover:bg-white/30 transition-colors">
                    <div class="font-black text-lg">₹<?php echo e(number_format($profile->funding_amount / 100000, 1)); ?>L</div>
                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Raised</div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl px-8 sm:px-12 pb-10 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 -mt-20 mb-8">
                <div class="flex items-end gap-6">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-pink-500 rounded-3xl blur-md opacity-50 group-hover:opacity-100 transition-opacity"></div>
                        <img src="<?php echo e($user->logoUrl()); ?>" alt="<?php echo e($user->companyName()); ?>"
                            class="w-32 h-32 rounded-3xl object-cover ring-8 ring-white dark:ring-gray-900 shadow-2xl relative z-10 bg-white">
                    </div>
                    <div class="pb-2">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h1 class="text-4xl font-black font-outfit text-gray-900 dark:text-white"><?php echo e($user->companyName()); ?></h1>
                            <?php $__currentLoopData = $user->badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span title="<?php echo e($b->description); ?>" class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg border-2 border-white dark:border-gray-900" >
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <?php if($profile?->industry): ?><span class="text-primary-600 dark:text-primary-400"><?php echo e($profile->industry->name); ?></span><?php endif; ?>
                            <?php if($profile?->city): ?><span>📍 <?php echo e($profile->city); ?><?php echo e($profile->state ? ', '.$profile->state : ''); ?></span><?php endif; ?>
                            <?php if($profile?->website): ?><a href="<?php echo e($profile->website); ?>" target="_blank" class="hover:text-primary-600 transition flex items-center gap-1">🌐 Website</a><?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="flex flex-wrap gap-4 pb-2">
                    <?php if($connection && $connection->status === 'active'): ?>
                        <a href="<?php echo e(route('chat.show', $connection)); ?>"
                            class="shimmer-btn inline-flex items-center gap-3 bg-gradient-to-r from-primary-600 to-purple-600 text-white font-black px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl shadow-primary-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Message
                        </a>
                    <?php elseif($user->isStartup() || $user->isCorporate()): ?>
                        <a href="<?php echo e(auth()->user()->isStartup() ? route('startup.swipe') : route('corporate.swipe')); ?>"
                            class="shimmer-btn inline-flex items-center gap-3 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-black px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl shadow-pink-500/30">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            Match
                        </a>
                    <?php endif; ?>

                    <?php if($user->isStartup()): ?>
                    <a href="<?php echo e(route('profile.pdf', $user)); ?>"
                        class="glass-card inline-flex items-center gap-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-bold px-8 py-4 rounded-2xl transition shadow-lg border border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid lg:grid-cols-3 gap-8 stagger-container">

        
        <div class="lg:col-span-2 space-y-8">

            
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <h2 class="text-2xl font-black font-outfit mb-6 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-purple-500 flex items-center justify-center text-white text-xl shadow-lg">✦</span>
                    About Us
                </h2>
                <div class="prose prose-lg dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                    <?php if($user->isStartup()): ?>
                        <?php echo e($profile?->elevator_pitch ?? 'No description yet.'); ?>

                    <?php else: ?>
                        <?php echo e($profile?->about ?: ($profile?->problem_statement ?? 'No description yet.')); ?>

                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($user->isCorporate() && $profile?->problem_statement): ?>
            <div class="reveal glass-card-strong rounded-3xl p-8 border border-purple-200 dark:border-purple-800/50 bg-purple-50/50 dark:bg-purple-900/10">
                <h2 class="text-2xl font-black font-outfit mb-6 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl shadow-lg">🎯</span>
                    What We're Looking For
                </h2>
                <p class="text-gray-700 dark:text-gray-300 font-medium leading-relaxed text-lg"><?php echo e($profile->problem_statement); ?></p>
            </div>
            <?php endif; ?>

            
            <?php
                $tags = $user->isStartup() ? ($profile?->tech_tags ?? []) : ($profile?->seeking_technologies ?? []);
                $tagLabel = $user->isStartup() ? 'Tech Stack' : 'Seeking Technologies';
                $tagColor = $user->isStartup() ? 'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-300 dark:border-primary-800/50 hover:bg-primary-500 hover:text-white' : 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800/50 hover:bg-purple-500 hover:text-white';
                $icon = $user->isStartup() ? '⚡' : '🔍';
                $iconBg = $user->isStartup() ? 'from-blue-500 to-cyan-500' : 'from-indigo-500 to-purple-500';
            ?>
            <?php if(count($tags)): ?>
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <h2 class="text-2xl font-black font-outfit mb-6 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br <?php echo e($iconBg); ?> flex items-center justify-center text-white text-xl shadow-lg"><?php echo e($icon); ?></span>
                    <?php echo e($tagLabel); ?>

                </h2>
                <div class="flex flex-wrap gap-3">
                    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="tag-chip <?php echo e($tagColor); ?> cursor-default">#<?php echo e($tag); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($user->isStartup() && $profile?->milestones?->count()): ?>
            <div class="reveal glass-card-strong rounded-3xl p-8 overflow-hidden relative">
                
                <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500/10 rounded-full blur-[80px]"></div>
                
                <h2 class="text-2xl font-black font-outfit mb-10 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center text-white text-xl shadow-lg">🚀</span>
                    Startup Journey
                </h2>
                
                <div class="relative pl-10 border-l-4 border-gray-100 dark:border-gray-800 space-y-10">
                    <?php $__currentLoopData = $profile->milestones->sortBy('milestone_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative timeline-item group card-lift bg-white/50 dark:bg-gray-800/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                        
                        <div class="absolute -left-[54px] top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 ring-8 ring-white dark:ring-gray-900 timeline-dot"></div>
                        
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h3 class="font-black text-lg text-gray-900 dark:text-white group-hover:text-pink-500 transition-colors"><?php echo e($ms->title); ?></h3>
                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap"><?php echo e($ms->milestone_date->format('M Y')); ?></span>
                        </div>
                        <?php if($ms->description): ?>
                            <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed"><?php echo e($ms->description); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($compatibility && isset($compatibility['breakdown'])): ?>
            <div class="reveal glass-card-strong rounded-3xl p-8 border-glow">
                <h2 class="text-2xl font-black font-outfit mb-8 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl shadow-lg">🤖</span>
                    AI Compatibility Breakdown
                </h2>
                
                <div class="grid sm:grid-cols-2 gap-8 mb-8">
                    <?php $__currentLoopData = $compatibility['breakdown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 card-lift">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-gray-900 dark:text-white"><?php echo e($item['label']); ?></h4>
                            <span class="text-xl font-black <?php echo e($item['score'] >= ($item['max']*0.8) ? 'text-green-500' : 'text-primary-500'); ?>"><?php echo e(round($item['score'])); ?><span class="text-sm text-gray-400">/<?php echo e($item['max']); ?></span></span>
                        </div>
                        
                        
                        <div class="relative w-20 h-20 mx-auto">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="40" cy="40" r="36" fill="none" class="stroke-gray-200 dark:stroke-gray-700" stroke-width="8"></circle>
                                <circle cx="40" cy="40" r="36" fill="none" class="<?php echo e($item['score'] >= ($item['max']*0.8) ? 'stroke-green-500' : 'stroke-primary-500'); ?> transition-all duration-1000 ease-out" 
                                        stroke-width="8" stroke-linecap="round" stroke-dasharray="226.2" 
                                        style="stroke-dashoffset: <?php echo e(226.2 - (226.2 * ($item['max'] > 0 ? $item['score']/$item['max'] : 0))); ?>"></circle>
                            </svg>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="bg-gradient-to-r from-primary-600 via-purple-600 to-pink-600 rounded-2xl p-6 text-center text-white shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9ImIiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI2IpIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PC9zdmc+')] opacity-20"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <span class="text-5xl font-black drop-shadow-md"><?php echo e($compatibility['score']); ?><span class="text-2xl text-white/70">/100</span></span>
                        <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                        <span class="text-xl font-bold uppercase tracking-widest drop-shadow-md"><?php echo e($compatibility['label']); ?> Match</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="space-y-8">

            
            <div class="reveal reveal-delay-1 glass-card-strong rounded-3xl p-8">
                <h3 class="font-black font-outfit mb-6 text-lg text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Fast Facts
                </h3>
                <div class="space-y-1">
                    <?php if($user->isStartup() && $profile): ?>
                        <div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Stage</span><span class="text-sm font-black"><?php echo e($profile->stageLabel()); ?></span></div>
                        <?php if($profile->team_size): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Team</span><span class="text-sm font-black"><?php echo e($profile->team_size); ?> <span class="text-gray-400">ppl</span></span></div><?php endif; ?>
                        <?php if($profile->founded_year): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Founded</span><span class="text-sm font-black"><?php echo e($profile->founded_year); ?></span></div><?php endif; ?>
                        <?php if($profile->funding_status): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Funding</span><span class="text-sm font-black capitalize"><?php echo e(str_replace('_',' ',$profile->funding_status)); ?></span></div><?php endif; ?>
                        <?php if($profile->funding_amount > 0): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Raised</span><span class="text-sm font-black text-green-500">₹<?php echo e(number_format($profile->funding_amount)); ?></span></div><?php endif; ?>
                    <?php elseif($user->isCorporate() && $profile): ?>
                        <?php if($profile->company_size): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Size</span><span class="text-sm font-black capitalize"><?php echo e(str_replace('_',' ',$profile->company_size)); ?></span></div><?php endif; ?>
                        <?php if($profile->budget_min || $profile->budget_max): ?><div class="info-row flex-col items-start gap-1"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Budget</span><span class="text-lg font-black text-green-500">₹<?php echo e(number_format($profile->budget_min)); ?> <span class="text-sm text-gray-400 font-bold px-1">to</span> ₹<?php echo e(number_format($profile->budget_max)); ?></span></div><?php endif; ?>
                        <?php if($profile->established_year): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Est.</span><span class="text-sm font-black"><?php echo e($profile->established_year); ?></span></div><?php endif; ?>
                    <?php endif; ?>
                    <?php if($profile?->city): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Location</span><span class="text-sm font-black"><?php echo e($profile->city); ?><?php echo e($profile->state ? ', '.$profile->state : ''); ?></span></div><?php endif; ?>
                    <?php if($profile?->website): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Website</span><a href="<?php echo e($profile->website); ?>" target="_blank" class="text-sm text-primary-600 hover:text-primary-700 font-black flex items-center gap-1">Visit Site <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a></div><?php endif; ?>
                </div>
            </div>

            
            <?php if($user->badges->count()): ?>
            <div class="reveal reveal-delay-2 glass-card-strong rounded-3xl p-8">
                <h3 class="font-black font-outfit mb-6 text-lg text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Badges Earned
                </h3>
                <div class="grid grid-cols-3 gap-4">
                    <?php $__currentLoopData = $user->badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center group cursor-default" title="<?php echo e($badge->description); ?>">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg mb-2 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <svg class="w-7 h-7 text-white drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-[10px] text-gray-900 dark:text-white font-bold leading-tight line-clamp-2 uppercase tracking-wider"><?php echo e($badge->name); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/profile-show.blade.php ENDPATH**/ ?>