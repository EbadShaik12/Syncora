<?php $__env->startSection('title', 'Kanban Board - ' . $challenge->title); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 animate-fade-in" 
     x-data="{
        applications: <?php echo \Illuminate\Support\Js::from($applications->map(function($app) {
            return [
                'id' => $app->id,
                'startup_name' => $app->startup->companyName(),
                'startup_logo' => $app->startup->logoUrl(),
                'elevator_pitch' => $app->startup->startupProfile ? \Illuminate\Support\Str::limit($app->startup->startupProfile->elevator_pitch, 110) : '',
                'cover_letter' => \Illuminate\Support\Str::limit($app->cover_letter, 110),
                'status' => $app->status,
                'created_diff' => $app->created_at->diffForHumans(),
                'profile_url' => route('profile.show', $app->startup),
                'stage_url' => route('corporate.applications.stage', $app),
            ];
        }))->toHtml() ?>,
        isLoading: false,
        async updateStage(app, newStatus) {
            this.isLoading = true;
            const originalStatus = app.status;
            app.status = newStatus;

            try {
                const response = await fetch(app.stage_url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error('Failed to update stage');
                }

                if (newStatus === 'shortlisted') {
                    confetti({
                        particleCount: 150,
                        spread: 80,
                        origin: { y: 0.6 }
                    });
                }
            } catch (err) {
                console.error(err);
                app.status = originalStatus;
                alert('An error occurred while updating the application status. Please try again.');
            } finally {
                this.isLoading = false;
            }
        }
     }">

    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 gap-6">
        <div class="max-w-3xl">
            <?php echo $__env->make('components.back-button', ['fallback' => route('corporate.challenges.index'), 'label' => 'Back to Challenges'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <div class="flex items-center gap-2 mb-3 flex-wrap">
                <span class="text-xs font-bold uppercase tracking-wider px-3.5 py-1 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/50">
                    💼 <?php echo e($challenge->industry?->name); ?>

                </span>
                <span class="text-xs font-semibold px-3 py-1 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                    Budget Pool: ₹<?php echo e(number_format($challenge->budget_min)); ?> – ₹<?php echo e(number_format($challenge->budget_max)); ?>

                </span>
            </div>

            <h1 class="text-3xl font-black text-gray-900 dark:text-white font-outfit mb-2">
                Pipeline: <span class="text-gradient from-purple-500 to-pink-500"><?php echo e($challenge->title); ?></span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Manage incoming proposals, track review cycles, and shortlist the most promising solutions dynamically.</p>
        </div>

        <div class="flex items-center gap-4 bg-white dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800/80 rounded-2xl px-6 py-4 shadow-md backdrop-blur-md">
            <div class="text-center px-4 border-r border-gray-100 dark:border-gray-800/50">
                <span class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Total Received</span>
                <span class="text-2xl font-black text-purple-600 dark:text-purple-400" x-text="applications.length"></span>
            </div>
            <div class="text-center px-4">
                <span class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Shortlisted</span>
                <span class="text-2xl font-black text-green-500" x-text="applications.filter(a => a.status === 'shortlisted').length"></span>
            </div>
        </div>
    </div>

    
    <div x-show="isLoading" x-transition class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center pointer-events-none">
        <div class="bg-white/80 dark:bg-gray-900/90 border border-purple-500/30 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
            <div class="w-6 h-6 border-4 border-purple-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="font-bold text-sm text-gray-900 dark:text-white">Syncing Status...</span>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        
        <div class="glass-card-strong rounded-3xl p-6 border-glow flex flex-col min-h-[500px]">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-800/50">
                <div class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full bg-yellow-400 animate-pulse shadow-md shadow-yellow-400/30"></span>
                    <h3 class="font-black text-lg text-gray-900 dark:text-white font-outfit">Pending Review</h3>
                </div>
                <span class="text-xs font-black px-2.5 py-1 rounded-lg bg-yellow-50 dark:bg-yellow-950/40 text-yellow-600 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-900/30" 
                      x-text="applications.filter(a => a.status === 'pending').length"></span>
            </div>
            
            <div class="space-y-4 flex-grow overflow-y-auto max-h-[600px] pr-2">
                <template x-for="app in applications.filter(a => a.status === 'pending')" :key="app.id">
                    <div class="glass-card rounded-2xl p-5 border border-gray-100 dark:border-gray-800/80 shadow-md hover:scale-[1.01] transition-all duration-300 relative group flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <img :src="app.startup_logo" class="w-12 h-12 rounded-xl object-cover bg-gray-100 dark:bg-gray-800 shadow-sm">
                            <div class="min-w-0">
                                <h4 class="font-bold text-gray-900 dark:text-white truncate font-outfit text-sm" x-text="app.startup_name"></h4>
                                <span class="text-[10px] font-semibold text-gray-400" x-text="app.created_diff"></span>
                            </div>
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium space-y-2">
                            <p x-show="app.elevator_pitch" class="italic" x-text="'&ldquo;' + app.elevator_pitch + '&rdquo;'"></p>
                            <p class="border-t border-gray-100 dark:border-gray-800/30 pt-2" x-text="app.cover_letter"></p>
                        </div>

                        <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100 dark:border-gray-800/50">
                            <a :href="app.profile_url" class="text-[11px] font-bold text-purple-500 hover:underline">
                                View Profile →
                            </a>
                            
                            <div class="flex items-center gap-1.5">
                                <button @click="updateStage(app, 'shortlisted')" 
                                        class="px-3 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-[10px] font-extrabold shadow-sm shadow-green-500/20 transition flex items-center gap-1">
                                    🚀 Shortlist
                                </button>
                                <button @click="updateStage(app, 'rejected')" 
                                        class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-950/20 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/30 text-[10px] font-extrabold transition">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                
                <div x-show="applications.filter(a => a.status === 'pending').length === 0" 
                     class="text-center py-16 text-gray-500 font-medium text-sm border-2 border-dashed border-gray-100 dark:border-gray-800/40 rounded-2xl">
                    🎉 No pending reviews!
                </div>
            </div>
        </div>

        
        <div class="glass-card-strong rounded-3xl p-6 border-glow flex flex-col min-h-[500px]">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-800/50">
                <div class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full bg-green-500 shadow-md shadow-green-500/30"></span>
                    <h3 class="font-black text-lg text-gray-900 dark:text-white font-outfit">Shortlisted</h3>
                </div>
                <span class="text-xs font-black px-2.5 py-1 rounded-lg bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30" 
                      x-text="applications.filter(a => a.status === 'shortlisted').length"></span>
            </div>
            
            <div class="space-y-4 flex-grow overflow-y-auto max-h-[600px] pr-2">
                <template x-for="app in applications.filter(a => a.status === 'shortlisted')" :key="app.id">
                    <div class="glass-card rounded-2xl p-5 border border-green-500/30 dark:border-green-500/20 shadow-md hover:scale-[1.01] transition-all duration-300 relative group flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <img :src="app.startup_logo" class="w-12 h-12 rounded-xl object-cover bg-gray-100 dark:bg-gray-800 shadow-sm">
                            <div class="min-w-0">
                                <h4 class="font-bold text-gray-900 dark:text-white truncate font-outfit text-sm" x-text="app.startup_name"></h4>
                                <span class="text-[10px] font-semibold text-gray-400" x-text="app.created_diff"></span>
                            </div>
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium space-y-2">
                            <p x-show="app.elevator_pitch" class="italic" x-text="'&ldquo;' + app.elevator_pitch + '&rdquo;'"></p>
                            <p class="border-t border-gray-100 dark:border-gray-800/30 pt-2" x-text="app.cover_letter"></p>
                        </div>

                        <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100 dark:border-gray-800/50">
                            <a :href="app.profile_url" class="text-[11px] font-bold text-purple-500 hover:underline">
                                View Profile →
                            </a>
                            
                            <div class="flex items-center gap-1.5">
                                <button @click="updateStage(app, 'pending')" 
                                        class="px-2.5 py-1.5 rounded-lg bg-yellow-50 dark:bg-yellow-950/20 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-100 transition text-[10px] font-extrabold flex items-center gap-1">
                                    ⏪ Undo
                                </button>
                                <button @click="updateStage(app, 'rejected')" 
                                        class="px-2.5 py-1.5 rounded-lg bg-red-100 dark:bg-red-950/20 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/30 text-[10px] font-extrabold transition">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                
                <div x-show="applications.filter(a => a.status === 'shortlisted').length === 0" 
                     class="text-center py-16 text-gray-500 font-medium text-sm border-2 border-dashed border-gray-100 dark:border-gray-800/40 rounded-2xl">
                    No shortlisted startups yet
                </div>
            </div>
        </div>

        
        <div class="glass-card-strong rounded-3xl p-6 border-glow flex flex-col min-h-[500px]">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-800/50">
                <div class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full bg-red-500 shadow-md shadow-red-500/30"></span>
                    <h3 class="font-black text-lg text-gray-900 dark:text-white font-outfit">Declined</h3>
                </div>
                <span class="text-xs font-black px-2.5 py-1 rounded-lg bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30" 
                      x-text="applications.filter(a => a.status === 'rejected').length"></span>
            </div>
            
            <div class="space-y-4 flex-grow overflow-y-auto max-h-[600px] pr-2">
                <template x-for="app in applications.filter(a => a.status === 'rejected')" :key="app.id">
                    <div class="glass-card rounded-2xl p-5 border border-gray-100 dark:border-gray-800/80 shadow-md hover:scale-[1.01] transition-all duration-300 relative group flex flex-col gap-4 opacity-75">
                        <div class="flex items-center gap-3">
                            <img :src="app.startup_logo" class="w-12 h-12 rounded-xl object-cover bg-gray-100 dark:bg-gray-800 shadow-sm grayscale">
                            <div class="min-w-0">
                                <h4 class="font-bold text-gray-900 dark:text-white truncate font-outfit text-sm" x-text="app.startup_name"></h4>
                                <span class="text-[10px] font-semibold text-gray-400" x-text="app.created_diff"></span>
                            </div>
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium space-y-2">
                            <p x-show="app.elevator_pitch" class="italic" x-text="'&ldquo;' + app.elevator_pitch + '&rdquo;'"></p>
                            <p class="border-t border-gray-100 dark:border-gray-800/30 pt-2" x-text="app.cover_letter"></p>
                        </div>

                        <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100 dark:border-gray-800/50">
                            <a :href="app.profile_url" class="text-[11px] font-bold text-purple-500 hover:underline">
                                View Profile →
                            </a>
                            
                            <div class="flex items-center gap-1.5">
                                <button @click="updateStage(app, 'pending')" 
                                        class="px-2.5 py-1.5 rounded-lg bg-yellow-50 dark:bg-yellow-950/20 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-100 transition text-[10px] font-extrabold">
                                    Restore
                                </button>
                                <button @click="updateStage(app, 'shortlisted')" 
                                        class="px-2.5 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-[10px] font-extrabold shadow-sm shadow-green-500/20 transition flex items-center gap-1">
                                    🚀 Shortlist
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                
                <div x-show="applications.filter(a => a.status === 'rejected').length === 0" 
                     class="text-center py-16 text-gray-500 font-medium text-sm border-2 border-dashed border-gray-100 dark:border-gray-800/40 rounded-2xl">
                    No declined applications
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\corporate\challenges\kanban.blade.php ENDPATH**/ ?>