<?php $__env->startSection('title', 'Register as Corporate'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Floating labels */
    .input-group { position: relative; margin-bottom: 1.25rem; }
    .input-group input, .input-group select, .input-group textarea { 
        padding: 1.5rem 1rem 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.4);
    }
    .dark .input-group input, .dark .input-group select, .dark .input-group textarea { background: rgba(0, 0, 0, 0.2); }
    .input-group label {
        position: absolute; left: 1rem; top: 1.2rem;
        transition: all 0.2s ease; pointer-events: none;
        color: #6b7280; font-size: 0.875rem; font-weight: 500;
    }
    .dark .input-group label { color: #9ca3af; }
    .input-group input:focus ~ label, .input-group input:not(:placeholder-shown) ~ label,
    .input-group select:focus ~ label, .input-group select:not(:placeholder-shown) ~ label,
    .input-group textarea:focus ~ label, .input-group textarea:not(:placeholder-shown) ~ label {
        top: 0.35rem; font-size: 0.65rem; color: #a855f7; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;
    }
    .dark .input-group input:focus ~ label, .dark .input-group input:not(:placeholder-shown) ~ label,
    .dark .input-group select:focus ~ label, .dark .input-group select:not(:placeholder-shown) ~ label,
    .dark .input-group textarea:focus ~ label, .dark .input-group textarea:not(:placeholder-shown) ~ label { color: #c084fc; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden bg-transparent">
    
    
    <div class="absolute inset-0 z-0">
        <div class="absolute top-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-[100px] blob"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-pink-500/20 rounded-full blur-[100px] blob" style="animation-delay:-5s"></div>
        <div class="absolute inset-0 noise opacity-20"></div>
    </div>

    <div class="w-full max-w-4xl relative z-10 stagger-container grid lg:grid-cols-5 gap-8 items-start mt-8">
        
        
        <div class="lg:col-span-2 lg:sticky lg:top-24 text-center lg:text-left reveal">
            <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-purple-600 transition mb-8 magnetic">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to roles
            </a>
            
            <div class="inline-flex mb-6">
                <div class="bg-white rounded-2xl p-4 shadow-xl border border-slate-200/40 dark:border-white/10 flex items-center justify-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden relative bg-white flex items-center justify-center shadow-sm border border-slate-100 flex-shrink-0">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" class="w-full h-full object-cover scale-[1.7] -translate-y-[15%] flex-shrink-0" alt="Syncora Icon">
                    </div>
                    <img src="<?php echo e(asset('images/logo-text.png')); ?>" class="h-8 w-auto object-contain dark:invert transition-all duration-300" alt="Syncora Text">
                </div>
            </div>
            
            <h2 class="text-4xl font-black mb-4 font-outfit">Corporate <span class="text-gradient from-purple-400 to-pink-600">Application</span></h2>
            <p class="text-gray-600 dark:text-gray-300 mb-8 font-medium">Access our curated network of highly vetted startups to accelerate your innovation roadmap.</p>
            
            <div class="hidden lg:block space-y-4 text-sm">
                <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                    <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold">1</div>
                    <span class="font-medium">Account Contact</span>
                </div>
                <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                    <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold">2</div>
                    <span class="font-medium">Company Profile</span>
                </div>
                <div class="flex items-center gap-3 text-gray-400 dark:text-gray-600">
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-600 font-bold">3</div>
                    <span>Complete Profile Later</span>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-3 reveal reveal-delay-2 glass-card-strong rounded-3xl p-8 shadow-2xl border-glow">
            <form method="POST" action="<?php echo e(route('register.corporate')); ?>">
                <?php echo csrf_field(); ?>
                
                
                <div class="mb-8 relative">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-6 h-6 rounded bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 font-bold text-xs">1</div>
                        <h3 class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-sm">Contact Details</h3>
                        <div class="flex-1 h-px bg-gradient-to-r from-gray-200 dark:from-gray-700 to-transparent ml-2"></div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="input-group">
                            <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="name">Your Full Name</label>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1 absolute -bottom-5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="input-group">
                            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="email">Work Email</label>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1 absolute -bottom-5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4 mt-2">
                        <div class="input-group">
                            <input type="password" name="password" id="password" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="password">Password</label>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1 absolute -bottom-5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="password_confirmation">Confirm Password</label>
                        </div>
                    </div>
                </div>

                
                <div class="relative">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-6 h-6 rounded bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 font-bold text-xs">2</div>
                        <h3 class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-sm">Company Profile</h3>
                        <div class="flex-1 h-px bg-gradient-to-r from-gray-200 dark:from-gray-700 to-transparent ml-2"></div>
                    </div>

                    <div class="input-group">
                        <input type="text" name="company_name" id="company_name" value="<?php echo e(old('company_name')); ?>" required placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white">
                        <label for="company_name">Company Name</label>
                        <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1 absolute -bottom-5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4 mt-2">
                        <div class="input-group">
                            <select name="industry_id" id="industry_id" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white appearance-none">
                                <option value=""></option>
                                <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($i->id); ?>" <?php echo e(old('industry_id') == $i->id ? 'selected' : ''); ?>><?php echo e($i->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="industry_id">Industry</label>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <?php $__errorArgs = ['industry_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1 absolute -bottom-5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="input-group">
                            <select name="company_size" id="company_size" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white appearance-none">
                                <option value="small" <?php echo e(old('company_size') == 'small' ? 'selected' : ''); ?>>Small (1-50)</option>
                                <option value="medium" <?php echo e(old('company_size', 'medium') == 'medium' ? 'selected' : ''); ?>>Medium (51-500)</option>
                                <option value="large" <?php echo e(old('company_size') == 'large' ? 'selected' : ''); ?>>Large (501-5000)</option>
                                <option value="enterprise" <?php echo e(old('company_size') == 'enterprise' ? 'selected' : ''); ?>>Enterprise (5000+)</option>
                            </select>
                            <label for="company_size">Company Size</label>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group mt-2">
                        <textarea name="problem_statement" id="problem_statement" rows="3" required minlength="20" maxlength="1000" placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition resize-none text-gray-900 dark:text-white"><?php echo e(old('problem_statement')); ?></textarea>
                        <label for="problem_statement">What are you looking for? (Problem Statement)</label>
                        <?php $__errorArgs = ['problem_statement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1 absolute -bottom-5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="input-group mt-2">
                        <input type="text" name="city" id="city" value="<?php echo e(old('city')); ?>" placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 outline-none transition text-gray-900 dark:text-white">
                        <label for="city">City / Location (Optional)</label>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="submit" class="shimmer-btn w-full bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-bold py-4 rounded-xl shadow-xl shadow-purple-500/30 transition hover:scale-[1.02] flex items-center justify-center gap-2">
                        Create Corporate Account
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    
                    <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                        By registering, you agree to our <a href="#" class="text-purple-600 hover:underline">Terms</a> and <a href="#" class="text-purple-600 hover:underline">Privacy Policy</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\auth\register-corporate.blade.php ENDPATH**/ ?>