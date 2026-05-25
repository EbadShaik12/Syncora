<?php $__env->startSection('title', 'Admin Analytics — StartupConnect'); ?>

<?php $__env->startPush('styles'); ?>
<style>
@keyframes countUp { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
@keyframes slideUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.stat-card  { animation: slideUp 0.5s ease forwards; opacity:0; }
.chart-card { animation: slideUp 0.6s ease forwards; opacity:0; }
.stat-card:nth-child(1)  { animation-delay:.05s }
.stat-card:nth-child(2)  { animation-delay:.10s }
.stat-card:nth-child(3)  { animation-delay:.15s }
.stat-card:nth-child(4)  { animation-delay:.20s }
.stat-card:nth-child(5)  { animation-delay:.25s }
.stat-card:nth-child(6)  { animation-delay:.30s }
.stat-card:nth-child(7)  { animation-delay:.35s }
.stat-card:nth-child(8)  { animation-delay:.40s }
.funnel-bar { transition: width 1.2s cubic-bezier(.4,0,.2,1); }
.section-card { @apply bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Administration</p>
            <h1 class="text-3xl font-black">Platform <span class="bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent">Analytics</span></h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Live
            </span>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="px-4 py-2 text-sm font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition">Manage Users →</a>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <?php
        $kpis = [
            ['label'=>'Total Users',   'value'=>$stats['total_users'],  'delta'=>'+'.$stats['new_this_week'].' this week', 'gradient'=>'from-primary-500 to-purple-600', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label'=>'Startups',      'value'=>$stats['startups'],     'delta'=>$stats['startups'].' registered',         'gradient'=>'from-blue-500 to-cyan-500',   'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label'=>'Corporates',    'value'=>$stats['corporates'],   'delta'=>$stats['corporates'].' registered',       'gradient'=>'from-purple-500 to-pink-500', 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            ['label'=>'Connections',   'value'=>$stats['connections'],  'delta'=>$stats['match_rate'].'% match rate',      'gradient'=>'from-rose-500 to-orange-500', 'icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['label'=>'Messages',      'value'=>$stats['messages'],     'delta'=>'platform total',                          'gradient'=>'from-teal-500 to-green-500',  'icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['label'=>'Challenges',    'value'=>$stats['challenges'],   'delta'=>$stats['applications'].' applications',   'gradient'=>'from-amber-500 to-yellow-500','icon'=>'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            ['label'=>'Pending Approval','value'=>$stats['pending'],    'delta'=>'needs review',                           'gradient'=>'from-yellow-400 to-orange-400','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Suspended',     'value'=>$stats['suspended'],    'delta'=>'accounts suspended',                     'gradient'=>'from-red-500 to-rose-600',    'icon'=>'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
        ];

        ?>
        <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="stat-card section-card p-5 hover:shadow-lg transition group cursor-default">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?php echo e($kpi['gradient']); ?> flex items-center justify-center text-white shadow-md group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($kpi['icon']); ?>"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black mb-0.5"><?php echo e($kpi['value']); ?></p>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?php echo e($kpi['label']); ?></p>
            <p class="text-[10px] text-gray-400 mt-0.5"><?php echo e($kpi['delta']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="grid lg:grid-cols-3 gap-6 mb-6">

        
        <div class="lg:col-span-2 chart-card section-card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-black text-lg">Activity Timeline</h3>
                    <p class="text-xs text-gray-400">Last 30 days — connections, signups, messages</p>
                </div>
                <div class="flex gap-3 text-[10px] font-bold">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-primary-500 inline-block"></span>Connections</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-purple-500 inline-block"></span>Signups</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-teal-500 inline-block"></span>Messages</span>
                </div>
            </div>
            <canvas id="timelineChart" height="140"></canvas>
        </div>

        
        <div class="chart-card section-card p-6 flex flex-col">
            <h3 class="font-black text-lg mb-1">User Status</h3>
            <p class="text-xs text-gray-400 mb-5">Account approval breakdown</p>
            <div class="flex-1 flex items-center justify-center">
                <canvas id="statusChart" height="180"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-4">
                <?php
                $statusColors = ['approved'=>'bg-green-500','pending'=>'bg-yellow-400','suspended'=>'bg-red-400','rejected'=>'bg-gray-400'];
                ?>
                <?php $__currentLoopData = $userStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full <?php echo e($statusColors[$key]); ?> flex-shrink-0"></span>
                    <span class="text-[10px] text-gray-500 capitalize"><?php echo e($key); ?></span>
                    <span class="text-[10px] font-bold ml-auto"><?php echo e($val); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="grid lg:grid-cols-2 gap-6 mb-6">

        
        <div class="chart-card section-card p-6">
            <h3 class="font-black text-lg mb-1">Application Pipeline</h3>
            <p class="text-xs text-gray-400 mb-5">Funnel across all challenge stages</p>
            <?php
            $funnelTotal = max(array_sum($funnel), 1);
            $funnelDefs  = [
                'pending'     => ['Applied',       'from-gray-400 to-gray-500',    'text-gray-600'],
                'reviewing'   => ['Under Review',  'from-blue-400 to-blue-600',    'text-blue-600'],
                'shortlisted' => ['Shortlisted',   'from-green-400 to-green-600',  'text-green-600'],
                'interview'   => ['Interview',     'from-purple-400 to-purple-600','text-purple-600'],
                'rejected'    => ['Rejected',      'from-red-400 to-red-500',      'text-red-500'],
            ];
            ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $funnelDefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$label, $gradient, $textColor]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $pct = round(($funnel[$key] / $funnelTotal) * 100); ?>
                <div>
                    <div class="flex justify-between items-center text-sm mb-1.5">
                        <span class="font-semibold text-gray-700 dark:text-gray-300"><?php echo e($label); ?></span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black <?php echo e($textColor); ?>"><?php echo e($funnel[$key]); ?></span>
                            <span class="text-xs text-gray-400"><?php echo e($pct); ?>%</span>
                        </div>
                    </div>
                    <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r <?php echo e($gradient); ?> funnel-bar rounded-full shadow-sm"
                            style="width: <?php echo e($pct); ?>%"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="chart-card section-card p-6">
            <h3 class="font-black text-lg mb-1">Top Industries</h3>
            <p class="text-xs text-gray-400 mb-5">Startups and corporates per sector</p>
            <canvas id="industryChart" height="220"></canvas>
        </div>
    </div>

    
    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="chart-card section-card p-6">
            <h3 class="font-black text-lg mb-1">New Registrations</h3>
            <p class="text-xs text-gray-400 mb-5">Startups vs Corporates — last 7 days</p>
            <canvas id="usersChart" height="200"></canvas>
        </div>
        <div class="chart-card section-card p-6">
            <h3 class="font-black text-lg mb-1">Connections Growth</h3>
            <p class="text-xs text-gray-400 mb-5">New matches — last 7 days</p>
            <canvas id="connectionsChart" height="200"></canvas>
        </div>
    </div>

    
    <div class="grid lg:grid-cols-2 gap-6">

        
        <div class="section-card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-black text-lg">Recent Signups</h3>
                    <p class="text-xs text-gray-400">Latest registered users</p>
                </div>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="text-sm text-primary-600 hover:underline font-semibold">View all →</a>
            </div>
            <div class="space-y-2">
                <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition group">
                    <img src="<?php echo e($u->logoUrl()); ?>" class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate"><?php echo e($u->companyName()); ?></p>
                        <p class="text-[10px] text-gray-400"><?php echo e($u->email); ?> · <?php echo e($u->created_at->diffForHumans()); ?></p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold <?php echo e($u->isStartup() ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300'); ?>">
                            <?php echo e(ucfirst($u->role)); ?>

                        </span>
                        <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold
                            <?php echo e($u->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : ($u->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300')); ?>">
                            <?php echo e(ucfirst($u->status)); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="section-card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-black text-lg">Top Challenges</h3>
                    <p class="text-xs text-gray-400">By number of applications</p>
                </div>
            </div>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $topChallenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $maxApps = $topChallenges->first()->applications_count ?: 1; ?>
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs font-black text-gray-500 flex-shrink-0"><?php echo e($i+1); ?></span>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate"><?php echo e($ch->title); ?></p>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full mt-1 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full transition-all duration-700"
                                style="width: <?php echo e(round(($ch->applications_count / $maxApps) * 100)); ?>%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-black text-amber-600 dark:text-amber-400 flex-shrink-0"><?php echo e($ch->applications_count); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-400 text-center py-6">No challenges yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="section-card p-6 mt-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-black text-lg">Live Matching Log</h3>
                <p class="text-xs text-gray-400">Real-time interest signals and mutual match logging (Phase 3 connection logging)</p>
            </div>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-[10px] font-black uppercase tracking-wider">
                🛡️ Admin Auditor
            </span>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $recentMatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $u1 = $match->userOne;
                    $u2 = $match->userTwo;
                ?>
                <div class="glass-card rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-md flex flex-col justify-between group hover:border-primary-400 transition-all duration-300">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                            Connected Match
                        </span>
                        <span class="text-[9px] font-bold text-gray-400" title="<?php echo e($match->matched_at); ?>">
                            <?php echo e($match->matched_at->diffForHumans()); ?>

                        </span>
                    </div>

                    <div class="flex items-center justify-center gap-4 py-3">
                        <div class="text-center w-24">
                            <img src="<?php echo e($u1->logoUrl()); ?>" class="w-12 h-12 rounded-xl object-cover mx-auto ring-2 ring-white dark:ring-gray-900 shadow-md bg-white">
                            <p class="text-xs font-bold truncate mt-2 text-gray-900 dark:text-white"><?php echo e($u1->companyName()); ?></p>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 uppercase font-black tracking-wider">Startup</span>
                        </div>

                        <div class="flex flex-col items-center flex-shrink-0">
                            <span class="text-2xl animate-pulse">🤝</span>
                            <div class="h-0.5 w-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mt-2"></div>
                        </div>

                        <div class="text-center w-24">
                            <img src="<?php echo e($u2->logoUrl()); ?>" class="w-12 h-12 rounded-xl object-cover mx-auto ring-2 ring-white dark:ring-gray-900 shadow-md bg-white">
                            <p class="text-xs font-bold truncate mt-2 text-gray-900 dark:text-white"><?php echo e($u2->companyName()); ?></p>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 uppercase font-black tracking-wider">Corporate</span>
                        </div>
                    </div>

                    
                    <div class="border-t border-gray-100 dark:border-gray-800/60 pt-3 mt-4 flex items-center justify-between text-[9px] font-bold text-gray-400 uppercase tracking-wider">
                        <span>Ratings status:</span>
                        <div class="flex items-center gap-2">
                            <span class="<?php echo e(!is_null($match->rating_by_user_one) ? 'text-green-500' : 'text-gray-400'); ?>">
                                <?php echo e(!is_null($match->rating_by_user_one) ? '★ U1 Rated' : '☆ U1 Pending'); ?>

                            </span>
                            <span>•</span>
                            <span class="<?php echo e(!is_null($match->rating_by_user_two) ? 'text-green-500' : 'text-gray-400'); ?>">
                                <?php echo e(!is_null($match->rating_by_user_two) ? '★ U2 Rated' : '☆ U2 Pending'); ?>

                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-3 text-center py-12 text-gray-500 font-medium">
                    No matching connections logged yet on the platform.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const isDark     = document.documentElement.classList.contains('dark');
    const textColor  = isDark ? '#9ca3af' : '#6b7280';
    const gridColor  = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    Chart.defaults.color       = textColor;
    Chart.defaults.borderColor = gridColor;
    Chart.defaults.font.family = 'Inter, sans-serif';

    // ── 30-day Activity Timeline ──────────────────────────────────────────
    const tlLabels = <?php echo json_encode(collect($timeline)->pluck('date')); ?>;
    new Chart(document.getElementById('timelineChart'), {
        type: 'line',
        data: {
            labels: tlLabels,
            datasets: [
                {
                    label: 'Connections',
                    data: <?php echo json_encode(collect($timeline)->pluck('connections')); ?>,
                    borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.12)',
                    tension: 0.45, fill: true, pointRadius: 0, borderWidth: 2.5,
                },
                {
                    label: 'Signups',
                    data: <?php echo json_encode(collect($timeline)->pluck('signups')); ?>,
                    borderColor: '#a855f7', backgroundColor: 'rgba(168,85,247,0.08)',
                    tension: 0.45, fill: true, pointRadius: 0, borderWidth: 2,
                },
                {
                    label: 'Messages',
                    data: <?php echo json_encode(collect($timeline)->pluck('messages')); ?>,
                    borderColor: '#14b8a6', backgroundColor: 'rgba(20,184,166,0.08)',
                    tension: 0.45, fill: true, pointRadius: 0, borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true, interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 10 } } },
                y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } } },
            },
        },
    });

    // ── User Status Doughnut ─────────────────────────────────────────────
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Suspended', 'Rejected'],
            datasets: [{
                data: [<?php echo e($userStatus['approved']); ?>, <?php echo e($userStatus['pending']); ?>, <?php echo e($userStatus['suspended']); ?>, <?php echo e($userStatus['rejected']); ?>],
                backgroundColor: ['#22c55e','#eab308','#ef4444','#9ca3af'],
                borderWidth: 0, hoverOffset: 6,
            }],
        },
        options: {
            responsive: true, cutout: '72%',
            plugins: { legend: { display: false } },
        },
    });

    // ── Industry Chart ───────────────────────────────────────────────────
    new Chart(document.getElementById('industryChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($topIndustries->pluck('name')); ?>,
            datasets: [
                { label: 'Startups',   data: <?php echo json_encode($topIndustries->pluck('startups')); ?>, backgroundColor: '#6366f1', borderRadius: 6 },
                { label: 'Corporates', data: <?php echo json_encode($topIndustries->pluck('corporates')); ?>, backgroundColor: '#a855f7', borderRadius: 6 },
            ],
        },
        options: {
            responsive: true, indexAxis: 'y',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } } },
                y: { ticks: { font: { size: 10 } } },
            },
        },
    });

    // ── Registrations Bar ────────────────────────────────────────────────
    new Chart(document.getElementById('usersChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(collect($usersData)->pluck('date')); ?>,
            datasets: [
                { label: 'Startups',   data: <?php echo json_encode(collect($usersData)->pluck('startups')); ?>, backgroundColor: '#6366f1', borderRadius: 8 },
                { label: 'Corporates', data: <?php echo json_encode(collect($usersData)->pluck('corporates')); ?>, backgroundColor: '#a855f7', borderRadius: 8 },
            ],
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } },
        },
    });

    // ── Connections Area ─────────────────────────────────────────────────
    new Chart(document.getElementById('connectionsChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode(collect($connectionsData)->pluck('date')); ?>,
            datasets: [{
                label: 'Connections',
                data: <?php echo json_encode(collect($connectionsData)->pluck('connections')); ?>,
                borderColor: '#f43f5e',
                backgroundColor: 'rgba(244,63,94,0.15)',
                tension: 0.4, fill: true, borderWidth: 2.5,
                pointBackgroundColor: '#f43f5e', pointRadius: 4,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } },
        },
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>