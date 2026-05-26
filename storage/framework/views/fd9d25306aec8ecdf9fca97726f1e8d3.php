
<?php if(auth()->guard()->check()): ?>
<?php
    $user    = auth()->user();
    $checks  = [];
    $score   = 0;

    if ($user->isStartup()) {
        $profile = $user->startupProfile;
        if ($profile) {
            $checks = [
                ['label' => 'Company name',    'ok' => !empty($profile->company_name),   'weight' => 10],
                ['label' => 'Elevator pitch',  'ok' => !empty($profile->elevator_pitch), 'weight' => 15],
                ['label' => 'Industry',        'ok' => !empty($profile->industry_id),     'weight' => 10],
                ['label' => 'Tech tags',       'ok' => !empty($profile->tech_tags) && count((array)$profile->tech_tags) > 0, 'weight' => 10],
                ['label' => 'City',            'ok' => !empty($profile->city),           'weight' =>  5],
                ['label' => 'Website',         'ok' => !empty($profile->website),        'weight' =>  5],
                ['label' => 'Stage',           'ok' => !empty($profile->stage),          'weight' => 10],
                ['label' => 'Team size',       'ok' => !empty($profile->team_size),      'weight' =>  5],
                ['label' => 'Founded year',    'ok' => !empty($profile->founded_year),   'weight' =>  5],
                ['label' => 'Funding status',  'ok' => !empty($profile->funding_status), 'weight' => 10],
                ['label' => 'Logo uploaded',   'ok' => !empty($profile->logo),           'weight' =>  5],
                ['label' => 'Seeking tags',    'ok' => !empty($profile->seeking) && count((array)$profile->seeking) > 0, 'weight' => 5],
                ['label' => 'Milestone added', 'ok' => $profile->milestones()->exists(), 'weight' =>  5],
            ];
        }
    } elseif ($user->isCorporate()) {
        $profile = $user->corporateProfile;
        if ($profile) {
            $checks = [
                ['label' => 'Company name',     'ok' => !empty($profile->company_name),   'weight' => 10],
                ['label' => 'Description',      'ok' => !empty($profile->description),    'weight' => 15],
                ['label' => 'Industry',         'ok' => !empty($profile->industry_id),     'weight' => 10],
                ['label' => 'City',             'ok' => !empty($profile->city),            'weight' =>  5],
                ['label' => 'Website',          'ok' => !empty($profile->website),         'weight' =>  5],
                ['label' => 'Budget range',     'ok' => !empty($profile->budget_min),      'weight' => 10],
                ['label' => 'Logo uploaded',    'ok' => !empty($profile->logo),            'weight' => 10],
                ['label' => 'Challenge posted', 'ok' => $user->challenges()->exists(),     'weight' => 20],
                ['label' => 'Connection made',  'ok' => $user->connectionsAsUserOne()->exists() || $user->connectionsAsUserTwo()->exists(), 'weight' => 15],
            ];
        }
    }

    $total   = array_sum(array_column($checks, 'weight')) ?: 1;
    foreach ($checks as $c) { if ($c['ok']) $score += $c['weight']; }
    $pct     = round(($score / $total) * 100);
    $missing = collect($checks)->where('ok', false)->take(3)->pluck('label');

    $color = match(true) {
        $pct >= 80 => ['bar' => 'from-green-400 to-emerald-500',  'text' => 'text-green-600 dark:text-green-400',  'label' => 'Excellent!'],
        $pct >= 60 => ['bar' => 'from-blue-400 to-cyan-500',      'text' => 'text-blue-600 dark:text-blue-400',    'label' => 'Looking good'],
        $pct >= 40 => ['bar' => 'from-yellow-400 to-orange-500',  'text' => 'text-yellow-600 dark:text-yellow-400','label' => 'Getting there'],
        default    => ['bar' => 'from-red-400 to-rose-500',       'text' => 'text-red-600 dark:text-red-400',      'label' => 'Needs work'],
    };
?>

<?php if(!empty($checks)): ?>
<div x-data="{ open: false }" class="glass-card rounded-[1.5rem] p-5 shadow-lg border-glow mt-6 reveal reveal-delay-2">

    
    <div class="flex items-center justify-between mb-3 cursor-pointer select-none group" @click="open = !open">
        <div class="flex items-center gap-3 flex-wrap">
            <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4 <?php echo e($color['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-sm font-black text-gray-900 dark:text-white block uppercase tracking-wider">Profile Completeness</span>
                <span class="text-xs font-bold <?php echo e($color['text']); ?> mt-0.5"><?php echo e($pct); ?>% · <?php echo e($color['label']); ?></span>
            </div>
        </div>
        <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 flex-shrink-0 group-hover:text-primary-500"
             :class="open ? 'rotate-180' : ''"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    
    <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner relative">
        <div class="h-full bg-gradient-to-r <?php echo e($color['bar']); ?> rounded-full shadow-sm relative overflow-hidden transition-all duration-1000 ease-out"
             style="width: 0%" x-intersect="$el.style.width = '<?php echo e($pct); ?>%'">
             <div class="absolute inset-0 bg-white/30 w-1/2 -skew-x-12 translate-x-full animate-[shimmer_2s_infinite]"></div>
        </div>
    </div>

    
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="mt-4 space-y-2">

        <?php $__currentLoopData = $checks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-3 text-sm p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
            <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center shadow-sm <?php echo e($c['ok'] ? 'bg-green-100 dark:bg-green-900/30 text-green-600' : 'bg-gray-100 dark:bg-gray-800 text-gray-400'); ?>">
                <?php if($c['ok']): ?>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                <?php else: ?>
                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                <?php endif; ?>
            </span>
            <span class="font-semibold <?php echo e($c['ok'] ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-300'); ?>">
                <?php echo e($c['label']); ?>

            </span>
            <?php if(!$c['ok']): ?>
            <span class="ml-auto text-[10px] bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 px-2 py-1 rounded-lg font-black uppercase tracking-wider flex-shrink-0 shadow-sm">
                +<?php echo e($c['weight']); ?>pts
            </span>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($pct < 100 && $missing->count() > 0): ?>
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800/50">
            <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3 flex items-center gap-2">
                <span class="text-yellow-500 text-sm">💡</span> Quick wins to boost your score
            </p>
            <div class="flex flex-wrap gap-2">
                <?php $__currentLoopData = $missing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($user->isStartup() ? route('startup.profile.edit') : route('corporate.profile.edit')); ?>"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 px-3 py-1.5 rounded-xl transition">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                    Add <?php echo e($tip); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php elseif($pct === 100): ?>
        <div class="mt-4 p-4 rounded-xl bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-200 dark:border-green-800/30 text-center">
            <p class="text-sm text-green-600 dark:text-green-400 font-black flex items-center justify-center gap-2">
                <span class="text-xl">🎉</span> Profile 100% complete!
            </p>
            <p class="text-xs text-green-600/70 dark:text-green-400/70 mt-1 font-semibold">You're fully optimized for matches.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\components\profile-completeness.blade.php ENDPATH**/ ?>