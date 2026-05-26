<?php $__env->startSection('title', 'My Applications'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <?php echo $__env->make('components.back-button', ['fallback' => route('startup.dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">My Applications</h1>
        <a href="<?php echo e(route('startup.challenges')); ?>" class="text-sm text-primary-600 hover:underline">Browse challenges →</a>
    </div>

    <?php if($applications->isEmpty()): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center">
            <div class="text-5xl mb-3">📤</div>
            <p class="text-gray-500 mb-4">You haven't applied to any challenges yet.</p>
            <a href="<?php echo e(route('startup.challenges')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium">Browse Challenges</a>
        </div>
    <?php else: ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden divide-y divide-gray-100 dark:divide-gray-700">
            <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                <div class="flex items-start gap-4">
                    <img src="<?php echo e($app->challenge->corporate->logoUrl()); ?>" class="w-12 h-12 rounded-xl object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <p class="text-xs text-gray-500"><?php echo e($app->challenge->corporate->companyName()); ?></p>
                                <h3 class="font-semibold"><?php echo e($app->challenge->title); ?></h3>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium capitalize <?php echo e($app->statusColor()); ?>"><?php echo e($app->status); ?></span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-2"><?php echo e($app->cover_letter); ?></p>
                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                            <span>Applied <?php echo e($app->created_at->diffForHumans()); ?></span>
                            <?php if($app->proposal_file): ?><a href="<?php echo e(asset('storage/'.$app->proposal_file)); ?>" target="_blank" class="text-primary-600 hover:underline">📄 View proposal</a><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-4"><?php echo e($applications->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\startup\applications.blade.php ENDPATH**/ ?>