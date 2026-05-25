<?php $__env->startSection('title', 'Post Innovation Challenge'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?php echo e(route('corporate.dashboard')); ?>" class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-purple-600 transition">← Back</a>
    </div>

    <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white font-outfit">Post Innovation Challenge</h1>
    <p class="text-gray-500 mb-8 font-medium">Define your corporate problem and find the startup with the right technical solution.</p>

    <form method="POST" action="<?php echo e(route('corporate.challenges.store')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Challenge Title</label>
                <input type="text" name="title" required value="<?php echo e(old('title')); ?>" placeholder="e.g. Next-Gen Generative AI Recommendation System" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white">
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Industry Sector</label>
                    <select name="industry_id" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white">
                        <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($i->id); ?>" <?php echo e(old('industry_id') == $i->id ? 'selected' : ''); ?>><?php echo e($i->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Deadline</label>
                    <input type="date" name="deadline" required value="<?php echo e(old('deadline')); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Problem Statement & Description</label>
                <textarea name="description" rows="5" required placeholder="Describe the corporate challenge in detail, including the exact core issues and goals..." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white"><?php echo e(old('description')); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Technical & Submission Requirements</label>
                <textarea name="requirements" rows="4" placeholder="Detail any mandatory stack requirements, certifications, timeline rules, or team sizes..." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white"><?php echo e(old('requirements')); ?></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Min Budget (₹)</label>
                    <input type="number" name="budget_min" min="0" required value="<?php echo e(old('budget_min', 0)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Max Budget (₹)</label>
                    <input type="number" name="budget_max" min="0" required value="<?php echo e(old('budget_max', 0)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Required Skills / Technology Tags <span class="text-xs text-gray-400">(comma-separated)</span></label>
                <input type="text" name="required_tags" value="<?php echo e(old('required_tags')); ?>" placeholder="e.g. AI, Python, Docker, PyTorch" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none text-gray-900 dark:text-white">
            </div>
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-xl shadow-xl shadow-primary-600/30 transition hover:scale-[1.01]">Post Innovation Challenge</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/corporate/challenges/create.blade.php ENDPATH**/ ?>