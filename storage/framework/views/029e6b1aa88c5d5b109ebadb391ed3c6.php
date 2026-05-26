<?php $__env->startSection('title', 'Application Rejected'); ?>
<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-10">
        <div class="w-20 h-20 mx-auto rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <h2 class="text-2xl font-bold mb-3">Account Not Approved</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Unfortunately, your account application has been rejected. Please contact support if you think this was an error.</p>
        <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="text-primary-600 hover:underline text-sm font-medium">Sign out</button></form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\auth\rejected.blade.php ENDPATH**/ ?>