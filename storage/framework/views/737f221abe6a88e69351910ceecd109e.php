<?php $__env->startSection('title', 'Platform Reports & Exports — Admin'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <?php echo $__env->make('components.back-button', ['fallback' => route('admin.dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Administration</p>
            <h1 class="text-3xl font-black">System <span class="bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent">Reports &amp; Exports</span></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor system metrics, growth trends, and download platform data securely.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.reports.export.users')); ?>" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-sm shadow-md shadow-primary-500/20 hover:scale-105 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Users CSV
            </a>
            <a href="<?php echo e(route('admin.reports.export.connections')); ?>" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 rounded-xl font-bold text-sm shadow-md hover:scale-105 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Matches CSV
            </a>
        </div>
    </div>

    
    <div class="mb-8">
        <h3 class="font-black text-lg mb-4 flex items-center gap-2 text-gray-900 dark:text-white">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
            System Health Metrics
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-2xl p-5 hover:shadow-md transition">
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Total Users Registered</p>
                <p class="text-3xl font-black text-slate-800 dark:text-white mt-1"><?php echo e($health['db_users']); ?></p>
                <div class="flex items-center gap-1.5 mt-2">
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 font-bold">
                        <?php echo e($health['pending_users']); ?> pending approval
                    </span>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-2xl p-5 hover:shadow-md transition">
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Platform Matches</p>
                <p class="text-3xl font-black text-slate-800 dark:text-white mt-1"><?php echo e($health['db_connections']); ?></p>
                <div class="flex items-center gap-1.5 mt-2">
                    <span class="text-[10px] text-slate-450 dark:text-zinc-500 font-semibold">Total active connections</span>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-2xl p-5 hover:shadow-md transition">
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Chat Messages Sent</p>
                <p class="text-3xl font-black text-slate-800 dark:text-white mt-1"><?php echo e($health['db_messages']); ?></p>
                <div class="flex items-center gap-1.5 mt-2">
                    <span class="text-[10px] text-slate-450 dark:text-zinc-500 font-semibold">User engagement metrics</span>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-2xl p-5 hover:shadow-md transition">
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Interest Swipes</p>
                <p class="text-3xl font-black text-slate-800 dark:text-white mt-1"><?php echo e($health['db_signals']); ?></p>
                <div class="flex items-center gap-1.5 mt-2">
                    <span class="text-[10px] text-slate-450 dark:text-zinc-500 font-semibold">Total right and left swipes</span>
                </div>
            </div>

        </div>
    </div>

    
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        
        
        <div class="md:col-span-2 bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-3xl p-6 shadow-sm">
            <h3 class="font-black text-lg text-slate-800 dark:text-white mb-4">Monthly Registration &amp; Match Growth</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-zinc-800 pb-3">
                            <th class="pb-3 font-black">Month</th>
                            <th class="pb-3 font-black">Startups Joined</th>
                            <th class="pb-3 font-black">Corporates Joined</th>
                            <th class="pb-3 font-black">New Connections</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        <?php $__currentLoopData = $growth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-3.5 font-bold text-slate-900 dark:text-white"><?php echo e($g['month']); ?></td>
                            <td class="py-3.5 text-slate-600 dark:text-zinc-300">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    <?php echo e($g['startups']); ?>

                                </span>
                            </td>
                            <td class="py-3.5 text-slate-600 dark:text-zinc-300">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    <?php echo e($g['corporates']); ?>

                                </span>
                            </td>
                            <td class="py-3.5 font-extrabold text-pink-650 dark:text-pink-400">
                                <?php echo e($g['connections']); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-3xl p-6 shadow-sm flex flex-col">
            <h3 class="font-black text-lg text-slate-800 dark:text-white mb-5">Queue &amp; Pipeline Status</h3>
            
            <div class="space-y-5 flex-1 flex flex-col justify-center">
                
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-lg">💡</div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                            <span>Open Challenges</span>
                            <span class="text-slate-800 dark:text-white"><?php echo e($health['open_challenges']); ?></span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-zinc-800 rounded-full mt-1.5 overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: <?php echo e(min(100, max(20, $health['open_challenges'] * 5))); ?>%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 text-lg">📝</div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                            <span>Pending Submissions</span>
                            <span class="text-slate-800 dark:text-white"><?php echo e($health['pending_apps']); ?></span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-zinc-800 rounded-full mt-1.5 overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full" style="width: <?php echo e(min(100, max(20, $health['pending_apps'] * 8))); ?>%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 text-lg">⏳</div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                            <span>Pending Account Approvals</span>
                            <span class="text-slate-800 dark:text-white"><?php echo e($health['pending_users']); ?></span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-zinc-800 rounded-full mt-1.5 overflow-hidden">
                            <div class="h-full bg-yellow-500 rounded-full" style="width: <?php echo e(min(100, max(20, $health['pending_users'] * 10))); ?>%"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\admin\reports.blade.php ENDPATH**/ ?>