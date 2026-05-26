<?php $__env->startSection('title', 'Industry Management — Admin'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Administration</p>
            <h1 class="text-3xl font-black">Industry <span class="bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent">Management</span></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add, edit and remove industry categories used across the platform.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-xs font-black"><?php echo e($industries->count()); ?> Industries</span>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-xl text-sm font-medium border border-green-200 dark:border-green-800/40">✅ <?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-4 px-4 py-3 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded-xl text-sm font-medium border border-red-200 dark:border-red-800/40">❌ <?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-6">

        
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <h2 class="font-black text-lg mb-5 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white text-sm">+</span>
                Add Industry
            </h2>
            <form method="POST" action="<?php echo e(route('admin.industries.store')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Industry Name *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="e.g. HealthTech"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition text-sm" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Icon (emoji)</label>
                    <input type="text" name="icon" value="<?php echo e(old('icon')); ?>" placeholder="🏭"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-primary-500 outline-none transition text-sm">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-purple-600 text-white font-bold py-2.5 rounded-xl hover:opacity-90 transition shadow-md shadow-primary-500/20">
                    Add Industry
                </button>
            </form>
        </div>

        
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/30">
                <h2 class="font-black text-sm uppercase tracking-wider text-gray-500">All Industries</h2>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php $__empty_1 = true; $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $industry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div x-data="{ editing: false }" class="px-6 py-4 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                    <div x-show="!editing" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl"><?php echo e($industry->icon ?? '🏭'); ?></span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white"><?php echo e($industry->name); ?></p>
                                <p class="text-xs text-gray-400"><?php echo e($industry->startup_profiles_count + $industry->corporate_profiles_count); ?> profiles using this</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold"><?php echo e($industry->startup_profiles_count); ?> startups</span>
                            <span class="px-2 py-0.5 rounded-full bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-xs font-bold"><?php echo e($industry->corporate_profiles_count); ?> corporates</span>
                            <button @click="editing = true" class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900/30 text-gray-500 hover:text-primary-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="<?php echo e(route('admin.industries.destroy', $industry)); ?>" onsubmit="return confirm('Delete <?php echo e($industry->name); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div x-show="editing" x-cloak>
                        <form method="POST" action="<?php echo e(route('admin.industries.update', $industry)); ?>" class="flex gap-3">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input type="text" name="icon" value="<?php echo e($industry->icon); ?>" class="w-16 px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none" placeholder="🏭">
                            <input type="text" name="name" value="<?php echo e($industry->name); ?>" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-primary-500" required>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-bold hover:bg-primary-700 transition">Save</button>
                            <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-sm font-bold hover:bg-gray-200 transition">Cancel</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-12 text-center text-gray-400 text-sm">No industries yet. Add your first one!</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\admin\industries.blade.php ENDPATH**/ ?>