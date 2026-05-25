<?php $__env->startSection('title', 'Join StartupConnect'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">
    
    
    <div class="absolute top-20 left-10 w-96 h-96 bg-primary-500/20 rounded-full blur-[100px] blob"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-[100px] blob" style="animation-delay:-5s"></div>

    <div class="w-full max-w-4xl relative z-10 stagger-container">
        
        
        <div class="reveal text-center mb-16">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center justify-center mb-8 magnetic">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center shadow-xl shadow-primary-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </a>
            <h2 class="text-4xl sm:text-5xl font-black mb-4 font-outfit">Join <span class="text-gradient">StartupConnect</span></h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Choose your role to start building the future</p>
        </div>

        
        <div class="grid md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            
            
            <a href="<?php echo e(route('register.startup')); ?>" class="reveal reveal-delay-2 group tilt-card glass-card-strong rounded-[2.5rem] p-8 border-glow hover:border-blue-500/50 transition-colors duration-300 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-500/5 group-hover:from-blue-500/5 group-hover:to-blue-500/10 transition-colors"></div>
                
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center mb-8 shadow-xl shadow-blue-500/30 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                
                <h3 class="text-3xl font-black mb-3 font-outfit">I'm a Startup</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 font-medium leading-relaxed">Showcase your innovation, find pilot programs, and connect with corporate investors.</p>
                
                <ul class="space-y-4 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-10">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">✓</div>
                        AI-matched corporate connections
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">✓</div>
                        Apply to innovation challenges
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">✓</div>
                        Showcase your tech stack
                    </li>
                </ul>
                
                <div class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold uppercase tracking-wider text-sm mt-auto group-hover:gap-4 transition-all">
                    Register as Startup
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            
            <a href="<?php echo e(route('register.corporate')); ?>" class="reveal reveal-delay-3 group tilt-card glass-card-strong rounded-[2.5rem] p-8 border-glow hover:border-purple-500/50 transition-colors duration-300 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-purple-500/5 group-hover:from-purple-500/5 group-hover:to-purple-500/10 transition-colors"></div>
                
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center mb-8 shadow-xl shadow-purple-500/30 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                
                <h3 class="text-3xl font-black mb-3 font-outfit">I'm a Corporate</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 font-medium leading-relaxed">Discover innovative startups, post R&D challenges, and accelerate transformation.</p>
                
                <ul class="space-y-4 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-10">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">✓</div>
                        Access curated startup database
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">✓</div>
                        Post innovation challenges
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">✓</div>
                        Filter by stage and tech
                    </li>
                </ul>
                
                <div class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 font-bold uppercase tracking-wider text-sm mt-auto group-hover:gap-4 transition-all">
                    Register as Corporate
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>
        </div>

        
        <div class="reveal reveal-delay-4 text-center mt-12 font-medium text-gray-500 dark:text-gray-400">
            Already have an account? 
            <a href="<?php echo e(route('login')); ?>" class="text-primary-600 font-bold hover:underline ml-1">Sign in instead</a>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/auth/register.blade.php ENDPATH**/ ?>