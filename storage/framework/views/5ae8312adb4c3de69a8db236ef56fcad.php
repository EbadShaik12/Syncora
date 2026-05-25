<?php $__env->startSection('title', 'My Innovation Challenges'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 animate-fade-in">
    <?php echo $__env->make('components.back-button', ['fallback' => route('corporate.dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="flex flex-col sm:flex-row items-center justify-between mb-10 gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-xs font-bold uppercase tracking-widest text-purple-600 dark:text-purple-400 mb-4 border border-purple-200 dark:border-purple-800/50">
                Innovation Hub
            </div>
            <h1 class="text-4xl font-black font-outfit text-gray-900 dark:text-white mb-2">
                My <span class="text-gradient from-purple-500 to-pink-500">Innovation Challenges</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Manage your posted challenges, track application flows, and find the perfect startup partner.</p>
        </div>
        <a href="<?php echo e(route('corporate.challenges.create')); ?>" class="magnetic shimmer-btn bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg hover:scale-105 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Post New Challenge
        </a>
    </div>

    <?php if($challenges->isEmpty()): ?>
        <div class="glass-card-strong rounded-[2.5rem] p-16 text-center border-glow max-w-2xl mx-auto">
            <div class="w-24 h-24 mx-auto rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 mb-6 shadow-lg">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-3 font-outfit">No Innovation Challenges Posted</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-8 font-medium">Post open innovation challenges to attract top startups with high-growth technology solutions.</p>
            <a href="<?php echo e(route('corporate.challenges.create')); ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-xl shadow-purple-500/20 hover:scale-105 transition">
                Create First Challenge
            </a>
        </div>
    <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-container">
            <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="reveal glass-card-strong rounded-3xl p-6 border-glow flex flex-col hover:scale-[1.02] transition-all duration-300 relative group"
                     style="transition-delay: <?php echo e($i * 100); ?>ms">
                    
                    
                    <div class="flex justify-between items-start mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-xl shadow-sm <?php echo e($challenge->statusColor()); ?>">
                            <?php echo e(ucfirst($challenge->status)); ?>

                        </span>
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-black bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 px-3 py-1.5 rounded-xl border border-purple-100 dark:border-purple-800/50">
                                💼 <?php echo e($challenge->industry?->name); ?>

                            </span>
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition font-outfit line-clamp-2">
                        <?php echo e($challenge->title); ?>

                    </h3>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium leading-relaxed flex-grow line-clamp-3">
                        <?php echo e($challenge->description); ?>

                    </p>

                    <div class="border-t border-gray-100 dark:border-gray-800/50 pt-4 mb-6 space-y-2 text-xs font-semibold text-gray-500">
                        <div class="flex justify-between">
                            <span>Target Budget:</span>
                            <span class="text-purple-600 font-bold">₹<?php echo e(number_format($challenge->budget_min)); ?> – ₹<?php echo e(number_format($challenge->budget_max)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Deadline:</span>
                            <span class="text-gray-900 dark:text-white font-bold"><?php echo e($challenge->deadline->format('M d, Y')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Submissions:</span>
                            <span class="text-gray-900 dark:text-white font-black bg-gray-100 dark:bg-gray-800 px-2.5 py-0.5 rounded-lg"><?php echo e($challenge->applications_count); ?></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <a href="<?php echo e(route('corporate.challenges.applications', $challenge)); ?>" class="px-4 py-3 bg-purple-50 dark:bg-purple-900/30 border border-purple-100 dark:border-purple-800/50 text-purple-600 dark:text-purple-400 font-bold rounded-xl text-center text-xs hover:bg-purple-100 transition shadow-sm">
                            👥 View Proposals
                        </a>
                        <a href="<?php echo e(route('corporate.challenges.kanban', $challenge)); ?>" class="px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl text-center text-xs hover:scale-105 transition shadow-md shadow-purple-500/20">
                            📋 Kanban Flow
                        </a>
                    </div>
                    
                    
                    <div class="flex gap-2 justify-end mt-4 pt-3 border-t border-gray-100 dark:border-gray-800/50">
                        <a href="<?php echo e(route('corporate.challenges.edit', $challenge)); ?>" class="text-xs font-bold text-gray-500 hover:text-blue-500 transition flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20">
                            ✏️ Edit
                        </a>
                        <form action="<?php echo e(route('corporate.challenges.destroy', $challenge)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this challenge?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-600 transition flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                🗑️ Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/corporate/challenges/index.blade.php ENDPATH**/ ?>