<?php $__env->startSection('title', 'Badges & Gamification — Admin'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Administration</p>
            <h1 class="text-3xl font-black">Badges & <span class="bg-gradient-to-r from-yellow-500 to-orange-500 bg-clip-text text-transparent">Gamification</span></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create achievement badges and manually award them to users.</p>
        </div>
        <span class="px-3 py-1.5 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-xs font-black"><?php echo e($badges->count()); ?> Badges</span>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-xl text-sm font-medium border border-green-200">✅ <?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-4 px-4 py-3 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded-xl text-sm font-medium border border-red-200">❌ <?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-6">

        
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <h2 class="font-black text-lg mb-5 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white text-sm">🏅</span>
                Create Badge
            </h2>
            <form method="POST" action="<?php echo e(route('admin.badges.store')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Badge Name *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="e.g. First Connection" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-yellow-400 outline-none transition text-sm">
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
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description *</label>
                    <textarea name="description" rows="2" placeholder="What is this badge for?" required
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-yellow-400 outline-none transition text-sm resize-none"><?php echo e(old('description')); ?></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Icon</label>
                        <input type="text" name="icon" value="<?php echo e(old('icon', '⭐')); ?>" placeholder="⭐"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 outline-none text-sm text-center text-2xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Criteria Type *</label>
                        <select name="criteria_type" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 outline-none text-sm">
                            <option value="manual">Manual</option>
                            <option value="connections">Connections</option>
                            <option value="applications">Applications</option>
                            <option value="swipes">Swipes</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Criteria Value <span class="text-gray-400 font-normal">(for auto-award)</span></label>
                    <input type="number" name="criteria_value" value="<?php echo e(old('criteria_value')); ?>" placeholder="e.g. 10"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 outline-none text-sm">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold py-2.5 rounded-xl hover:opacity-90 transition shadow-md shadow-orange-500/20">
                    🏅 Create Badge
                </button>
            </form>
        </div>

        
        <div class="lg:col-span-2 space-y-4">

            <?php $__empty_1 = true; $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div x-data="{ showAward: false }" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <?php
                            $iconMap = [
                                'chat' => '💬',
                                'message' => '✉️',
                                'handshake' => '🤝',
                                'bolt' => '⚡',
                                'star' => '⭐',
                                'rocket' => '🚀',
                                'trophy' => '🏆',
                                'crown' => '👑',
                                'check' => '✅',
                                'book' => '📖',
                            ];
                            $displayIcon = isset($badge->icon) ? ($iconMap[strtolower(trim($badge->icon))] ?? $badge->icon) : '⭐';
                        ?>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-3xl shadow-md shadow-orange-500/20 flex-shrink-0">
                            <?php echo e($displayIcon); ?>

                        </div>
                        <div>
                            <h3 class="font-black text-lg text-gray-900 dark:text-white"><?php echo e($badge->name); ?></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($badge->description); ?></p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="px-2 py-0.5 rounded-full bg-orange-50 dark:bg-orange-900/30 text-orange-600 text-xs font-bold"><?php echo e($badge->criteria_type); ?></span>
                                <?php if($badge->criteria_value): ?>
                                    <span class="text-xs text-gray-400">≥ <?php echo e($badge->criteria_value); ?></span>
                                <?php endif; ?>
                                <span class="px-2 py-0.5 rounded-full bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 text-xs font-bold"><?php echo e($badge->users_count); ?> awarded</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button @click="showAward = !showAward"
                                class="px-3 py-1.5 rounded-xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold hover:bg-primary-100 transition">
                            🎖 Award
                        </button>
                        <form method="POST" action="<?php echo e(route('admin.badges.destroy', $badge)); ?>" onsubmit="return confirm('Delete this badge?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="p-1.5 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-400 hover:text-red-600 hover:bg-red-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                
                <div x-show="showAward" x-cloak class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Award to a User</p>
                    <form method="POST" action="<?php echo e(route('admin.badges.award', $badge)); ?>" class="flex gap-2">
                        <?php echo csrf_field(); ?>
                        <select name="user_id" required class="flex-1 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-yellow-400">
                            <option value="">Select a user...</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u->id); ?>"><?php echo e($u->companyName()); ?> (<?php echo e($u->role); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold rounded-xl text-sm hover:opacity-90 transition whitespace-nowrap">
                            Award Badge
                        </button>
                    </form>
                </div>

                
                <?php if($badge->users_count > 0): ?>
                <div class="mt-3 flex items-center gap-2">
                    <span class="text-xs text-gray-400 font-medium">Holders:</span>
                    <?php $__currentLoopData = $badge->users->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative group">
                        <img src="<?php echo e($holder->logoUrl()); ?>" class="w-7 h-7 rounded-lg border-2 border-white dark:border-gray-900 shadow" data-tooltip="<?php echo e($holder->companyName()); ?>">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($badge->users_count > 6): ?>
                        <span class="text-xs text-gray-400">+<?php echo e($badge->users_count - 6); ?> more</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mx-auto mb-4 text-3xl">🏅</div>
                <p class="text-gray-500 font-medium">No badges yet. Create your first achievement badge!</p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\admin\badges.blade.php ENDPATH**/ ?>