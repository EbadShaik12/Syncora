<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($user->companyName()); ?> - Startup Profile</title>
    <style>
        * { font-family: 'Helvetica', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 30px; color: #1f2937; font-size: 11pt; line-height: 1.5; }
        h1 { font-size: 24pt; color: #4f46e5; margin: 0 0 8px; }
        h2 { font-size: 14pt; color: #4f46e5; margin: 24px 0 8px; padding-bottom: 4px; border-bottom: 2px solid #e0e7ff; }
        h3 { font-size: 12pt; margin: 12px 0 4px; }
        .header { padding-bottom: 16px; border-bottom: 3px solid #4f46e5; margin-bottom: 16px; }
        .meta { color: #6b7280; font-size: 10pt; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 9pt; background: #e0e7ff; color: #3730a3; margin-right: 6px; }
        .tag { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 9pt; background: #f3f4f6; color: #374151; margin: 2px; }
        .info-row { padding: 6px 0; border-bottom: 1px solid #f3f4f6; }
        .info-label { color: #6b7280; font-weight: 600; display: inline-block; width: 140px; }
        .score-box { background: linear-gradient(135deg, #6366f1, #a855f7); color: white; padding: 16px; border-radius: 12px; margin: 12px 0; text-align: center; }
        .score-num { font-size: 36pt; font-weight: bold; }
        .milestone { padding: 10px 0; border-left: 3px solid #6366f1; padding-left: 12px; margin-bottom: 8px; }
        .milestone-date { color: #6b7280; font-size: 9pt; }
        .pitch { padding: 12px; background: #f9fafb; border-left: 4px solid #6366f1; border-radius: 4px; margin: 8px 0; }
        .footer { margin-top: 30px; padding-top: 12px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 9pt; }
    </style>
</head>
<body>
    <?php $profile = $user->startupProfile; ?>

    <div class="header">
        <h1><?php echo e($user->companyName()); ?></h1>
        <div class="meta">
            <?php if($profile?->industry): ?> <?php echo e($profile->industry->name); ?> <?php endif; ?>
            <?php if($profile?->city): ?> • <?php echo e($profile->city); ?><?php echo e($profile->state ? ', '.$profile->state : ''); ?> <?php endif; ?>
        </div>
        <div style="margin-top:8px;">
            <?php if($profile): ?>
                <span class="badge"><?php echo e($profile->stage === 'idea' ? 'Idea' : ($profile->stage === 'mvp' ? 'MVP' : ($profile->stage === 'growth' ? 'Growth' : 'Scaling'))); ?> Stage</span>
                <?php if($profile->team_size): ?><span class="badge"><?php echo e($profile->team_size); ?> team members</span><?php endif; ?>
                <?php if($profile->founded_year): ?><span class="badge">Founded <?php echo e($profile->founded_year); ?></span><?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if($compatibility): ?>
        <div class="score-box">
            <div style="font-size:9pt; opacity:0.9; text-transform:uppercase; letter-spacing:1px;">AI Compatibility Score</div>
            <div class="score-num"><?php echo e($compatibility['score']); ?>/100</div>
            <div style="font-size:11pt; opacity:0.95;"><?php echo e($compatibility['label']); ?></div>
        </div>
    <?php endif; ?>

    <?php if($profile?->elevator_pitch): ?>
        <h2>Elevator Pitch</h2>
        <div class="pitch"><?php echo e($profile->elevator_pitch); ?></div>
    <?php endif; ?>

    <h2>Company Details</h2>
    <?php if($profile): ?>
        <div class="info-row"><span class="info-label">Industry:</span> <?php echo e($profile->industry?->name ?? 'N/A'); ?></div>
        <div class="info-row"><span class="info-label">Stage:</span> <?php echo e(ucfirst($profile->stage)); ?></div>
        <?php if($profile->team_size): ?><div class="info-row"><span class="info-label">Team Size:</span> <?php echo e($profile->team_size); ?></div><?php endif; ?>
        <?php if($profile->founded_year): ?><div class="info-row"><span class="info-label">Founded:</span> <?php echo e($profile->founded_year); ?></div><?php endif; ?>
        <div class="info-row"><span class="info-label">Funding Status:</span> <?php echo e(ucwords(str_replace('_', ' ', $profile->funding_status))); ?></div>
        <?php if($profile->funding_amount > 0): ?><div class="info-row"><span class="info-label">Funding Raised:</span> ₹<?php echo e(number_format($profile->funding_amount)); ?></div><?php endif; ?>
        <?php if($profile->budget_min || $profile->budget_max): ?><div class="info-row"><span class="info-label">Budget Range:</span> ₹<?php echo e(number_format($profile->budget_min)); ?> - ₹<?php echo e(number_format($profile->budget_max)); ?></div><?php endif; ?>
        <?php if($profile->city): ?><div class="info-row"><span class="info-label">Location:</span> <?php echo e($profile->city); ?><?php echo e($profile->state ? ', '.$profile->state : ''); ?>, <?php echo e($profile->country); ?></div><?php endif; ?>
        <?php if($profile->website): ?><div class="info-row"><span class="info-label">Website:</span> <?php echo e($profile->website); ?></div><?php endif; ?>
    <?php endif; ?>

    <?php if($profile?->tech_tags && count($profile->tech_tags)): ?>
        <h2>Technologies</h2>
        <div>
            <?php $__currentLoopData = $profile->tech_tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="tag"><?php echo e($tag); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <?php if($profile?->seeking && count($profile->seeking)): ?>
        <h2>Looking For</h2>
        <div>
            <?php $__currentLoopData = $profile->seeking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="tag"><?php echo e(ucwords(str_replace('_', ' ', $s))); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <?php if($profile?->milestones && $profile->milestones->count()): ?>
        <h2>Startup Journey</h2>
        <?php $__currentLoopData = $profile->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="milestone">
                <div class="milestone-date"><?php echo e($milestone->milestone_date->format('M Y')); ?></div>
                <h3 style="margin:2px 0;"><?php echo e($milestone->title); ?></h3>
                <?php if($milestone->description): ?><div style="font-size:10pt; color:#4b5563;"><?php echo e($milestone->description); ?></div><?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <?php if($user->badges->count()): ?>
        <h2>Achievements</h2>
        <div>
            <?php $__currentLoopData = $user->badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge" style="background:#fef3c7; color:#92400e;">🏆 <?php echo e($badge->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <div class="footer">
        Generated by StartupConnect • <?php echo e(now()->format('M d, Y')); ?>

    </div>
</body>
</html>
<?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/pdf/startup-report.blade.php ENDPATH**/ ?>