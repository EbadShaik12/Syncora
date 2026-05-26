<?php $__env->startSection('title', 'Content Moderation — Admin'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Administration</p>
            <h1 class="text-3xl font-black">Content <span class="bg-gradient-to-r from-red-500 to-orange-500 bg-clip-text text-transparent">Moderation</span></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review flagged profiles, reports and take action.</p>
        </div>
    </div>

    
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xl">⏳</div>
            <div>
                <p class="text-3xl font-black text-amber-700 dark:text-amber-300"><?php echo e($pendingCount); ?></p>
                <p class="text-sm font-bold text-amber-600 dark:text-amber-400">Pending Review</p>
            </div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/30 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center text-white text-xl">✅</div>
            <div>
                <p class="text-3xl font-black text-green-700 dark:text-green-300"><?php echo e($reviewedCount); ?></p>
                <p class="text-sm font-bold text-green-600 dark:text-green-400">Reviewed</p>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-400 flex items-center justify-center text-white text-xl">🚫</div>
            <div>
                <p class="text-3xl font-black text-gray-700 dark:text-gray-300"><?php echo e($dismissedCount); ?></p>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Dismissed</p>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-xl text-sm font-medium">✅ <?php echo e(session('success')); ?></div>
    <?php endif; ?>

    
    <form method="GET" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 mb-6 flex gap-3">
        <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-red-400">
            <option value="">All Statuses</option>
            <option value="pending" <?php if(request('status')=='pending'): echo 'selected'; endif; ?>>⏳ Pending</option>
            <option value="reviewed" <?php if(request('status')=='reviewed'): echo 'selected'; endif; ?>>✅ Reviewed</option>
            <option value="dismissed" <?php if(request('status')=='dismissed'): echo 'selected'; endif; ?>>🚫 Dismissed</option>
        </select>
        <select name="reason" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-red-400">
            <option value="">All Reasons</option>
            <option value="spam" <?php if(request('reason')=='spam'): echo 'selected'; endif; ?>>Spam</option>
            <option value="inappropriate" <?php if(request('reason')=='inappropriate'): echo 'selected'; endif; ?>>Inappropriate</option>
            <option value="fake" <?php if(request('reason')=='fake'): echo 'selected'; endif; ?>>Fake Account</option>
            <option value="harassment" <?php if(request('reason')=='harassment'): echo 'selected'; endif; ?>>Harassment</option>
            <option value="other" <?php if(request('reason')=='other'): echo 'selected'; endif; ?>>Other</option>
        </select>
        <button type="submit" class="px-5 py-2 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition">Filter</button>
        <a href="<?php echo e(route('admin.moderation.index')); ?>" class="px-5 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition">Reset</a>
    </form>

    
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <th class="px-6 py-3">Reported Content</th>
                        <th class="px-6 py-3">Reason</th>
                        <th class="px-6 py-3">Reporter</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $flags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4">
                            <?php if($flag->flaggable): ?>
                                <?php if($flag->flaggable instanceof \App\Models\User): ?>
                                    <div class="flex items-center gap-2">
                                        <img src="<?php echo e($flag->flaggable->logoUrl()); ?>" class="w-8 h-8 rounded-lg">
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white text-xs"><?php echo e($flag->flaggable->companyName()); ?></p>
                                            <p class="text-[10px] text-gray-400"><?php echo e(ucfirst($flag->flaggable->role)); ?> account</p>
                                        </div>
                                    </div>
                                <?php elseif($flag->flaggable instanceof \App\Models\Challenge): ?>
                                    <div>
                                        <p class="font-bold text-xs text-gray-900 dark:text-white"><?php echo e($flag->flaggable->title); ?></p>
                                        <p class="text-[10px] text-gray-400">Challenge</p>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400"><?php echo e(class_basename($flag->flaggable_type)); ?> #<?php echo e($flag->flaggable_id); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-xs text-red-400">Content deleted</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $reasonColor = match($flag->reason) {
                                    'spam' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
                                    'inappropriate' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    'fake' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                                    'harassment' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            ?>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold capitalize <?php echo e($reasonColor); ?>"><?php echo e($flag->reason); ?></span>
                            <?php if($flag->notes): ?>
                                <p class="text-[11px] text-gray-400 mt-1 max-w-[200px] truncate" title="<?php echo e($flag->notes); ?>"><?php echo e($flag->notes); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($flag->reporter): ?>
                                <div class="flex items-center gap-2">
                                    <img src="<?php echo e($flag->reporter->logoUrl()); ?>" class="w-6 h-6 rounded-md">
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300"><?php echo e($flag->reporter->companyName()); ?></span>
                                </div>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Deleted user</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400"><?php echo e($flag->created_at->diffForHumans()); ?></td>
                        <td class="px-6 py-4">
                            <?php
                                $sc = match($flag->status) {
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                    'reviewed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                    'dismissed' => 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                };
                            ?>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold capitalize <?php echo e($sc); ?>"><?php echo e($flag->status); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1 justify-end">
                                <?php if($flag->status === 'pending'): ?>
                                    <form method="POST" action="<?php echo e(route('admin.moderation.review', $flag)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="reviewed">
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 transition">✅ Resolve</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.moderation.review', $flag)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="dismissed">
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-200 transition">🚫 Dismiss</button>
                                    </form>
                                    <?php if($flag->flaggable instanceof \App\Models\User): ?>
                                    <form method="POST" action="<?php echo e(route('admin.moderation.ban', $flag)); ?>" onsubmit="return confirm('Suspend this user?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 transition">🔨 Ban</button>
                                    </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">Reviewed <?php echo e($flag->reviewed_at?->diffForHumans()); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-16 text-gray-400">
                            <div class="text-4xl mb-3">🛡️</div>
                            <p class="font-medium">No flagged content. Platform is clean!</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4"><?php echo e($flags->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\admin\moderation.blade.php ENDPATH**/ ?>