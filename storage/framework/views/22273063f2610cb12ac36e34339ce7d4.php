<?php $__env->startSection('title', 'Admin Login'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .input-group { position: relative; }
    .input-group input {
        padding: 1.25rem 1rem 0.5rem 1rem;
        background: rgba(0, 0, 0, 0.2);
    }
    .input-group label {
        position: absolute; left: 1rem; top: 1.1rem;
        transition: all 0.2s ease; pointer-events: none;
        color: #9ca3af; font-size: 0.875rem; font-weight: 500;
    }
    .input-group input:focus ~ label,
    .input-group input:not(:placeholder-shown) ~ label {
        top: 0.35rem; font-size: 0.65rem; color: #f59e0b;
        text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;
    }
    @keyframes shieldPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
        50%       { box-shadow: 0 0 0 20px rgba(245, 158, 11, 0); }
    }
    .shield-pulse { animation: shieldPulse 2.5s ease-in-out infinite; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-transparent px-4 py-12">

    
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-orange-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md relative stagger-container">

        
        <div class="reveal text-center mb-8">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex flex-col items-center gap-4 mb-2 group">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-amber-400 to-orange-600 flex items-center justify-center shadow-2xl shadow-amber-500/40 group-hover:scale-110 transition-transform duration-300 shield-pulse">
                    <svg class="w-10 h-10 text-white drop-shadow-md" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                </div>
                <span class="font-black font-outfit text-2xl bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Admin Portal</span>
            </a>
            <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium text-sm">Restricted access — administrators only</p>
        </div>

        
        <div class="reveal reveal-delay-1 rounded-3xl p-8 shadow-2xl relative overflow-hidden border border-amber-500/20"
             style="background: rgba(13,13,18,0.88); backdrop-filter: blur(24px); box-shadow: 0 30px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(245,158,11,0.1);">

            
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-amber-500 to-transparent rounded-t-3xl"></div>

            <h2 class="text-2xl font-black text-white mb-1 font-outfit">Sign In</h2>
            <p class="text-gray-500 text-sm mb-7">Enter your admin credentials to continue</p>

            <form method="POST" action="<?php echo e(route('admin.login.post')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>

                <div class="input-group">
                    <input type="email" name="email" id="admin_email" value="<?php echo e(old('email')); ?>" required autofocus placeholder=" "
                           class="w-full rounded-xl border border-gray-700/50 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/30 outline-none transition text-white bg-black/20">
                    <label for="admin_email">Email Address</label>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-400 text-xs mt-1 font-semibold"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="admin_password" required placeholder=" "
                           class="w-full rounded-xl border border-gray-700/50 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/30 outline-none transition text-white bg-black/20">
                    <label for="admin_password">Password</label>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-400 text-xs mt-1 font-semibold"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-amber-500 to-orange-600 text-white font-black py-4 rounded-xl shadow-xl shadow-amber-500/30 transition-all hover:scale-[1.02] hover:shadow-amber-500/50 flex items-center justify-center gap-2 mt-6 shimmer-btn">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                    Access Admin Panel
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/5 text-center">
                <p class="text-gray-500 text-sm">Not an admin?
                    <a href="<?php echo e(route('login')); ?>" class="text-amber-400 font-bold hover:text-amber-300 transition">Regular Login →</a>
                </p>
                <p class="text-gray-600 text-xs mt-3">
                    Need an admin account?
                    <a href="<?php echo e(route('admin.register')); ?>" class="text-gray-500 hover:text-amber-400 font-semibold transition">Register here</a>
                </p>
            </div>
        </div>

        
        <div class="reveal reveal-delay-2 mt-5 flex items-center justify-center gap-2 text-xs text-gray-600 font-semibold">
            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
            </svg>
            <span>Secured & encrypted admin session</span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\auth\admin-login.blade.php ENDPATH**/ ?>