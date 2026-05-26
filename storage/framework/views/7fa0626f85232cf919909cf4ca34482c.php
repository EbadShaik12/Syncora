<?php $__env->startSection('title', 'Innovation Challenges'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ lightboxOpen: false, lightboxImage: '', lightboxTitle: '' }">
    <?php echo $__env->make('components.back-button', ['fallback' => route('startup.dashboard'), 'label' => 'Back to Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Innovation Challenges</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Apply to challenges posted by corporates and win pilot opportunities.</p>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 mb-6 grid md:grid-cols-12 gap-3">
        <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search challenges..." class="md:col-span-7 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
        <select name="industry_id" class="md:col-span-4 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
            <option value="">All industries</option>
            <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($i->id); ?>" <?php if(request('industry_id')==$i->id): echo 'selected'; endif; ?>><?php echo e($i->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="md:col-span-1 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium">Filter</button>
    </form>

    <?php if($challenges->isEmpty()): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center">
            <div class="text-5xl mb-3">📋</div>
            <p class="text-gray-500">No open challenges right now. Check back soon!</p>
        </div>
    <?php else: ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php 
            $applied = in_array($challenge->id, $appliedIds);
            
            $isAttachment = !empty($challenge->attachment_path);
            $extension = $isAttachment ? strtolower(pathinfo($challenge->attachment_path, PATHINFO_EXTENSION)) : '';
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
            $isPdf = $extension === 'pdf';
            $isPpt = in_array($extension, ['ppt', 'pptx']);
            $isWord = in_array($extension, ['doc', 'docx']);
        ?>
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-slate-100 dark:border-zinc-800 p-5 hover:shadow-xl transition flex flex-col relative overflow-hidden group">
            
            
            <div class="flex items-center gap-3 mb-4">
                <img src="<?php echo e($challenge->corporate->logoUrl()); ?>" class="w-11 h-11 rounded-full object-cover shadow-sm border border-slate-200 dark:border-zinc-700">
                <div class="flex-grow min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="font-extrabold text-sm text-slate-800 dark:text-white truncate"><?php echo e($challenge->corporate->companyName()); ?></span>
                        <span class="text-[9px] text-gray-400 dark:text-zinc-500 font-black uppercase tracking-wider bg-slate-100 dark:bg-zinc-900 px-2 py-0.5 rounded border border-slate-200/50 dark:border-zinc-850">CORPORATE</span>
                    </div>
                    <p class="text-[10px] text-gray-500 dark:text-zinc-400 font-semibold flex items-center gap-1 mt-0.5">
                        <span>Posted a challenge</span>
                        <span class="text-gray-400 dark:text-zinc-600">•</span>
                        <span><?php echo e($challenge->created_at ? $challenge->created_at->diffForHumans() : 'Recently'); ?></span>
                        <span class="text-gray-400 dark:text-zinc-600">•</span>
                        <span>🌎</span>
                    </p>
                </div>
            </div>

            
            <div class="mb-3">
                <h3 class="text-base font-extrabold font-outfit text-slate-900 dark:text-white leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                    <?php echo e($challenge->title); ?>

                </h3>
                <?php if($challenge->industry): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 border border-primary-100/50 dark:border-primary-900/30 mt-2">
                        💼 <?php echo e($challenge->industry->name); ?>

                    </span>
                <?php endif; ?>
            </div>

            
            <p class="text-xs text-slate-500 dark:text-zinc-350 leading-relaxed font-medium line-clamp-3 mb-4"><?php echo e($challenge->description); ?></p>

            
            <?php if($isAttachment): ?>
                <div class="mb-4">
                    <?php if($isImage): ?>
                        <div class="border border-slate-100 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-inner bg-slate-50 dark:bg-zinc-900/40 relative group cursor-pointer"
                             @click="lightboxOpen = true; lightboxImage = '<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>'; lightboxTitle = '<?php echo e($challenge->title); ?>'">
                            <img src="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>" class="w-full h-44 object-cover group-hover:scale-[1.01] transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300 text-white font-bold text-xs gap-1.5 backdrop-blur-[2px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                Click to View Image
                            </div>
                        </div>
                    <?php elseif($isPdf): ?>
                        <div class="border border-slate-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm bg-slate-50 dark:bg-zinc-900/40">
                            <div class="bg-slate-100 dark:bg-zinc-850 px-3.5 py-2 flex items-center justify-between border-b border-slate-200 dark:border-zinc-850">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="text-[10px] font-black text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20">PDF</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 truncate"><?php echo e($challenge->attachment_filename); ?></span>
                                </div>
                                <a href="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>" target="_blank" class="px-2.5 py-1 bg-white hover:bg-slate-50 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-800 dark:text-white rounded-lg border border-slate-200/80 dark:border-zinc-700 text-[10px] font-bold flex items-center gap-1 shadow-sm transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Open
                                </a>
                            </div>
                            <iframe src="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>#toolbar=0" class="w-full h-48 border-0 bg-white dark:bg-zinc-900"></iframe>
                        </div>
                    <?php elseif($isPpt): ?>
                        <div class="border border-orange-500/20 rounded-2xl overflow-hidden shadow-sm bg-[#c43e1c]/5 dark:bg-[#c43e1c]/10 p-4 flex flex-col justify-between h-44 border-l-4 border-l-orange-500">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-600/10 dark:bg-orange-600/20 flex items-center justify-center text-orange-650 dark:text-orange-400 font-black text-sm shadow-sm flex-shrink-0">
                                    PPTX
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-extrabold text-xs text-slate-800 dark:text-white truncate"><?php echo e($challenge->attachment_filename); ?></h4>
                                    <p class="text-[10px] text-slate-500 dark:text-zinc-400 font-semibold mt-0.5">Presentation Slideshow</p>
                                    <p class="text-[9px] text-orange-600 dark:text-orange-400 font-bold uppercase tracking-wider mt-2.5 flex items-center gap-1">
                                        <span>★ Powerpoint Deck</span>
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-auto">
                                <a href="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>" target="_blank" class="py-2 text-center text-[10px] font-black bg-orange-600 hover:bg-orange-700 text-white rounded-lg shadow-sm transition hover:scale-[1.02]">
                                    Download
                                </a>
                                <a href="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo e(urlencode(secure_url('storage/' . $challenge->attachment_path))); ?>" target="_blank" class="py-2 text-center text-[10px] font-black bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 rounded-lg hover:bg-slate-50 transition hover:scale-[1.02]">
                                    View slides
                                </a>
                            </div>
                        </div>
                    <?php elseif($isWord): ?>
                        <div class="border border-blue-500/20 rounded-2xl overflow-hidden shadow-sm bg-[#185abd]/5 dark:bg-[#185abd]/10 p-4 flex flex-col justify-between h-44 border-l-4 border-l-blue-600">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-600/10 dark:bg-blue-600/20 flex items-center justify-center text-blue-655 dark:text-blue-400 font-black text-sm shadow-sm flex-shrink-0">
                                    DOCX
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-extrabold text-xs text-slate-800 dark:text-white truncate"><?php echo e($challenge->attachment_filename); ?></h4>
                                    <p class="text-[10px] text-slate-500 dark:text-zinc-400 font-semibold mt-0.5">Word Document</p>
                                    <p class="text-[9px] text-blue-600 dark:text-blue-400 font-bold uppercase tracking-wider mt-2.5 flex items-center gap-1">
                                        <span>★ Document Brief</span>
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-auto">
                                <a href="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>" target="_blank" class="py-2 text-center text-[10px] font-black bg-blue-600 hover:bg-blue-750 text-white rounded-lg shadow-sm transition hover:scale-[1.02]">
                                    Download
                                </a>
                                <a href="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo e(urlencode(secure_url('storage/' . $challenge->attachment_path))); ?>" target="_blank" class="py-2 text-center text-[10px] font-black bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 rounded-lg hover:bg-slate-50 transition hover:scale-[1.02]">
                                    View doc
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="border border-slate-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm bg-slate-50 dark:bg-zinc-900/40 p-3 flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-650 dark:text-purple-400 text-xs font-black flex-shrink-0">
                                    FILE
                                </div>
                                <span class="text-xs font-bold text-slate-750 dark:text-zinc-300 truncate"><?php echo e($challenge->attachment_filename); ?></span>
                            </div>
                            <a href="<?php echo e(asset('storage/' . $challenge->attachment_path)); ?>" target="_blank" class="px-3 py-1.5 bg-purple-600 text-white text-[10px] font-bold rounded-lg hover:bg-purple-700 transition flex items-center gap-1 shadow-sm">
                                Download
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            
            <div class="border-t border-slate-100 dark:border-zinc-800/80 pt-4 mt-auto space-y-2 text-xs font-bold text-slate-500">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 dark:text-zinc-500">Budget Range:</span>
                    <span class="text-primary-600 font-extrabold">₹<?php echo e(number_format($challenge->budget_min)); ?> – ₹<?php echo e(number_format($challenge->budget_max)); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 dark:text-zinc-500">Deadline:</span>
                    <span class="text-slate-800 dark:text-zinc-300"><?php echo e($challenge->deadline->format('M d, Y')); ?> <span class="text-gray-400 dark:text-zinc-500 font-medium">(<?php echo e($challenge->deadline->diffForHumans()); ?>)</span></span>
                </div>
            </div>

            <?php if($applied): ?>
                <button disabled class="mt-4 w-full py-3.5 rounded-xl bg-slate-100 dark:bg-zinc-800/60 text-slate-400 dark:text-zinc-500 font-extrabold text-xs cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Applied to Challenge
                </button>
            <?php else: ?>
                <a href="<?php echo e(route('startup.challenges.apply', $challenge)); ?>" class="mt-4 w-full py-3.5 rounded-xl bg-primary-600 hover:bg-primary-750 text-white font-extrabold text-xs text-center block transition hover:scale-[1.01] shadow-md shadow-primary-500/10">Apply Now</a>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-8"><?php echo e($challenges->withQueryString()->links()); ?></div>
    <?php endif; ?>

    
    <div x-show="lightboxOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="lightboxOpen = false">
        
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 p-2.5 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors z-[110]" aria-label="Close Lightbox">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        
        <div class="relative max-w-4xl w-full flex flex-col items-center justify-center scale-95 duration-300"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="scale-90"
             x-transition:enter-end="scale-100"
             @click.outside="lightboxOpen = false">
            
            <img :src="lightboxImage" class="max-h-[80vh] max-w-full rounded-2xl shadow-2xl object-contain border border-white/10">
            <h3 class="text-white font-outfit font-black text-lg mt-5 text-center leading-snug drop-shadow-md" x-text="lightboxTitle"></h3>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\startup\challenges.blade.php ENDPATH**/ ?>