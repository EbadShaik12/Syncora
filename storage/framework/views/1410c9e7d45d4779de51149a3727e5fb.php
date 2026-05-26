<?php $__env->startSection('title', 'Challenge Applications'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php echo $__env->make('components.back-button', ['fallback' => route('corporate.challenges.index'), 'label' => 'Back to Challenges'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Challenge Review</p>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white"><?php echo e($challenge->title); ?></h1>
        </div>
        <a href="<?php echo e(route('corporate.challenges.kanban', $challenge)); ?>" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl transition text-sm font-bold">View Kanban →</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="glass-card rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm relative group hover:border-purple-400 transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="<?php echo e($app->startup->logoUrl()); ?>" class="w-16 h-16 rounded-2xl object-cover shadow-sm bg-gray-100 dark:bg-gray-800">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white"><?php echo e($app->startup->companyName()); ?></h2>
                            <p class="text-xs text-gray-400 mt-0.5">Applied <?php echo e($app->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo e($app->statusColor()); ?>"><?php echo e($app->status); ?></span>
                </div>

                <div class="mt-6 space-y-4">
                    <?php if($app->startup->startupProfile): ?>
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Elevator Pitch</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($app->startup->startupProfile->elevator_pitch); ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Proposal & Cover Letter</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($app->cover_letter); ?></p>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                    <a href="<?php echo e(route('profile.show', $app->startup)); ?>" class="text-sm font-bold text-primary-600 hover:underline">View Full Profile</a>
                    
                    <div class="flex items-center gap-2">
                        <?php if($app->status === 'pending'): ?>
                        <form method="POST" action="<?php echo e(route('corporate.applications.shortlist', $app)); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition shadow-sm shadow-green-600/30">Shortlist</button>
                        </form>
                        <form method="POST" action="<?php echo e(route('corporate.applications.reject', $app)); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="px-4 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold transition">Reject</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-16 bg-gray-50/50 dark:bg-gray-900/30 rounded-3xl border border-dashed border-gray-200 dark:border-gray-800">
                <p class="text-gray-500 font-medium">No applications received yet for this challenge.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="space-y-6">
            <div class="glass-card rounded-3xl p-6 border border-gray-100 dark:border-gray-800">
                <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Challenge Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-400 block">Status</span>
                        <span class="font-bold capitalize text-gray-700 dark:text-gray-300"><?php echo e($challenge->status); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Budget Pool</span>
                        <span class="font-bold text-purple-600 dark:text-purple-400">₹<?php echo e(number_format($challenge->budget_min)); ?> - ₹<?php echo e(number_format($challenge->budget_max)); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Deadline</span>
                        <span class="font-bold text-gray-700 dark:text-gray-300"><?php echo e($challenge->deadline->format('M d, Y')); ?></span>
                    </div>
                    <?php if($challenge->attachment_path): ?>
                    <div class="pt-3 border-t border-gray-150 dark:border-gray-800">
                        <span class="text-gray-400 block mb-1">Attached Document</span>
                        <a href="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <?php echo e(Str::limit($challenge->attachment_filename, 18)); ?>

                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\corporate\challenges\applications.blade.php ENDPATH**/ ?>