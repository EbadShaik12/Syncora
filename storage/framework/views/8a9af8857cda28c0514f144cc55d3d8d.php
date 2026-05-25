<?php $__env->startSection('title', 'Sign In'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Floating labels */
    .input-group { position: relative; }
    .input-group input { 
        padding: 1.25rem 1rem 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.4);
    }
    .dark .input-group input { background: rgba(0, 0, 0, 0.2); }
    .input-group label {
        position: absolute; left: 1rem; top: 1.1rem;
        transition: all 0.2s ease; pointer-events: none;
        color: #6b7280; font-size: 0.875rem; font-weight: 500;
    }
    .dark .input-group label { color: #9ca3af; }
    .input-group input:focus ~ label, .input-group input:not(:placeholder-shown) ~ label {
        top: 0.35rem; font-size: 0.65rem; color: #6366f1; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;
    }
    .dark .input-group input:focus ~ label, .dark .input-group input:not(:placeholder-shown) ~ label { color: #a855f7; }

    /* Left panel 3D stack */
    .card-stack { position: relative; perspective: 1200px; transform-style: preserve-3d; height: 350px; width: 100%; display: flex; justify-content: center; align-items: center; }
    .stack-card { 
        position: absolute; width: 280px; height: 180px; border-radius: 1.5rem;
        transition: all 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .stack-card:nth-child(1) { transform: translateZ(-100px) translateY(-30px) rotateX(10deg); opacity: 0.5; background: linear-gradient(135deg, #4f46e5, #7c3aed); }
    .stack-card:nth-child(2) { transform: translateZ(-50px) translateY(-15px) rotateX(5deg); opacity: 0.8; background: linear-gradient(135deg, #ec4899, #8b5cf6); }
    .stack-card:nth-child(3) { transform: translateZ(0) translateY(0) rotateX(0); opacity: 1; background: linear-gradient(135deg, #3b82f6, #06b6d4); border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px); }
    
    .card-stack:hover .stack-card:nth-child(1) { transform: translateZ(-120px) translateY(-50px) rotateX(15deg) rotateY(-10deg); }
    .card-stack:hover .stack-card:nth-child(2) { transform: translateZ(-60px) translateY(-25px) rotateX(10deg) rotateY(10deg); }
    .card-stack:hover .stack-card:nth-child(3) { transform: translateZ(20px) translateY(10px) rotateX(5deg) scale(1.05); }

</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="min-h-screen flex relative overflow-hidden bg-transparent">
    
    
    <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center relative z-10 p-12 border-r border-gray-200/20 dark:border-white/5 bg-white/20 dark:bg-gray-900/40 backdrop-blur-3xl">
        
        <div class="w-full max-w-lg text-center stagger-container">
            <h2 class="reveal reveal-delay-1 text-4xl font-black mb-4 leading-tight font-outfit text-gray-900 dark:text-white">Welcome Back to<br><span class="text-gradient">StartupConnect</span></h2>
            <p class="reveal reveal-delay-2 text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-12 font-medium">Log in to access your dashboard, discover new partners, and manage your innovation pipeline.</p>
            
            
            <div class="reveal reveal-delay-3 card-stack mb-12">
                <div class="stack-card flex flex-col justify-between p-5 text-white">
                    <div class="w-10 h-10 rounded-full bg-white/20"></div>
                    <div class="h-4 w-3/4 bg-white/20 rounded"></div>
                </div>
                <div class="stack-card flex flex-col justify-between p-5 text-white">
                    <div class="flex justify-between items-center">
                        <div class="w-12 h-12 rounded-xl bg-white/20"></div>
                        <div class="w-8 h-4 rounded-full bg-green-400"></div>
                    </div>
                    <div class="space-y-2"><div class="h-2 w-full bg-white/20 rounded"></div><div class="h-2 w-2/3 bg-white/20 rounded"></div></div>
                </div>
                <div class="stack-card flex flex-col p-5 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 blur-xl"></div>
                    <div class="relative z-10 flex items-center justify-between mb-4">
                        <div class="font-bold font-outfit">Match Found!</div>
                        <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mt-auto">
                        <div class="w-10 h-10 rounded-full bg-white/30 border border-white/50 backdrop-blur-md"></div>
                        <div class="w-10 h-10 rounded-full bg-white/30 border border-white/50 backdrop-blur-md -ml-4"></div>
                        <div class="text-xs font-bold bg-white/20 px-2 py-1 rounded-md ml-auto backdrop-blur-md">95% Match</div>
                    </div>
                </div>
            </div>

            
            <div class="reveal reveal-delay-4 flex justify-center gap-4 mt-auto">
                <div class="glass-card rounded-2xl px-5 py-3 text-center w-32 border-glow">
                    <p class="text-2xl font-black font-outfit text-gray-900 dark:text-white">340+</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-0.5 font-bold">Connections</p>
                </div>
                <div class="glass-card rounded-2xl px-5 py-3 text-center w-32 border-glow">
                    <p class="text-2xl font-black font-outfit text-gray-900 dark:text-white">98%</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-0.5 font-bold">Satisfaction</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 relative z-10">
        <div class="w-full max-w-md relative stagger-container">
            
            <div class="reveal text-center mb-8 lg:mb-10">
                <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 mb-6 magnetic">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white shadow-xl shadow-primary-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </a>
                <h2 class="text-3xl font-black font-outfit">Sign In</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Enter your details to access your account</p>
            </div>

            
            <div class="reveal reveal-delay-2 glass-card-strong rounded-3xl p-8 shadow-2xl border-glow relative overflow-hidden">
                <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5 relative z-10">
                    <?php echo csrf_field(); ?>
                    
                    <div class="input-group">
                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required autofocus placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 outline-none transition text-gray-900 dark:text-white">
                        <label for="email">Email Address</label>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="input-group">
                        <input type="password" name="password" id="password" required placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 outline-none transition text-gray-900 dark:text-white">
                        <label for="password">Password</label>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="flex items-center justify-between mt-4">
                        <label class="flex items-center gap-2 text-sm cursor-pointer group">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 bg-white/50 dark:bg-gray-800/50">
                            <span class="text-gray-600 dark:text-gray-400 font-medium group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">Remember me</span>
                        </label>
                        <a href="#" class="text-xs font-semibold text-primary-600 hover:text-primary-500 transition">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="shimmer-btn w-full bg-gradient-to-r from-primary-600 to-purple-600 text-white font-bold py-3.5 rounded-xl shadow-xl shadow-primary-600/30 transition hover:scale-[1.02] mt-6 flex items-center justify-center gap-2">
                        Sign In
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
                
                <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 relative z-10 font-medium">
                    Don't have an account? <a href="<?php echo e(route('register')); ?>" class="text-primary-600 font-bold hover:underline">Sign up</a>
                </div>
            </div>

            
            <div class="reveal reveal-delay-3 mt-6 glass-card rounded-2xl p-5 text-sm border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    <p class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-xs">Demo Accounts</p>
                </div>
                <div class="grid gap-2 text-gray-600 dark:text-gray-400 text-xs font-medium">
                    <div class="flex justify-between items-center bg-white/50 dark:bg-gray-800/50 px-3 py-2 rounded-lg"><span class="font-bold text-primary-600 dark:text-primary-400">Admin</span> <span>admin@scp.test</span></div>
                    <div class="flex justify-between items-center bg-white/50 dark:bg-gray-800/50 px-3 py-2 rounded-lg"><span class="font-bold text-blue-600 dark:text-blue-400">Startup</span> <span>priya@neuralcart.test</span></div>
                    <div class="flex justify-between items-center bg-white/50 dark:bg-gray-800/50 px-3 py-2 rounded-lg"><span class="font-bold text-purple-600 dark:text-purple-400">Corporate</span> <span>rajesh@tatadigital.test</span></div>
                </div>
                <div class="mt-3 text-center text-[10px] text-gray-500 font-bold uppercase tracking-widest bg-gray-100 dark:bg-gray-900/80 py-1.5 rounded-lg border border-gray-200 dark:border-gray-800">Password for all: <code class="text-primary-600 dark:text-primary-400 bg-transparent">password</code></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/auth/login.blade.php ENDPATH**/ ?>