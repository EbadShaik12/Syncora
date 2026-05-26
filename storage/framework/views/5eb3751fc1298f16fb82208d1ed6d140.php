<?php if($results->isEmpty()): ?>
    <div class="reveal reveal-delay-3 glass-card-strong rounded-3xl p-16 text-center border-dashed border-2 border-gray-300 dark:border-gray-700">
        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-primary-500/20 to-purple-500/20 flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <h3 class="text-2xl font-black mb-3 text-gray-900 dark:text-white font-outfit">No matches found</h3>
        <p class="text-gray-500 dark:text-gray-400 text-base mb-8 max-w-md mx-auto">We couldn't find any <?php echo e($targetRole); ?>s matching your exact criteria. Try broadening your search filters.</p>
        <button type="button" @click="resetFilters()" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-purple-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-xl shadow-primary-500/20 hover:scale-105 transition">
            Clear all filters
        </button>
    </div>
<?php else: ?>
    
    <div id="results-real" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6 stagger-container">
        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php 
            $profile = $r->profile(); 
            $score = $r->compatibility ?? null; 
            // Pick a random gradient for the banner based on ID to keep it consistent
            $gradients = [
                'from-primary-500 to-purple-600',
                'from-blue-500 to-cyan-500',
                'from-purple-500 to-pink-500',
                'from-orange-500 to-red-500',
                'from-green-400 to-emerald-600'
            ];
            $bgGradient = $gradients[$r->id % count($gradients)];
        ?>
        <a href="<?php echo e(route('profile.show', $r)); ?>"
            class="reveal result-card glass-card-strong rounded-[2rem] border border-gray-100 dark:border-gray-800 overflow-hidden relative visible"
            style="transition-delay: <?php echo e(($i % 6) * 100); ?>ms">

            
            <div class="h-24 bg-gradient-to-r <?php echo e($bgGradient); ?> relative">
                <div class="absolute inset-0 bg-white/10 mesh-gradient opacity-30"></div>
                <?php if($score): ?>
                <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md border border-white/30 rounded-xl px-3 py-1.5 flex items-center gap-1.5 shadow-lg">
                    <svg class="w-3.5 h-3.5 text-yellow-300 drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span class="text-white font-black text-sm drop-shadow-md"><?php echo e($score['score']); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="px-6 pb-6 -mt-8 relative z-10 flex flex-col h-[calc(100%-6rem)]">
                <div class="flex items-end gap-4 mb-4">
                    <img src="<?php echo e($r->logoUrl()); ?>" alt="<?php echo e($r->companyName()); ?>"
                        class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white dark:ring-gray-900 shadow-xl bg-white">
                    <div class="flex-1 min-w-0 pt-8">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate group-hover:text-primary-600 transition font-outfit"><?php echo e($r->companyName()); ?></h3>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider"><?php echo e($profile?->industry?->name ?? ucfirst($r->role)); ?></p>
                    </div>
                </div>

                
                <div class="flex flex-wrap gap-2 mb-4">
                    <?php if($r->isStartup() && $profile?->stage): ?>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg <?php echo e($profile->stageColor()); ?>"><?php echo e($profile->stageLabel()); ?></span>
                    <?php endif; ?>
                    <?php if($profile?->city): ?>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">📍 <?php echo e($profile->city); ?></span>
                    <?php endif; ?>
                </div>

                
                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed mb-4 font-medium flex-grow">
                    <?php echo e($profile?->elevator_pitch ?? $profile?->problem_statement ?? 'No description available.'); ?>

                </p>

                
                <?php $tags = $r->isStartup() ? ($profile?->tech_tags ?? []) : ($profile?->seeking_technologies ?? []); ?>
                <?php if(count($tags) > 0): ?>
                <div class="flex flex-wrap gap-1.5 mb-5 mt-auto">
                    <?php $__currentLoopData = array_slice($tags, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 border border-primary-100 dark:border-primary-800/50">#<?php echo e($tag); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(count($tags) > 3): ?><span class="text-[10px] font-bold px-2 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500">+<?php echo e(count($tags) - 3); ?></span><?php endif; ?>
                </div>
                <?php endif; ?>

                
                <?php if($score): ?>
                <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-800/50">
                    <div class="flex justify-between items-center text-xs font-bold mb-2">
                        <span class="text-gray-500 uppercase tracking-wider">AI Compatibility</span>
                        <span class="<?php echo e($score['color'] === 'green' ? 'text-green-500' : ($score['color'] === 'blue' ? 'text-blue-500' : ($score['color'] === 'yellow' ? 'text-yellow-500' : 'text-red-500'))); ?>">
                            <?php echo e($score['label']); ?>

                        </span>
                    </div>
                    <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden
                            <?php echo e($score['color'] === 'green' ? 'bg-gradient-to-r from-green-400 to-emerald-500' : ($score['color'] === 'blue' ? 'bg-gradient-to-r from-blue-400 to-cyan-500' : ($score['color'] === 'yellow' ? 'bg-gradient-to-r from-yellow-400 to-orange-500' : 'bg-gradient-to-r from-red-400 to-rose-500'))); ?>"
                            style="width: <?php echo e($score['score']); ?>%">
                            <div class="absolute inset-0 bg-white/20 w-1/2 -skew-x-12 translate-x-full animate-[shimmer_2s_infinite]"></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="mt-12 flex justify-center"><?php echo e($results->withQueryString()->links()); ?></div>
<?php endif; ?>
<?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\partials\search-results.blade.php ENDPATH**/ ?>