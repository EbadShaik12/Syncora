<?php $__env->startSection('title', 'Notifications'); ?>
<?php $__env->startSection('content'); ?>

<div class="max-w-3xl mx-auto px-4 py-8">
    <?php echo $__env->make('components.back-button', ['fallback' => route('dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">Notifications</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Stay up to date with your matches, messages, and opportunities</p>
        </div>
        <form method="POST" action="<?php echo e(route('notifications.readAll')); ?>">
            <?php echo csrf_field(); ?>
            <button class="flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 transition px-4 py-2 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Mark all read
            </button>
        </form>
    </div>

    <?php if($notifications->isEmpty()): ?>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-16 text-center shadow-sm">
            <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-primary-100 to-purple-100 dark:from-primary-900/30 dark:to-purple-900/30 flex items-center justify-center mb-4 text-4xl">🔔</div>
            <h3 class="text-xl font-bold mb-2">All caught up!</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">No notifications yet. Start discovering partners to get notified.</p>
        </div>
    <?php else: ?>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden divide-y divide-gray-100 dark:divide-gray-800">
            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($n->link ?? '#'); ?>"
                class="flex items-start gap-4 p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition group <?php echo e($n->isUnread() ? 'bg-primary-50/40 dark:bg-primary-900/10' : ''); ?>">

                
                <div class="w-11 h-11 rounded-2xl <?php echo e($n->iconColor()); ?> flex items-center justify-center text-white flex-shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($n->iconSvg()); ?>"/>
                    </svg>
                </div>

                
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 mb-1">
                        <p class="font-semibold text-sm <?php echo e($n->isUnread() ? 'text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300'); ?>">
                            <?php echo e($n->title); ?>

                        </p>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs text-gray-400"><?php echo e($n->created_at->diffForHumans()); ?></span>
                            <?php if($n->isUnread()): ?>
                                <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed"><?php echo e($n->body); ?></p>

                    
                    <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide
                        <?php if($n->type === 'match'): ?> bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300
                        <?php elseif($n->type === 'message'): ?> bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                        <?php elseif($n->type === 'application'): ?> bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                        <?php elseif($n->type === 'challenge'): ?> bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300
                        <?php elseif($n->type === 'badge'): ?> bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                        <?php else: ?> bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 <?php endif; ?>">
                        <?php echo e($n->type); ?>

                    </span>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-5"><?php echo e($notifications->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/notifications.blade.php ENDPATH**/ ?>