<?php $__env->startSection('title', 'Edit Innovation Challenge'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-8 relative z-10 animate-fade-in">
    <?php echo $__env->make('components.back-button', ['fallback' => route('corporate.challenges.index'), 'label' => 'Back to Challenges'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-xs font-bold uppercase tracking-widest text-purple-600 dark:text-purple-400 mb-4 border border-purple-200 dark:border-purple-800/50">
            Edit Mode
        </div>
        <h1 class="text-3xl font-black mb-2 text-gray-900 dark:text-white font-outfit">
            Modify <span class="text-gradient from-purple-500 to-pink-500">Challenge Details</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Update the problem statement, parameters, and tag identifiers for your challenge.</p>
    </div>

    <form method="POST" action="<?php echo e(route('corporate.challenges.update', $challenge)); ?>" enctype="multipart/form-data" data-warn-unsaved class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="glass-card-strong rounded-3xl p-8 border-glow space-y-6">
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Challenge Title</label>
                <input type="text" name="title" required value="<?php echo e(old('title', $challenge->title)); ?>" placeholder="e.g. Next-Gen Generative AI Recommendation System" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Industry Sector</label>
                    <select name="industry_id" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                        <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($i->id); ?>" <?php echo e(old('industry_id', $challenge->industry_id) == $i->id ? 'selected' : ''); ?>><?php echo e($i->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['industry_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Deadline</label>
                    <input type="date" name="deadline" required value="<?php echo e(old('deadline', $challenge->deadline ? $challenge->deadline->format('Y-m-d') : '')); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Status</label>
                    <select name="status" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                        <option value="open" <?php echo e(old('status', $challenge->status) == 'open' ? 'selected' : ''); ?>>Open</option>
                        <option value="reviewing" <?php echo e(old('status', $challenge->status) == 'reviewing' ? 'selected' : ''); ?>>Reviewing</option>
                        <option value="closed" <?php echo e(old('status', $challenge->status) == 'closed' ? 'selected' : ''); ?>>Closed</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Problem Statement & Description</label>
                <textarea name="description" rows="6" required placeholder="Describe the corporate challenge in detail, including the exact core issues and goals..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200"><?php echo e(old('description', $challenge->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Technical & Submission Requirements</label>
                <textarea name="requirements" rows="4" placeholder="Detail any mandatory stack requirements, certifications, timeline rules, or team sizes..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200"><?php echo e(old('requirements', $challenge->requirements)); ?></textarea>
                <?php $__errorArgs = ['requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Min Budget (₹)</label>
                    <input type="number" name="budget_min" min="0" required value="<?php echo e(old('budget_min', $challenge->budget_min)); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    <?php $__errorArgs = ['budget_min'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Max Budget (₹)</label>
                    <input type="number" name="budget_max" min="0" required value="<?php echo e(old('budget_max', $challenge->budget_max)); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                    <?php $__errorArgs = ['budget_max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Required Skills / Technology Tags <span class="text-xs text-gray-400 font-normal">(comma-separated)</span></label>
                <input type="text" name="required_tags" value="<?php echo e(old('required_tags', is_array($challenge->required_tags) ? implode(', ', $challenge->required_tags) : '')); ?>" placeholder="e.g. AI, Python, Docker, PyTorch" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/60 focus:border-purple-500 dark:focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500 outline-none text-gray-900 dark:text-white font-medium transition duration-200">
                <?php $__errorArgs = ['required_tags'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300 font-outfit">Reference Document / Brief <span class="text-xs text-gray-400 font-normal">(Optional, max 20MB)</span></label>
                
                <?php if($challenge->attachment_path): ?>
                    <div class="mb-3 p-3 bg-purple-500/10 border border-purple-500/30 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm font-bold text-gray-750 dark:text-gray-250"><?php echo e($challenge->attachment_filename); ?></span>
                        </div>
                        <span class="text-xs text-purple-600 dark:text-purple-400 font-bold uppercase tracking-wider">Current File</span>
                    </div>
                <?php endif; ?>

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 dark:border-zinc-800 border-dashed rounded-xl hover:border-purple-400 dark:hover:border-purple-500/50 transition duration-200 bg-slate-50/50 dark:bg-zinc-900/20">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-zinc-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex text-sm text-slate-600 dark:text-zinc-400 justify-center">
                            <label for="attachment" class="relative cursor-pointer rounded-md font-bold text-purple-600 dark:text-purple-400 hover:text-purple-500 focus-within:outline-none">
                                <span>Upload a new file</span>
                                <input id="attachment" name="attachment" type="file" class="sr-only" accept=".ppt,.pptx,.pdf,.doc,.docx,image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">PPT, PDF, Word, or Image up to 20MB</p>
                        <div id="file-chosen-wrapper" class="hidden mt-3 p-2 px-3 bg-purple-50 dark:bg-purple-950/30 rounded-lg border border-purple-200/50 dark:border-purple-900/50 inline-flex items-center gap-2">
                            <span class="text-xs font-bold text-purple-700 dark:text-purple-300" id="file-chosen-name">No file chosen</span>
                            <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700 font-bold text-xs p-1">×</button>
                        </div>
                    </div>
                </div>
                <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1 font-bold"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <button type="submit" class="w-full shimmer-btn bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-purple-600/25 transition-all duration-300 hover:scale-[1.01]">
            Save and Update Challenge
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('attachment');
        const nameSpan = document.getElementById('file-chosen-name');
        const wrapperDiv = document.getElementById('file-chosen-wrapper');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    nameSpan.textContent = this.files[0].name;
                    wrapperDiv.classList.remove('hidden');
                } else {
                    wrapperDiv.classList.add('hidden');
                }
            });
        }
    });

    function clearFile() {
        const fileInput = document.getElementById('attachment');
        const wrapperDiv = document.getElementById('file-chosen-wrapper');
        const nameSpan = document.getElementById('file-chosen-name');
        if (fileInput) fileInput.value = '';
        if (wrapperDiv) wrapperDiv.classList.add('hidden');
        if (nameSpan) nameSpan.textContent = '';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\corporate\challenges\edit.blade.php ENDPATH**/ ?>