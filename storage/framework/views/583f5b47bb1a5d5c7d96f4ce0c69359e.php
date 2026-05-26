<?php $__env->startSection('title', 'Awaiting Approval — Syncora'); ?>

<?php $__env->startPush('styles'); ?>
<style>
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
@keyframes pulse-ring { 0%{transform:scale(0.95);opacity:1} 100%{transform:scale(1.2);opacity:0} }
@keyframes shimmer { 0%{background-position:-600px 0} 100%{background-position:600px 0} }

.float-icon { animation: float 4s ease-in-out infinite; }
.pulse-ring::before {
    content: '';
    position: absolute; inset: -12px;
    border-radius: 50%;
    border: 2px solid #f59e0b;
    animation: pulse-ring 2s ease-out infinite;
}
.step-bar {
    background: linear-gradient(90deg,
        rgba(156,163,175,0.15) 25%,
        rgba(156,163,175,0.35) 37%,
        rgba(156,163,175,0.15) 63%);
    background-size: 600px 100%;
    animation: shimmer 1.8s ease infinite;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-4 py-16 relative">

    
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-1/4 w-96 h-96 bg-amber-400/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-20 right-1/4 w-80 h-80 bg-primary-500/10 rounded-full blur-[80px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg">

        
        <div class="glass-card-strong rounded-[2.5rem] p-10 text-center shadow-2xl border border-amber-200/30 dark:border-amber-800/20">

            
            <div class="relative inline-flex mb-8">
                <div class="pulse-ring relative w-24 h-24 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-xl shadow-amber-500/40 float-icon">
                    <svg class="w-12 h-12 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            
            <h1 class="text-3xl font-black font-outfit mb-3 text-gray-900 dark:text-white">
                Application <span class="text-gradient">Under Review</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium leading-relaxed mb-8">
                Thanks for joining Syncora! Our team is reviewing your profile to ensure a high-quality experience for everyone on the platform.
            </p>

            
            <div class="bg-gray-50/80 dark:bg-gray-800/50 rounded-2xl p-6 mb-8 text-left space-y-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Approval Progress</h3>

                
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-xl bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Account Created</p>
                        <p class="text-xs text-gray-400">Your profile has been submitted</p>
                    </div>
                    <span class="text-xs font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-lg">Done</span>
                </div>

                
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center flex-shrink-0 relative overflow-hidden">
                        <div class="absolute inset-0 step-bar rounded-xl"></div>
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Admin Review</p>
                        <p class="text-xs text-gray-400">Our team is verifying your details</p>
                    </div>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded-lg animate-pulse">In Review</span>
                </div>

                
                <div class="flex items-center gap-4 opacity-40">
                    <div class="w-9 h-9 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Full Access Granted</p>
                        <p class="text-xs text-gray-400">Start matching, swiping & messaging</p>
                    </div>
                    <span class="text-xs font-bold text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-lg">Waiting</span>
                </div>
            </div>

            
            <div class="flex items-start gap-3 bg-primary-50/80 dark:bg-primary-900/20 rounded-2xl p-4 text-left mb-8 border border-primary-100 dark:border-primary-800/40">
                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs font-medium text-primary-700 dark:text-primary-300 leading-relaxed">
                    Approvals typically take <strong>24–48 hours</strong>. You'll receive a notification on your dashboard once reviewed. No action needed from your side.
                </p>
            </div>

            
            <div class="flex flex-col gap-3">
                <a href="<?php echo e(route('home')); ?>"
                   class="shimmer-btn w-full bg-gradient-to-r from-primary-600 to-purple-600 text-white px-6 py-3.5 rounded-2xl font-bold shadow-xl hover:scale-105 transition text-center">
                    Back to Home
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full glass-card border border-white/20 dark:border-gray-700 text-gray-600 dark:text-gray-400 px-6 py-3.5 rounded-2xl font-bold hover:bg-white/10 transition text-center text-sm">
                        Sign Out
                    </button>
                </form>
            </div>

        </div>

        
        <p class="text-center text-xs text-gray-400 mt-6 font-medium">
            Have questions? Contact us at
            <a href="mailto:support@syncora.in" class="text-primary-600 dark:text-primary-400 hover:underline font-bold">support@syncora.in</a>
        </p>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\auth\pending.blade.php ENDPATH**/ ?>