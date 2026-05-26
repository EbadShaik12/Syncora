<?php $__env->startSection('title', 'Apply: ' . $challenge->title); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-8">
    <?php echo $__env->make('components.back-button', ['fallback' => route('startup.challenges'), 'label' => 'Back to Challenges'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Challenge summary -->
    <div class="bg-gradient-to-br from-primary-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl mb-6">
        <div class="flex items-start gap-3 mb-3">
            <img src="<?php echo e($challenge->corporate->logoUrl()); ?>" class="w-12 h-12 rounded-xl object-cover bg-white">
            <div class="flex-1">
                <p class="text-xs opacity-80"><?php echo e($challenge->corporate->companyName()); ?></p>
                <h1 class="text-2xl font-bold"><?php echo e($challenge->title); ?></h1>
            </div>
        </div>
        <p class="text-sm opacity-90 mb-3"><?php echo e($challenge->description); ?></p>
        <div class="flex flex-wrap gap-3 text-xs mb-3">
            <span class="px-2.5 py-1 bg-white/20 rounded-full">💰 ₹<?php echo e(number_format($challenge->budget_min)); ?> – ₹<?php echo e(number_format($challenge->budget_max)); ?></span>
            <span class="px-2.5 py-1 bg-white/20 rounded-full">📅 Deadline: <?php echo e($challenge->deadline->format('M d, Y')); ?></span>
        </div>
        <?php if($challenge->attachment_path): ?>
            <div class="pt-3 border-t border-white/20">
                <a href="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-bold text-white transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Reference Brief: <?php echo e($challenge->attachment_filename); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($challenge->requirements): ?>
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6 text-sm">
        <p class="font-semibold mb-1">Requirements:</p>
        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line"><?php echo e($challenge->requirements); ?></p>
    </div>
    <?php endif; ?>

    <!-- Application form -->
    <form method="POST" action="<?php echo e(route('startup.challenges.apply.store', $challenge)); ?>" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 space-y-5">
        <?php echo csrf_field(); ?>
        <h2 class="font-bold text-lg">Your Application</h2>

        <div>
            <label class="block text-sm font-medium mb-2">Cover Letter <span class="text-xs text-gray-400">(50-2000 chars)</span></label>
            <textarea name="cover_letter" rows="5" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none" placeholder="Tell them why you're the right fit..."><?php echo e(old('cover_letter')); ?></textarea>
            <?php $__errorArgs = ['cover_letter'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Your Approach / Solution <span class="text-xs text-gray-400">(optional, max 3000 chars)</span></label>
            <textarea name="approach" rows="6" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none" placeholder="Describe your technical approach, timeline, and deliverables..."><?php echo e(old('approach')); ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Proposal Document <span class="text-xs text-gray-400">(PDF / DOC, max 5MB)</span></label>
            <input type="file" name="proposal_file" accept=".pdf,.doc,.docx" class="block w-full text-sm">
            <?php $__errorArgs = ['proposal_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-primary-600/30 transition hover:scale-[1.01]">Submit Application</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\startup\apply.blade.php ENDPATH**/ ?>