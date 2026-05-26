<?php $__env->startSection('title', 'Edit Profile'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <?php echo $__env->make('components.back-button', ['fallback' => route('startup.dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <h1 class="text-3xl font-bold mb-6">Your Startup Profile</h1>

    <form method="POST" action="<?php echo e(route('startup.profile.update')); ?>" enctype="multipart/form-data" data-warn-unsaved class="space-y-6">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <!-- Basic info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Basic Info</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Logo</label>
                    <div class="flex items-center gap-4">
                        <img src="<?php echo e($profile->logo ? asset('storage/'.$profile->logo) : auth()->user()->logoUrl()); ?>" class="w-20 h-20 rounded-2xl object-cover bg-gray-105 border border-slate-250 dark:border-zinc-700 shadow-inner">
                        <div class="flex flex-col gap-1">
                            <input type="file" name="logo" accept="image/*" class="block text-sm text-slate-500 dark:text-zinc-400">
                            <p class="text-[10px] text-gray-450 dark:text-zinc-500 font-bold mt-1">Recommended: Square PNG/JPEG up to 10MB</p>
                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs font-bold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Profile Cover Banner</label>
                    <div class="flex flex-col gap-3">
                        <?php if($profile->banner): ?>
                            <div class="relative w-full h-32 rounded-2xl overflow-hidden border border-slate-200 dark:border-zinc-700 shadow-inner">
                                <img src="<?php echo e(asset('storage/'.$profile->banner)); ?>" class="w-full h-full object-cover">
                            </div>
                        <?php else: ?>
                            <div class="w-full h-32 rounded-2xl bg-gradient-to-r from-primary-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold shadow-inner opacity-80">
                                Default Cover Gradient Active
                            </div>
                        <?php endif; ?>
                        <div class="flex flex-col gap-1">
                            <input type="file" name="banner" accept="image/*" class="block text-sm text-slate-500 dark:text-zinc-400">
                            <p class="text-[10px] text-gray-455 dark:text-zinc-500 font-bold mt-1">Recommended: Landscape banner up to 15MB</p>
                            <?php $__errorArgs = ['banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs font-bold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Company Name</label>
                    <input type="text" name="company_name" value="<?php echo e(old('company_name', $profile->company_name)); ?>" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Industry</label>
                    <select name="industry_id" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                        <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($i->id); ?>" <?php if(old('industry_id', $profile->industry_id)==$i->id): echo 'selected'; endif; ?>><?php echo e($i->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Stage</label>
                    <select name="stage" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                        <?php $__currentLoopData = ['idea'=>'Idea','mvp'=>'MVP','growth'=>'Growth','scale'=>'Scale']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($val); ?>" <?php if(old('stage',$profile->stage)==$val): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Team Size</label>
                    <input type="number" name="team_size" min="1" value="<?php echo e(old('team_size', $profile->team_size)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Founded Year</label>
                    <input type="number" name="founded_year" min="1900" max="<?php echo e(date('Y')); ?>" value="<?php echo e(old('founded_year', $profile->founded_year)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Funding Status</label>
                    <select name="funding_status" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                        <?php $__currentLoopData = ['bootstrapped'=>'Bootstrapped','pre_seed'=>'Pre-seed','seed'=>'Seed','series_a'=>'Series A','series_b_plus'=>'Series B+']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($val); ?>" <?php if(old('funding_status',$profile->funding_status)==$val): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Funding Amount (₹)</label>
                    <input type="number" name="funding_amount" min="0" value="<?php echo e(old('funding_amount', $profile->funding_amount)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Elevator Pitch</label>
                    <textarea name="elevator_pitch" rows="4" required class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"><?php echo e(old('elevator_pitch', $profile->elevator_pitch)); ?></textarea>
                    <?php $__errorArgs = ['elevator_pitch'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Technology Tags <span class="text-xs text-gray-400">(comma-separated)</span></label>
                    <input type="text" name="tech_tags" value="<?php echo e(old('tech_tags', is_array($profile->tech_tags) ? implode(', ', $profile->tech_tags) : '')); ?>" placeholder="e.g. Machine Learning, Python, AWS" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Mission, Vision & Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Mission, Vision &amp; Growth Details</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Our Mission <span class="text-xs text-gray-400">(Shows on profile timeline)</span></label>
                    <textarea name="mission" rows="2" placeholder="e.g. To revolutionize global e-commerce by making deep-learning recommendations accessible." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"><?php echo e(old('mission', $profile->mission)); ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Our Vision <span class="text-xs text-gray-400">(Shows on profile timeline)</span></label>
                    <textarea name="vision" rows="2" placeholder="e.g. To power the default predictive interface for digital commerce platforms worldwide." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"><?php echo e(old('vision', $profile->vision)); ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Annual Revenue (ARR) / Financial Scale <span class="text-xs text-gray-400">(Shows as current year metric in timeline)</span></label>
                    <input type="text" name="annual_revenue" value="<?php echo e(old('annual_revenue', $profile->annual_revenue)); ?>" placeholder="e.g. ARR ₹1.2 Cr or ARR ₹45L" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Seeking -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">What You're Looking For</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <?php $__currentLoopData = ['investment'=>'💰 Investment','pilot'=>'🚀 Pilot','mentorship'=>'🎓 Mentorship','acquisition'=>'🤝 Acquisition']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                    <input type="checkbox" name="seeking[]" value="<?php echo e($val); ?>" <?php echo e(in_array($val, (array)($profile->seeking ?? [])) ? 'checked' : ''); ?> class="rounded text-primary-600">
                    <span class="text-sm"><?php echo e($label); ?></span>
                </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Budget Min (₹)</label>
                    <input type="number" name="budget_min" min="0" value="<?php echo e(old('budget_min', $profile->budget_min)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Budget Max (₹)</label>
                    <input type="number" name="budget_max" min="0" value="<?php echo e(old('budget_max', $profile->budget_max)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Location & links -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Location & Links</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium mb-2">City</label><input type="text" name="city" value="<?php echo e(old('city', $profile->city)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
                <div><label class="block text-sm font-medium mb-2">State</label><input type="text" name="state" value="<?php echo e(old('state', $profile->state)); ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
                <div><label class="block text-sm font-medium mb-2">Website</label><input type="url" name="website" value="<?php echo e(old('website', $profile->website)); ?>" placeholder="https://" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
                <div><label class="block text-sm font-medium mb-2">LinkedIn</label><input type="url" name="linkedin" value="<?php echo e(old('linkedin', $profile->linkedin)); ?>" placeholder="https://linkedin.com/..." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none"></div>
            </div>
        </div>

        <!-- Milestones -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="font-bold text-lg mb-5">Your Journey (Milestones)</h2>
            <?php if($milestones->count()): ?>
            <div class="space-y-2 mb-5">
                <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <div class="text-2xl"><?php echo e($m->icon ?? '🚀'); ?></div>
                    <div class="flex-1">
                        <p class="font-medium"><?php echo e($m->title); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($m->milestone_date->format('M Y')); ?> — <?php echo e($m->description); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
            <p class="text-xs text-gray-500 mb-3">Add a milestone after saving the profile (uses /milestones endpoint via separate form).</p>
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-primary-600/30 transition hover:scale-[1.01]">Save Profile</button>
    </form>

    <!-- Add milestone separate form -->
    <form method="POST" action="<?php echo e(route('startup.milestones.store')); ?>" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 mt-6">
        <?php echo csrf_field(); ?>
        <h2 class="font-bold text-lg mb-4">Add a Milestone</h2>
        <div class="grid md:grid-cols-4 gap-3">
            <input type="text" name="title" required placeholder="Title (e.g. Launched MVP)" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none md:col-span-2">
            <input type="date" name="milestone_date" required class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
            <input type="text" name="icon" placeholder="🚀" maxlength="4" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
            <textarea name="description" rows="2" placeholder="Brief description (optional)" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none md:col-span-3"></textarea>
            <button class="bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium">Add</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\startup\profile-edit.blade.php ENDPATH**/ ?>