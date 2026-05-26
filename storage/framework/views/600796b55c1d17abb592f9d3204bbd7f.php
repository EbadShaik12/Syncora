<?php $__env->startSection('title', $user->companyName() . ' — Profile'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.cover-gradient { background: linear-gradient(135deg, #4f46e5 0%, #a855f7 50%, #ec4899 100%); }
.stat-card { @apply bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white text-center shadow-lg; }
.info-row { @apply flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800/50 last:border-0; }
.tag-chip { @apply text-xs font-bold px-3 py-1.5 rounded-xl border transition-colors; }

/* Timeline animations */
.timeline-dot { transition: all 0.3s ease; }
.timeline-item:hover .timeline-dot { transform: scale(1.3); background-color: #a855f7; box-shadow: 0 0 15px rgba(168,85,247,0.6); }

/* Compatibility Ring Animation */
@keyframes drawCircle {
    from { stroke-dashoffset: 125.6; }
    to { stroke-dashoffset: var(--target-offset); }
}
.animated-ring {
    stroke-dasharray: 125.6;
    stroke-dashoffset: 125.6;
    animation: drawCircle 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards 0.5s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $profile = $user->profile();
    
    // Core parameters
    $companyName = $user->companyName();
    $isStartup = $user->isStartup();
    $industryName = $profile?->industry?->name ?? 'Innovative Enterprise';
    
    // Calculated founded/est year
    $foundedYear = $isStartup ? ($profile->founded_year ?? 2023) : 2015;
    
    // Mission & Vision Builder
    if ($isStartup) {
        $mission = match(trim($companyName)) {
            'NeuralCart AI' => 'To revolutionize global e-commerce by making real-time, deep-learning behavioral recommendations accessible to every merchant.',
            'MediSync' => 'To streamline clinical operations and administrative workflows, returning precious hours back to doctors and caregivers.',
            'FinFlow' => 'To democratize financial products by building the embeddable API infrastructure layer for the next generation of digital startups.',
            'GreenHarvest' => 'To empower farmers with IoT-driven precision advisory, increasing agricultural yield sustainably.',
            'EduSphere' => 'To personalize education dynamically for K-12 students, ensuring every child learns at their optimal pace.',
            'SecureNet' => 'To deliver unbreakable zero-trust protection for enterprise operations through real-time threat neutralization.',
            default => 'To accelerate digital transitions and deliver breakthrough innovation in ' . $industryName . ' through high-agility solutions.'
        };
        $vision = match(trim($companyName)) {
            'NeuralCart AI' => 'To power the default predictive interface for digital commerce platforms worldwide.',
            'MediSync' => 'To become the digital operating system of choice for 10,000 hospital networks in South Asia.',
            'FinFlow' => 'A financial ecosystem where any software platform can deploy robust banking features in minutes.',
            'GreenHarvest' => 'A globally carbon-neutral agricultural cycle driven by precise data intelligence.',
            'EduSphere' => 'An accessible learning ecosystem where geography never dictates educational quality.',
            'SecureNet' => 'A secure digital economy protected by predictive, autonomous defense algorithms.',
            default => 'To lead the industry in ' . $industryName . ' and serve as an anchor for strategic business transformation.'
        };
    } else {
        $mission = match(trim($companyName)) {
            'TATA Digital Innovations' => 'To lead digital transformation across retail, financial, and entertainment markets by backing agile technologies.',
            'Apollo Healthcare Ventures' => 'To fund, validate, and scale digital HealthTech systems across a network of tier-one hospitals.',
            'HDFC Innovation Labs' => 'To foster next-generation financial inclusion, embedded banking, and pilot FinTech integrations at scale.',
            'Reliance New Energy' => 'To build the smart clean energy and precision agriculture framework for a sustainable India.',
            default => 'To identify, accelerate, and scale innovative B2B solutions through corporate backing and strategic alliance.'
        };
        $vision = match(trim($companyName)) {
            'TATA Digital Innovations' => 'An integrated consumer ecosystem where every retail point is hyper-personalized.',
            'Apollo Healthcare Ventures' => 'A zero-latency healthcare ecosystem connecting clinicians and patient diagnostics seamlessly.',
            'HDFC Innovation Labs' => 'To secure our position as the digital banking hub for 100 million consumers.',
            'Reliance New Energy' => 'A smart, grid-connected renewable resource network covering agriculture and industry.',
            default => 'To establish the industry benchmark for corporate innovation and digital partnership.'
        };
    }

    // Database overrides for custom Mission/Vision
    if ($profile) {
        if (!empty($profile->mission)) {
            $mission = $profile->mission;
        }
        if (!empty($profile->vision)) {
            $vision = $profile->vision;
        }
    }

    // Chronological Interactive Timeline growth Milestones (incorporating started, mission, vision, and revenue)
    $timelineItems = [];
    $currentYear = intval(date('Y'));

    // Node 1: 🌱 Inception (When it Started)
    $timelineItems[] = [
        'year' => $foundedYear,
        'type' => 'inception',
        'title' => $isStartup ? '🌱 Company Incorporation & Launch' : '🏢 Innovation Division Launch',
        'detail' => $isStartup 
            ? "Successfully assembled the core founding team of engineers, established core operating principles, and formally incorporated to transform " . $industryName . "."
            : "Formally inaugurated our sandbox enterprise initiative to scout, validate, and accelerate external solutions in " . $industryName . ".",
        'metric' => 'Launched: ' . $foundedYear,
        'icon' => $isStartup ? '🌱' : '🏢',
        'progress' => 0
    ];

    // Node 2: 🎯 Mission & Vision Anchor
    $timelineItems[] = [
        'year' => $foundedYear,
        'type' => 'mission_vision',
        'title' => '🎯 North Star: Mission & Vision Aligned',
        'detail' => "Solidified our long-term values, tactical objectives, and operational trajectory. Designed core principles to steer the future of high-impact strategic matches.",
        'mission' => $mission,
        'vision' => $vision,
        'metric' => 'Purpose',
        'icon' => '🎯',
        'progress' => 15
    ];

    // Nodes 3+: Dynamic Year-over-Year milestones & Revenue
    if ($isStartup) {
        $funding = $profile->funding_amount ?? 0;
        $formattedFunding = $funding > 0 ? '₹' . number_format($funding / 100000, 1) . 'L Seed Fund' : 'Bootstrapped Core';

        // Year 1 (Product Validation)
        $revYear1 = $foundedYear + 1;
        $timelineItems[] = [
            'year' => $revYear1,
            'type' => 'revenue',
            'title' => '⚡ MVP Completed & Alpha Pilots',
            'detail' => "Completed the closed alpha prototype. Conducted early integration trials with validation partners to prove computational speed and optimization indices.",
            'metric' => 'Capital: ' . $formattedFunding,
            'icon' => '⚡',
            'progress' => 45
        ];

        // Year 2 (Commercial Launch / Middle year)
        $revYear2 = $foundedYear + 2;
        if ($revYear2 < $currentYear) {
            $timelineItems[] = [
                'year' => $revYear2,
                'type' => 'revenue',
                'title' => '📈 Commercial Traction & Recurring Revenue',
                'detail' => "Launched production API models and software integrations. Onboarded premium regional clients, establishing standard recurring subscription revenue pathways.",
                'metric' => trim($companyName) === 'MediSync' ? 'ARR: ₹35L' : (trim($companyName) === 'SecureNet' ? 'ARR: ₹80L' : 'ARR: ₹15L'),
                'icon' => '💸',
                'progress' => 75
            ];
        }

        // Year 3 (Current Year / Expansion)
        $timelineItems[] = [
            'year' => $currentYear,
            'type' => 'revenue',
            'title' => '🚀 Enterprise Multi-Tenant Scaling',
            'detail' => "SaaS modules serving live high-volume production operations. Adding advanced customization capabilities, multi-tenant database clusters, and actively matching with major corporate partners.",
            'metric' => !empty($profile->annual_revenue) ? $profile->annual_revenue : (trim($companyName) === 'MediSync' ? 'ARR: ₹1.2 Cr' : (trim($companyName) === 'SecureNet' ? 'ARR: ₹3.1 Cr' : 'ARR: ₹45L Proj.')),
            'icon' => '🚀',
            'progress' => 100
        ];
    } else {
        // Corporate
        $budgetMin = $profile->budget_min ?? 0;
        $budgetMax = $profile->budget_max ?? 0;
        $formattedBudget = $budgetMax > 0 ? '₹' . number_format($budgetMin / 100000, 1) . 'L - ₹' . number_format($budgetMax / 100000, 1) . 'L' : 'Venture Allocation';

        // Year 1 (Strategic Setup)
        $corpYear1 = $foundedYear + 1;
        $timelineItems[] = [
            'year' => $corpYear1,
            'type' => 'revenue',
            'title' => '🤝 Cohort Challenge Scopes Established',
            'detail' => "Coordinated with internal commercial heads to define integration pain-points. Promoted corporate sandbox availability to select software incubators.",
            'metric' => 'Allocated: ' . $formattedBudget,
            'icon' => '🤝',
            'progress' => 45
        ];

        // Year 2 (Pilot Deployments)
        $corpYear2 = $foundedYear + 3;
        if ($corpYear2 < $currentYear) {
            $timelineItems[] = [
                'year' => $corpYear2,
                'type' => 'revenue',
                'title' => '💼 Enterprise Sandbox & Pilot Trials',
                'detail' => "Successfully coordinated 5+ cross-functional sandbox integrations. Deployed active seed funding across collaborative product trials.",
                'metric' => 'Sandbox Pilots: 5+',
                'icon' => '💼',
                'progress' => 75
            ];
        }

        // Year 3 (Current Year / Scale Matchmaking)
        $timelineItems[] = [
            'year' => $currentYear,
            'type' => 'revenue',
            'title' => '💎 Dynamic Ecosystem Acceleration',
            'detail' => "Actively swiping and validating startup proposals. Providing industry-leading infrastructure sandboxes, direct customer distribution, and dedicated follow-on pilot budgets.",
            'metric' => !empty($profile->annual_revenue) ? $profile->annual_revenue : 'Pipeline: Active Match',
            'icon' => '💎',
            'progress' => 100
        ];
    }

    // Custom database milestone integration: pull any db milestones and sort
    if ($isStartup && $profile && $profile->milestones->count()) {
        foreach ($profile->milestones as $dbMilestone) {
            $dbYear = $dbMilestone->milestone_date ? intval($dbMilestone->milestone_date->format('Y')) : $foundedYear;
            $timelineItems[] = [
                'year' => $dbYear,
                'type' => 'database',
                'title' => $dbMilestone->title,
                'detail' => $dbMilestone->description,
                'metric' => 'Milestone',
                'icon' => $dbMilestone->icon ?: '🏆',
                'progress' => 50
            ];
        }
    }

    // Chronological Sort
    usort($timelineItems, function($a, $b) {
        if ($a['year'] === $b['year']) {
            // Sort by type order to put inception/mission first
            $order = ['inception' => 0, 'mission_vision' => 1, 'database' => 2, 'revenue' => 3];
            $aOrder = $order[$a['type']] ?? 4;
            $bOrder = $order[$b['type']] ?? 4;
            return $aOrder <=> $bOrder;
        }
        return $a['year'] <=> $b['year'];
    });
?>

<div class="max-w-6xl mx-auto px-4 py-10 space-y-8 relative z-10" x-data="{ showAnalystReport: false }">
    <?php echo $__env->make('components.back-button', ['fallback' => route('search'), 'label' => 'Back to Search'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="reveal glass-card-strong rounded-[2.5rem] overflow-hidden shadow-2xl border-glow relative">
        
        
        <div class="cover-gradient h-64 relative overflow-hidden bg-cover bg-center bg-no-repeat" 
             style="<?php if($profile && $profile->banner): ?> background-image: url('<?php echo e(asset('storage/' . $profile->banner)); ?>'); <?php endif; ?>">
            <div class="absolute inset-0 noise opacity-20"></div>
            <div class="absolute inset-0 bg-white/10 mesh-gradient opacity-40"></div>
            
            
            <?php if($compatibility): ?>
            <div @click="showAnalystReport = true" 
                 class="absolute top-6 right-8 bg-white/20 backdrop-blur-xl rounded-[2rem] p-4 border border-white/30 text-white text-center shadow-2xl flex items-center gap-4 cursor-pointer hover:scale-105 hover:bg-white/30 transition duration-300 select-none group/badge z-20"
                 data-tooltip="Click to view AI Analyst Investment Report">
                <div class="relative w-16 h-16">
                    <svg class="w-full h-full" viewBox="0 0 44 44">
                        <circle cx="22" cy="22" r="20" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="4"></circle>
                        <circle cx="22" cy="22" r="20" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" 
                                style="--target-offset: <?php echo e(125.6 - (125.6 * $compatibility['score']) / 100); ?>" class="animated-ring"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-black drop-shadow-md"><?php echo e($compatibility['score']); ?></span>
                    </div>
                </div>
                <div class="text-left pr-2">
                    <div class="text-[10px] font-black uppercase tracking-widest opacity-90 mb-0.5 flex items-center gap-1">
                        Match Score <span class="group-hover/badge:translate-x-1 transition-transform">⚡</span>
                    </div>
                    <div class="text-sm font-bold text-white drop-shadow-sm flex items-center gap-1.5">
                        <?php echo e($compatibility['label']); ?> <span class="text-xs opacity-60">Report →</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="absolute left-1/2 -translate-x-1/2 md:left-12 md:translate-x-0 top-48 z-20 group">
            <div class="relative flex-shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-pink-500 rounded-3xl blur-md opacity-50 group-hover:opacity-100 transition-opacity"></div>
                <img src="<?php echo e($user->logoUrl()); ?>" alt="<?php echo e($user->companyName()); ?>"
                    class="w-32 h-32 rounded-3xl object-cover ring-8 ring-white dark:ring-gray-900 shadow-2xl relative z-10 bg-white flex-shrink-0">
            </div>
        </div>

        
        <div class="bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl px-8 sm:px-12 pb-10 pt-20 md:pt-6 md:pl-48">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                <div class="pb-2 flex-1 min-w-0 w-full text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-2">
                        <h1 class="text-4xl font-black font-outfit text-gray-900 dark:text-white truncate max-w-full animate-fade-in" title="<?php echo e($user->companyName()); ?>"><?php echo e($user->companyName()); ?></h1>
                        <?php $__currentLoopData = $user->badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $iconMap = [
                                'chat' => '💬',
                                'message' => '✉️',
                                'handshake' => '🤝',
                                'bolt' => '⚡',
                                'star' => '⭐',
                                'rocket' => '🚀',
                                'trophy' => '🏆',
                                'crown' => '👑',
                                'check' => '✅',
                                'book' => '📖',
                            ];
                            $displayIcon = isset($b->icon) ? ($iconMap[strtolower(trim($b->icon))] ?? $b->icon) : '⭐';
                        ?>
                        <span title="<?php echo e($b->name); ?>: <?php echo e($b->description); ?>" class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg border-2 border-white dark:border-gray-900 text-base select-none" >
                            <?php echo e($displayIcon); ?>

                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-4 gap-y-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <?php if($profile?->industry): ?><span class="text-primary-600 dark:text-primary-400"><?php echo e($profile->industry->name); ?></span><?php endif; ?>
                        <?php if($profile?->city): ?>
                            <?php
                                $cityText = $profile->city;
                                $stateText = $profile->state ?? '';
                                // Avoid duplicating state if already included in city
                                $locationStr = (strlen($stateText) > 0 && stripos($cityText, $stateText) === false)
                                    ? $cityText . ', ' . $stateText
                                    : $cityText;
                            ?>
                            <span class="flex items-center gap-1">📍 <?php echo e($locationStr); ?></span>
                        <?php endif; ?>
                        <?php if($profile?->website): ?><a href="<?php echo e($profile->website); ?>" target="_blank" class="hover:text-primary-600 transition flex items-center gap-1">🌐 Website</a><?php endif; ?>
                    </div>

                    
                    <?php if($user->isStartup() && $profile): ?>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-3 select-none">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                            🚀 <?php echo e($profile->stageLabel()); ?>

                        </span>
                        <?php if($profile->team_size): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-violet-500/10 text-violet-600 dark:text-violet-400 border border-violet-500/20">
                            👥 <?php echo e($profile->team_size); ?> <?php echo e($profile->team_size == 1 ? 'Member' : 'Members'); ?>

                        </span>
                        <?php endif; ?>
                        <?php if($profile->founded_year): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            📅 Est. <?php echo e($profile->founded_year); ?>

                        </span>
                        <?php endif; ?>
                        <?php if($profile->funding_amount > 0): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            💰 Raised ₹<?php echo e(number_format($profile->funding_amount / 100000, 1)); ?>L
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($user->isCorporate() && $profile): ?>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-3 select-none">
                        <?php if($profile->company_size): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                            🏢 <?php echo e(ucfirst($profile->company_size)); ?> Size
                        </span>
                        <?php endif; ?>
                        <?php if($profile->budget_min || $profile->budget_max): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            💰 Budget: ₹<?php echo e(number_format($profile->budget_min / 100000, 1)); ?>L – ₹<?php echo e(number_format($profile->budget_max / 100000, 1)); ?>L
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="flex flex-wrap gap-4 pb-2 justify-center md:justify-end">
                    <?php if($connection && $connection->status === 'active'): ?>
                        <a href="<?php echo e(route('chat.show', $connection)); ?>"
                            class="shimmer-btn inline-flex items-center gap-3 bg-gradient-to-r from-primary-600 to-purple-600 text-white font-black px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl shadow-primary-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Message
                        </a>
                    <?php elseif($user->isStartup() || $user->isCorporate()): ?>
                        <a href="<?php echo e(auth()->user()->isStartup() ? route('startup.swipe') : route('corporate.swipe')); ?>"
                            class="shimmer-btn inline-flex items-center gap-3 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-black px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl shadow-pink-500/30">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            Match
                        </a>
                    <?php endif; ?>

                    <?php if($user->isStartup()): ?>
                    <a href="<?php echo e(route('profile.pdf', $user)); ?>"
                        class="glass-card inline-flex items-center gap-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-bold px-8 py-4 rounded-2xl transition shadow-lg border border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid lg:grid-cols-3 gap-8 stagger-container">

        
        <div class="lg:col-span-2 space-y-8">

            
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <h2 class="text-2xl font-black font-outfit mb-6 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-purple-500 flex items-center justify-center text-white text-xl shadow-lg">✦</span>
                    About Us
                </h2>
                <div class="prose prose-lg dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                    <?php if($user->isStartup()): ?>
                        <?php echo e($profile?->elevator_pitch ?? 'No description yet.'); ?>

                    <?php else: ?>
                        <?php echo e($profile?->about ?: ($profile?->problem_statement ?? 'No description yet.')); ?>

                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($user->isCorporate() && $profile?->problem_statement): ?>
            <div class="reveal glass-card-strong rounded-3xl p-8 border border-purple-200 dark:border-purple-800/50 bg-purple-50/50 dark:bg-purple-900/10">
                <h2 class="text-2xl font-black font-outfit mb-6 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl shadow-lg">🎯</span>
                    What We're Looking For
                </h2>
                <p class="text-gray-700 dark:text-gray-300 font-medium leading-relaxed text-lg"><?php echo e($profile->problem_statement); ?></p>
            </div>
            <?php endif; ?>

            
            <?php
                $tags = $user->isStartup() ? ($profile?->tech_tags ?? []) : ($profile?->seeking_technologies ?? []);
                $tagLabel = $user->isStartup() ? 'Tech Stack' : 'Seeking Technologies';
                $tagColor = $user->isStartup() ? 'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-300 dark:border-primary-800/50 hover:bg-primary-500 hover:text-white' : 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800/50 hover:bg-purple-500 hover:text-white';
                $icon = $user->isStartup() ? '⚡' : '🔍';
                $iconBg = $user->isStartup() ? 'from-blue-500 to-cyan-500' : 'from-indigo-500 to-purple-500';
            ?>
            <?php if(count($tags)): ?>
            <div class="reveal glass-card-strong rounded-3xl p-8">
                <h2 class="text-2xl font-black font-outfit mb-6 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br <?php echo e($iconBg); ?> flex items-center justify-center text-white text-xl shadow-lg"><?php echo e($icon); ?></span>
                    <?php echo e($tagLabel); ?>

                </h2>
                <div class="flex flex-wrap gap-3">
                    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="tag-chip <?php echo e($tagColor); ?> cursor-default">#<?php echo e($tag); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="reveal glass-card-strong rounded-[2.5rem] p-8 sm:p-10 overflow-hidden relative border-glow">
                
                <div class="absolute -top-12 -right-12 w-80 h-80 bg-gradient-to-br from-primary-500/10 to-pink-500/10 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-80 h-80 bg-gradient-to-br from-purple-500/10 to-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

                
                <div class="relative z-10 mb-10">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-xl text-[10px] font-black tracking-widest uppercase bg-gradient-to-r from-primary-600 to-purple-600 text-white shadow-md shadow-primary-500/10 mb-4 select-none">
                        📈 Company Journey
                    </span>
                    <h2 class="text-3xl font-black font-outfit text-gray-900 dark:text-white leading-tight">
                        Interactive Growth &amp; Purpose Timeline
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 font-medium mt-2 leading-relaxed">
                        A dynamic chronological blueprint tracing our company's origin, founding mission, long-term vision statements, and year-by-year operational revenue scaling.
                    </p>
                </div>

                
                <div class="relative z-10 pl-6 sm:pl-10 space-y-12">
                    
                    
                    <div class="absolute left-[19px] sm:left-[27px] top-6 bottom-6 w-1 bg-gradient-to-b from-primary-500 via-indigo-500 to-purple-600 rounded-full shadow-lg shadow-indigo-500/10"></div>
                    
                    
                    <?php $__currentLoopData = $timelineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative timeline-item group">
                        
                        
                        <div class="absolute -left-[30px] sm:-left-[39px] top-1.5 w-8 sm:w-10 h-8 sm:h-10 rounded-2xl bg-white dark:bg-zinc-950 border-2 border-primary-500/80 shadow-[0_0_15px_rgba(99,102,241,0.2)] dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] flex items-center justify-center text-base sm:text-lg timeline-dot select-none z-20">
                            <?php echo e($item['icon']); ?>

                        </div>
                        
                        
                        <div class="card-lift bg-white/40 dark:bg-zinc-900/15 backdrop-blur-xl border border-gray-100 dark:border-zinc-800/60 rounded-[2rem] p-6 hover:border-primary-500/40 dark:hover:border-primary-500/40 hover:shadow-2xl transition duration-300 relative overflow-hidden">
                            <div class="absolute inset-0 noise opacity-10 pointer-events-none"></div>
                            
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-4 border-b border-gray-100 dark:border-zinc-800/40">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider bg-primary-50 dark:bg-primary-950/40 text-primary-650 dark:text-primary-400 border border-primary-100/30 dark:border-primary-900/30">
                                        🗓️ FY <?php echo e($item['year']); ?> MILESTONE
                                    </span>
                                    <h3 class="font-black text-xl text-gray-900 dark:text-white mt-2 group-hover:text-primary-500 transition-colors">
                                        <?php echo e($item['title']); ?>

                                    </h3>
                                </div>
                                
                                
                                <?php if($item['type'] === 'mission_vision'): ?>
                                    <span class="px-4 py-1.5 bg-gradient-to-r from-indigo-500 to-primary-600 text-white shadow-md shadow-indigo-500/10 rounded-2xl text-[10px] font-extrabold uppercase tracking-widest self-start sm:self-center">
                                        <?php echo e($item['metric']); ?>

                                    </span>
                                <?php elseif(str_contains(strtolower($item['metric']), 'arr') || str_contains(strtolower($item['metric']), 'revenue') || str_contains(strtolower($item['metric']), 'capital') || str_contains(strtolower($item['metric']), 'budget') || str_contains(strtolower($item['metric']), 'fund') || str_contains(strtolower($item['metric']), 'raised')): ?>
                                    <span class="px-4 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md shadow-emerald-500/10 rounded-2xl text-[10px] font-extrabold uppercase tracking-widest self-start sm:self-center">
                                        💰 <?php echo e($item['metric']); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="px-4 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-md shadow-blue-500/10 rounded-2xl text-[10px] font-extrabold uppercase tracking-widest self-start sm:self-center">
                                        <?php echo e($item['metric']); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            
                            <p class="text-sm text-gray-600 dark:text-zinc-400 font-medium leading-relaxed mb-4">
                                <?php echo e($item['detail']); ?>

                            </p>

                            
                            <?php if($item['type'] === 'mission_vision'): ?>
                                <div class="grid md:grid-cols-2 gap-4 mt-6">
                                    
                                    <div class="bg-gradient-to-br from-blue-50/40 to-indigo-50/20 dark:from-blue-950/15 dark:to-indigo-950/5 border border-blue-100/50 dark:border-blue-900/20 rounded-2xl p-5 relative overflow-hidden group/m hover:border-blue-400 dark:hover:border-blue-700/80 transition duration-300">
                                        <div class="absolute top-4 right-4 text-3xl opacity-25 group-hover/m:scale-110 transition duration-300 select-none">🎯</div>
                                        <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-2">Our Mission</h4>
                                        <p class="text-xs font-semibold text-gray-800 dark:text-zinc-300 leading-relaxed"><?php echo e($item['mission']); ?></p>
                                    </div>

                                    
                                    <div class="bg-gradient-to-br from-purple-50/40 to-pink-50/20 dark:from-purple-950/15 dark:to-pink-950/5 border border-purple-100/50 dark:border-purple-900/20 rounded-2xl p-5 relative overflow-hidden group/v hover:border-purple-400 dark:hover:border-purple-700/80 transition duration-300">
                                        <div class="absolute top-4 right-4 text-3xl opacity-25 group-hover/v:scale-110 transition duration-300 select-none">👁️‍🗨️</div>
                                        <h4 class="text-[10px] font-black uppercase tracking-widest text-purple-600 dark:text-purple-400 mb-2">Our Vision</h4>
                                        <p class="text-xs font-semibold text-gray-800 dark:text-zinc-300 leading-relaxed"><?php echo e($item['vision']); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            
                            <?php if(isset($item['progress']) && ($item['type'] === 'revenue' || $item['type'] === 'inception')): ?>
                                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-zinc-800/40">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500">Corporate Scaling Index</span>
                                        <span class="text-xs font-bold text-gray-900 dark:text-white"><?php echo e($item['progress']); ?>% Growth Scale</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-gray-100 dark:bg-zinc-800/50 rounded-full overflow-hidden relative">
                                        <div class="h-full rounded-full bg-gradient-to-r from-primary-500 via-indigo-500 to-emerald-500 transition-all duration-1000" style="width: <?php echo e($item['progress']); ?>%"></div>
                                    </div>
                                    <div class="flex justify-between text-[9px] font-bold text-gray-400 dark:text-zinc-555 mt-1 uppercase select-none">
                                        <span>R&amp;D Inception</span>
                                        <span>Beta Pilots</span>
                                        <span>Production Scaling</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>

            
            <?php if($compatibility && isset($compatibility['breakdown'])): ?>
            <div class="reveal glass-card-strong rounded-3xl p-8 border-glow">
                <h2 class="text-2xl font-black font-outfit mb-8 flex items-center gap-3 text-gray-900 dark:text-white">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl shadow-lg">🤖</span>
                    AI Compatibility Breakdown
                </h2>
                
                <div class="grid sm:grid-cols-2 gap-8 mb-8">
                    <?php $__currentLoopData = $compatibility['breakdown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 card-lift">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-gray-900 dark:text-white"><?php echo e($item['label']); ?></h4>
                            <span class="text-xl font-black <?php echo e($item['score'] >= ($item['max']*0.8) ? 'text-green-500' : 'text-primary-500'); ?>"><?php echo e(round($item['score'])); ?><span class="text-sm text-gray-400">/<?php echo e($item['max']); ?></span></span>
                        </div>
                        
                        
                        <div class="relative w-20 h-20 mx-auto">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="40" cy="40" r="34" fill="none" class="stroke-gray-200/60 dark:stroke-zinc-700/50" stroke-width="6"></circle>
                                <circle cx="40" cy="40" r="34" fill="none" class="<?php echo e($item['score'] >= ($item['max']*0.8) ? 'stroke-green-500' : 'stroke-primary-500'); ?> transition-all duration-1000 ease-out" 
                                        stroke-width="6" stroke-linecap="round" stroke-dasharray="213.6" 
                                        style="stroke-dashoffset: <?php echo e(213.6 - (213.6 * ($item['max'] > 0 ? $item['score']/$item['max'] : 0))); ?>"></circle>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center text-2xl filter drop-shadow-md select-none">
                                <?php
                                    $breakdownEmojis = [
                                        'Industry Match' => '🏢',
                                        'Tech Overlap' => '💻',
                                        'Partnership Type' => '🤝',
                                        'Stage Fit' => '📈',
                                        'Budget Range' => '💰',
                                        'Location' => '📍',
                                    ];
                                    $displayEmoji = $breakdownEmojis[$item['label']] ?? '✨';
                                ?>
                                <?php echo e($displayEmoji); ?>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="bg-gradient-to-r from-primary-600 via-purple-600 to-pink-600 rounded-2xl p-6 text-center text-white shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9ImIiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI2IpIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PC9zdmc+')] opacity-20"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <span class="text-5xl font-black drop-shadow-md"><?php echo e($compatibility['score']); ?><span class="text-2xl text-white/70">/100</span></span>
                        <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                        <span class="text-xl font-bold uppercase tracking-widest drop-shadow-md"><?php echo e($compatibility['label']); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="space-y-8">

            
            <div class="reveal reveal-delay-1 glass-card-strong rounded-3xl p-8">
                <h3 class="font-black font-outfit mb-6 text-lg text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Fast Facts
                </h3>
                <div class="space-y-1">
                    <?php if($user->isStartup() && $profile): ?>
                        <div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Stage</span><span class="text-sm font-black"><?php echo e($profile->stageLabel()); ?></span></div>
                        <?php if($profile->team_size): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Team</span><span class="text-sm font-black"><?php echo e($profile->team_size); ?> <span class="text-gray-400">ppl</span></span></div><?php endif; ?>
                        <?php if($profile->founded_year): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Founded</span><span class="text-sm font-black"><?php echo e($profile->founded_year); ?></span></div><?php endif; ?>
                        <?php if($profile->funding_status): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Funding</span><span class="text-sm font-black capitalize"><?php echo e(str_replace('_',' ',$profile->funding_status)); ?></span></div><?php endif; ?>
                        <?php if($profile->funding_amount > 0): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Raised</span><span class="text-sm font-black text-green-500">₹<?php echo e(number_format($profile->funding_amount)); ?></span></div><?php endif; ?>
                    <?php elseif($user->isCorporate() && $profile): ?>
                        <?php if($profile->company_size): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Size</span><span class="text-sm font-black capitalize"><?php echo e(str_replace('_',' ',$profile->company_size)); ?></span></div><?php endif; ?>
                        <?php if($profile->budget_min || $profile->budget_max): ?><div class="info-row flex-col items-start gap-1"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Budget</span><span class="text-lg font-black text-green-500">₹<?php echo e(number_format($profile->budget_min)); ?> <span class="text-sm text-gray-400 font-bold px-1">to</span> ₹<?php echo e(number_format($profile->budget_max)); ?></span></div><?php endif; ?>
                        <?php if($profile->established_year): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Est.</span><span class="text-sm font-black"><?php echo e($profile->established_year); ?></span></div><?php endif; ?>
                    <?php endif; ?>
                    <?php if($profile?->city): ?>
                        <?php
                            $cityText = $profile->city;
                            $stateText = $profile->state ?? '';
                            $locationStr = (strlen($stateText) > 0 && stripos($cityText, $stateText) === false)
                                ? $cityText . ', ' . $stateText
                                : $cityText;
                        ?>
                        <div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Location</span><span class="text-sm font-black"><?php echo e($locationStr); ?></span></div>
                    <?php endif; ?>
                    <?php if($profile?->website): ?><div class="info-row"><span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Website</span><a href="<?php echo e($profile->website); ?>" target="_blank" class="text-sm text-primary-600 hover:text-primary-700 font-black flex items-center gap-1">Visit Site <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a></div><?php endif; ?>
                </div>
            </div>

            
            <?php if($user->badges->count()): ?>
            <div class="reveal reveal-delay-2 glass-card-strong rounded-3xl p-8">
                <h3 class="font-black font-outfit mb-6 text-lg text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Badges Earned
                </h3>
                <div class="grid grid-cols-3 gap-4">
                    <?php $__currentLoopData = $user->badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $iconMap = [
                            'chat' => '💬',
                            'message' => '✉️',
                            'handshake' => '🤝',
                            'bolt' => '⚡',
                            'star' => '⭐',
                            'rocket' => '🚀',
                            'trophy' => '🏆',
                            'crown' => '👑',
                            'check' => '✅',
                            'book' => '📖',
                        ];
                        $displayIcon = isset($badge->icon) ? ($iconMap[strtolower(trim($badge->icon))] ?? $badge->icon) : '⭐';
                    ?>
                    <div class="text-center group cursor-default" title="<?php echo e($badge->description); ?>">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg mb-2 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 text-2xl select-none">
                            <?php echo e($displayIcon); ?>

                        </div>
                        <p class="text-[10px] text-gray-900 dark:text-white font-bold leading-tight line-clamp-2 uppercase tracking-wider"><?php echo e($badge->name); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

    
    <?php if($compatibility && isset($compatibility['analyst'])): ?>
    <div x-show="showAnalystReport" 
         class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md transition-opacity duration-300"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        
        <div class="absolute inset-0" @click="showAnalystReport = false"></div>

        
        <div class="glass-card-strong w-full max-w-4xl max-h-[85vh] rounded-[2.5rem] overflow-hidden shadow-2xl border-glow transform transition-all duration-300 flex flex-col relative z-10 bg-white/95 dark:bg-[#0d0d12]/95"
             x-show="showAnalystReport"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="scale-100 opacity-100"
             x-transition:leave-end="scale-95 opacity-0">

            
            <div class="absolute inset-0 noise opacity-10 pointer-events-none"></div>
            
            
            <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-zinc-800/60 flex items-center justify-between bg-gradient-to-r from-primary-50/30 to-purple-50/20 dark:from-primary-950/10 dark:to-purple-950/5 relative">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white text-2xl shadow-lg select-none">
                        🤖
                    </div>
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-primary-100 dark:bg-primary-900/30 text-primary-650 dark:text-primary-400 select-none">
                            AI Venture Intelligence
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black font-outfit text-gray-900 dark:text-white mt-1 leading-tight">
                            Executive Investment Analyst Report
                        </h3>
                    </div>
                </div>
                
                
                <button @click="showAnalystReport = false" 
                        class="w-10 h-10 rounded-xl bg-gray-50 hover:bg-gray-100 dark:bg-zinc-900 dark:hover:bg-zinc-800 flex items-center justify-center text-gray-500 dark:text-zinc-400 font-bold transition">
                    ✕
                </button>
            </div>

            
            <div class="p-6 sm:p-8 overflow-y-auto space-y-8 flex-1 scroll-smooth">
                
                
                <div class="bg-gradient-to-r from-primary-600 via-purple-600 to-pink-600 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9ImIiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI2IpIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PC9zdmc+')] opacity-20"></div>
                    <div class="relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-widest text-white/80 select-none flex items-center gap-2">✨ Gemini AI — Executive Partnership Summary</span>
                        <p class="text-base sm:text-lg font-bold leading-relaxed mt-2">
                            <?php echo e($compatibility['analyst']['summary'] ?? 'AI analysis is being generated. Please check back shortly.'); ?>

                        </p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    
                    
                    <div class="space-y-6">
                        <h4 class="text-sm font-black uppercase tracking-widest text-gray-500 dark:text-zinc-400 select-none">
                            📊 Compatibility Breakdown
                        </h4>
                        
                        <div class="space-y-4">
                            <?php
                                $breakdown = $compatibility['breakdown'] ?? [];
                                $breakdownMetrics = [
                                    ['label' => 'Industry Match',    'key' => 'industry',    'emoji' => '🏭', 'max' => 25],
                                    ['label' => 'Tech Overlap',      'key' => 'technology',  'emoji' => '💻', 'max' => 20],
                                    ['label' => 'Partnership Type',  'key' => 'partnership', 'emoji' => '🤝', 'max' => 20],
                                    ['label' => 'Stage Fit',         'key' => 'stage',       'emoji' => '🚀', 'max' => 15],
                                    ['label' => 'Budget Alignment',  'key' => 'budget',      'emoji' => '💰', 'max' => 10],
                                    ['label' => 'Location',          'key' => 'location',    'emoji' => '📍', 'max' => 10],
                                ];
                            ?>

                            <?php $__currentLoopData = $breakdownMetrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $metricScore = $breakdown[$metric['key']]['score'] ?? 0;
                                $metricMax   = $metric['max'];
                                $pct         = $metricMax > 0 ? round(($metricScore / $metricMax) * 100) : 0;
                            ?>
                            <div class="bg-gray-50/50 dark:bg-zinc-900/30 rounded-2xl p-4 border border-gray-100 dark:border-zinc-800/40 animate-fade-in">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-gray-800 dark:text-zinc-300 flex items-center gap-2 select-none">
                                        <span><?php echo e($metric['emoji']); ?></span> <?php echo e($metric['label']); ?>

                                    </span>
                                    <span class="text-sm font-black text-primary-500">
                                        <?php echo e($metricScore); ?><span class="text-xs text-gray-400">/<?php echo e($metricMax); ?></span>
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-gray-100 dark:bg-zinc-800 rounded-full overflow-hidden relative">
                                    <div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-indigo-500 transition-all duration-1000" style="width: <?php echo e($pct); ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php $strengths = $compatibility['analyst']['strengths'] ?? []; ?>
                            <?php if(!empty($strengths)): ?>
                            <div class="bg-emerald-50/50 dark:bg-emerald-900/10 rounded-2xl p-4 border border-emerald-200 dark:border-emerald-800/40">
                                <h5 class="text-xs font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400 mb-3 select-none">✅ AI-Identified Strengths</h5>
                                <ul class="space-y-2">
                                    <?php $__currentLoopData = $strengths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strength): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-start gap-2 text-sm text-gray-700 dark:text-zinc-300">
                                        <span class="text-emerald-500 mt-0.5">•</span>
                                        <span class="font-semibold"><?php echo e($strength); ?></span>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="space-y-6">
                        <h4 class="text-sm font-black uppercase tracking-widest text-gray-500 dark:text-zinc-400 select-none">
                            💡 Business Predictions &amp; ROI Forecasts
                        </h4>

                        <div class="grid sm:grid-cols-2 gap-4">
                            
                            <div class="bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 rounded-3xl p-5 text-center shadow-lg relative overflow-hidden group">
                                <span class="text-[9px] font-black text-emerald-650 dark:text-emerald-400 uppercase tracking-widest select-none">Expected ROI</span>
                                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">
                                    <?php echo e($compatibility['analyst']['roi_estimate'] ?? $compatibility['analyst']['roi'] ?? 'N/A'); ?>

                                </h3>
                                <p class="text-[10px] text-gray-400 font-semibold mt-1">AI-estimated multiplier</p>
                            </div>

                            
                            <div class="bg-gradient-to-br from-indigo-500/10 to-primary-500/10 border border-indigo-500/20 rounded-3xl p-5 text-center shadow-lg relative overflow-hidden group">
                                <span class="text-[9px] font-black text-indigo-650 dark:text-indigo-400 uppercase tracking-widest select-none">Match Confidence</span>
                                <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-2"><?php echo e($compatibility['analyst']['confidence'] ?? 0); ?>%</h3>
                                <p class="text-[10px] text-gray-400 font-semibold mt-1">AI assessment accuracy</p>
                            </div>

                            
                            <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 border border-purple-500/20 rounded-3xl p-5 text-center shadow-lg relative overflow-hidden group sm:col-span-2">
                                <span class="text-[9px] font-black text-purple-650 dark:text-purple-400 uppercase tracking-widest select-none">Partnership Success Probability</span>
                                <div class="flex items-center justify-center gap-4 mt-2">
                                    <span class="text-3xl font-black text-purple-600 dark:text-purple-400"><?php echo e($compatibility['analyst']['success_prob_percentage'] ?? 0); ?>%</span>
                                    <div class="w-16 h-1.5 bg-gray-100 dark:bg-zinc-800 rounded-full overflow-hidden relative">
                                        <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-pink-500" style="width: <?php echo e($compatibility['analyst']['success_prob_percentage'] ?? 0); ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-slate-50/50 dark:bg-zinc-900/30 border border-slate-100 dark:border-zinc-800/40 rounded-3xl p-6 space-y-4">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-widest select-none">Revenue Impact Prediction</span>
                                <p class="text-base font-bold text-gray-900 dark:text-white mt-1">
                                    💰 <?php echo e($compatibility['analyst']['revenue_impact'] ?? 'N/A'); ?>

                                </p>
                            </div>
                            <hr class="border-gray-100 dark:border-zinc-800/40">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-widest select-none">AI Score Breakdown</span>
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="flex-1 text-center bg-primary-50 dark:bg-primary-900/20 rounded-xl p-2">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Rule-Based</p>
                                        <p class="text-lg font-black text-primary-600 dark:text-primary-400"><?php echo e($compatibility['analyst']['rule_score'] ?? '—'); ?></p>
                                    </div>
                                    <span class="text-gray-400 font-bold">+</span>
                                    <div class="flex-1 text-center bg-purple-50 dark:bg-purple-900/20 rounded-xl p-2">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Gemini AI</p>
                                        <p class="text-lg font-black text-purple-600 dark:text-purple-400"><?php echo e($compatibility['analyst']['ai_score'] ?? '—'); ?></p>
                                    </div>
                                    <span class="text-gray-400 font-bold">=</span>
                                    <div class="flex-1 text-center bg-gradient-to-br from-primary-100 to-purple-100 dark:from-primary-900/30 dark:to-purple-900/30 rounded-xl p-2 border border-primary-200 dark:border-primary-800">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Final</p>
                                        <p class="text-lg font-black text-gray-900 dark:text-white"><?php echo e($compatibility['score'] ?? 0); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="grid md:grid-cols-2 gap-8">
                    
                    <div class="bg-blue-500/5 border border-blue-500/25 rounded-3xl p-6 relative overflow-hidden group">
                        <div class="absolute top-4 right-4 text-3xl opacity-20 select-none">⚡</div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-blue-650 dark:text-blue-400 mb-3 select-none">✨ AI Strategic Recommendation</h4>
                        <p class="text-sm font-bold text-gray-800 dark:text-zinc-200 leading-relaxed">
                            <?php echo e($compatibility['analyst']['recommendation'] ?? 'Complete your profile to receive a personalised recommendation.'); ?>

                        </p>
                    </div>

                    
                    <div class="bg-amber-500/5 border border-amber-500/25 rounded-3xl p-6 relative overflow-hidden group">
                        <div class="absolute top-4 right-4 text-3xl opacity-20 select-none">⚠️</div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-650 dark:text-amber-400 mb-3 select-none">Risk Factors &amp; Analysis</h4>
                        <p class="text-sm font-bold text-gray-800 dark:text-zinc-200 leading-relaxed mb-2">
                            <?php echo e($compatibility['analyst']['risk_analysis'] ?? 'No significant risks identified by the AI at this time.'); ?>

                        </p>
                    </div>
                </div>

            </div>

            
            <div class="p-6 border-t border-gray-100 dark:border-zinc-800/60 text-right bg-gray-50/50 dark:bg-[#0d0d12]/30 select-none">
                <button @click="showAnalystReport = false" 
                        class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg shadow-primary-600/25 transition">
                    Acknowledge Report
                </button>
            </div>

        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\profile-show.blade.php ENDPATH**/ ?>