<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'StartupConnect'); ?> - <?php echo e(config('app.name')); ?></title>

    <!-- Tailwind via CDN (Pinned to v3 to support JS config) -->
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        outfit: ['Outfit', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81',
                        },
                        dark: {
                            900: '#030014', /* Deep premium dark */
                            800: '#0f0a2a',
                            700: '#1a103c',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                        'bounce-in': 'bounceIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                        'float-slow': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 8s linear infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                        slideUp: { '0%': { transform: 'translateY(20px)', opacity: 0 }, '100%': { transform: 'translateY(0)', opacity: 1 } },
                        bounceIn: { '0%': { transform: 'scale(0.3)', opacity: 0 }, '50%': { transform: 'scale(1.05)' }, '70%': { transform: 'scale(0.9)' }, '100%': { transform: 'scale(1)', opacity: 1 } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        pulseGlow: { '0%, 100%': { opacity: 1, boxShadow: '0 0 0 0 rgba(99,102,241, 0.4)' }, '50%': { opacity: .8, boxShadow: '0 0 0 10px rgba(99,102,241, 0)' } },
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-outfit { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* ── Premium Backgrounds ── */
        html.dark body { background-color: #090909 !important; }
        .bg-radial-gradient { background-image: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 40%), radial-gradient(circle at bottom left, rgba(236, 72, 153, 0.15), transparent 40%); }
        .dark .bg-radial-gradient { background-color: #090909; background-image: radial-gradient(circle at top right, rgba(99, 102, 241, 0.10), transparent 40%), radial-gradient(circle at bottom left, rgba(236, 72, 153, 0.10), transparent 40%); }
        .gradient-bg { background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); }
        .glass { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        
        /* ── Scrollbars ── */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #6366f1, #a855f7); border-radius: 99px; border: 2px solid transparent; background-clip: content-box; }
        .dark ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #4f46e5, #9333ea); border: 2px solid transparent; background-clip: content-box; }

        /* ── Page Transition ── */
        @keyframes pageIn  { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        @keyframes pageOut { from{opacity:1;transform:scale(1)} to{opacity:0;transform:scale(0.98)} }
        .page-transition-in  { animation: pageIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .page-transition-out { animation: pageOut 0.2s ease forwards; }

        /* ── Top Progress Bar ── */
        #nprogress { position:fixed; top:0; left:0; right:0; z-index:9999; height:3px;
            background: linear-gradient(90deg,#6366f1,#a855f7,#ec4899);
            transition: width 0.3s ease; border-radius:0 2px 2px 0; box-shadow: 0 0 10px rgba(168, 85, 247, 0.6); }

        /* ── Skeleton Shimmer ── */
        @keyframes shimmer {
            0%   { background-position: -600px 0; }
            100% { background-position: 600px 0; }
        }
        .skeleton {
            background: linear-gradient(90deg,
                rgba(156,163,175,0.15) 25%,
                rgba(156,163,175,0.35) 37%,
                rgba(156,163,175,0.15) 63%);
            background-size: 600px 100%;
            animation: shimmer 1.4s ease infinite;
            border-radius: 0.5rem;
        }
        .dark .skeleton {
            background: linear-gradient(90deg,
                rgba(75,85,99,0.2) 25%,
                rgba(75,85,99,0.45) 37%,
                rgba(75,85,99,0.2) 63%);
            background-size: 600px 100%;
            animation: shimmer 1.4s ease infinite;
        }
        .skeleton-text  { height: 1rem; border-radius: 0.375rem; margin-bottom: 0.5rem; }
        .skeleton-title { height: 1.5rem; border-radius: 0.375rem; margin-bottom: 0.75rem; }
        .skeleton-avatar{ border-radius: 9999px; }
        .skeleton-card  { height: 160px; border-radius: 1rem; }

        /* ── Stagger Reveal ── */
        .reveal { opacity:0; transform:translateY(30px); transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.visible { opacity:1; transform:translateY(0); }
        .reveal-delay-1 { transition-delay: 0.08s; }
        .reveal-delay-2 { transition-delay: 0.16s; }
        .reveal-delay-3 { transition-delay: 0.24s; }
        .reveal-delay-4 { transition-delay: 0.32s; }

        /* ── Premium Design Utilities ── */
        .text-glow { text-shadow: 0 0 20px rgba(168, 85, 247, 0.5); }
        .border-glow { box-shadow: 0 0 15px rgba(99, 102, 241, 0.3); border: 1px solid rgba(99, 102, 241, 0.5); }
        .dark .border-glow { box-shadow: 0 0 20px rgba(99, 102, 241, 0.15); border-color: rgba(99, 102, 241, 0.2); }
        
        .glass-card {
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(20px) saturate(150%); -webkit-backdrop-filter: blur(20px) saturate(150%);
            border: 1px solid rgba(255,255,255,0.4);
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
        }
        .dark .glass-card {
            background: rgba(13, 13, 18, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            backdrop-filter: blur(16px);
        }
        .glass-card-strong {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(24px) saturate(180%); -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 10px 40px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.8);
        }
        .dark .glass-card-strong {
            background: rgba(13, 13, 18, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 40px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.02);
            backdrop-filter: blur(24px);
        }

        /* ── Glow Shadows ── */
        .glow-purple { box-shadow: 0 0 20px rgba(139,92,246,0.3), 0 8px 32px rgba(139,92,246,0.15); }
        .glow-blue   { box-shadow: 0 0 20px rgba(59,130,246,0.3), 0 8px 32px rgba(59,130,246,0.15); }
        .glow-pink   { box-shadow: 0 0 20px rgba(236,72,153,0.3), 0 8px 32px rgba(236,72,153,0.15); }
        .glow-green  { box-shadow: 0 0 20px rgba(16,185,129,0.3), 0 8px 32px rgba(16,185,129,0.15); }
        .glow-orange { box-shadow: 0 0 20px rgba(245,158,11,0.3), 0 8px 32px rgba(245,158,11,0.15); }

        /* ── Card Lift (premium hover) ── */
        .card-lift {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        border-color 0.3s ease;
        }
        .card-lift:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 24px 60px rgba(99,102,241,0.15), 0 12px 24px rgba(0,0,0,0.08);
            border-color: rgba(99,102,241,0.4);
        }
        .dark .card-lift:hover {
            box-shadow: 0 24px 60px rgba(168, 85, 247, 0.12), 0 12px 24px rgba(0,0,0,0.3);
            border-color: rgba(168, 85, 247, 0.4);
        }

        /* ── Gradient Text ── */
        .text-gradient {
            background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Magnetic Buttons ── */
        .magnetic {
            transition: transform 0.2s cubic-bezier(0.2, 0, 0.2, 1);
            will-change: transform;
        }

        /* ── Shimmer Button ── */
        @keyframes btnShimmer { 0% { left: -100%; } 100% { left: 100%; } }
        .shimmer-btn { position: relative; overflow: hidden; }
        .shimmer-btn::after {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: none; transform: skewX(-20deg);
        }
        .shimmer-btn:hover::after { animation: btnShimmer 0.7s ease; }

        /* ── 3D Tilt Card Effect ── */
        .tilt-card {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
            transform-style: preserve-3d;
            will-change: transform;
        }
        .tilt-card:hover {
            transform: perspective(1000px) rotateX(var(--tilt-x, 0deg)) rotateY(var(--tilt-y, 0deg)) translateY(-6px);
        }

        /* ── Floating Blobs (animated background elements) ── */
        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            25%     { transform: translate(30px,-40px) scale(1.05); }
            50%     { transform: translate(-20px,-60px) scale(0.95); }
            75%     { transform: translate(40px,-20px) scale(1.02); }
        }
        .blob {
            position: absolute; border-radius: 50%;
            filter: blur(60px); opacity: 0.4;
            animation: blobFloat 20s ease-in-out infinite;
            pointer-events: none;
        }
        .dark .blob { opacity: 0.25; filter: blur(80px); }

        /* ── Animated Mesh Gradient ── */
        @keyframes meshMove {
            0%,100% { background-position: 0% 50%; }
            50%     { background-position: 100% 50%; }
        }
        .mesh-gradient {
            background: linear-gradient(-45deg, #0a0520, #2d1b69, #5b21b6, #9d174d, #4c1d95, #1e1b4b, #0a0520);
            background-size: 300% 300%;
            animation: meshMove 15s ease infinite;
        }
        .mesh-gradient-light {
            background: linear-gradient(-45deg, #eef2ff, #e0e7ff, #c4b5fd, #f0abfc, #fbcfe8, #e0e7ff, #eef2ff);
            background-size: 300% 300%;
            animation: meshMove 15s ease infinite;
        }

        /* ── Noise Texture ── */
        .noise::before {
            content:''; position:absolute; inset:0; z-index:1;
            opacity:0.04; pointer-events:none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }

        /* ── Tooltips ── */
        [data-tooltip] { position: relative; cursor: pointer; }
        [data-tooltip]::before {
            content: attr(data-tooltip);
            position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%) translateY(-8px);
            padding: 4px 8px; border-radius: 6px; background: rgba(17, 24, 39, 0.9);
            color: white; font-size: 11px; white-space: nowrap; font-weight: 500;
            opacity: 0; visibility: hidden; transition: all 0.2s ease; pointer-events: none;
            backdrop-filter: blur(4px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 50;
        }
        [data-tooltip]::after {
            content: ''; position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%) translateY(0);
            border: 4px solid transparent; border-top-color: rgba(17, 24, 39, 0.9);
            opacity: 0; visibility: hidden; transition: all 0.2s ease; pointer-events: none; z-index: 50;
        }
        [data-tooltip]:hover::before { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(-4px); }
        [data-tooltip]:hover::after  { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(4px); }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-[#090909] text-white min-h-screen flex flex-col overflow-x-hidden relative">

<!-- ── Background Layers ── -->
<div id="cursor-glow" class="pointer-events-none fixed inset-0 z-0 transition-opacity duration-300 opacity-0 dark:opacity-40" style="background: radial-gradient(600px circle at 0px 0px, rgba(99, 102, 241, 0.08), transparent 40%);"></div>
<canvas id="particle-canvas" class="fixed inset-0 pointer-events-none z-0 opacity-30 dark:opacity-50"></canvas>

<!-- ── Top progress bar ── -->
<div id="nprogress" style="width:0"></div>

<!-- ── Page wrapper (for transitions) ── -->
<div id="page-content" class="flex-1 flex flex-col relative z-10 w-full">

<?php if(auth()->guard()->check()): ?>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<main class="flex-1 w-full <?php if(auth()->guard()->check()): ?> pt-16 <?php endif; ?>">
    <?php if(session('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000); confetti({particleCount: 100, spread: 70, origin: { y: 0.6 }});" x-transition
            class="toast-animate fixed top-20 right-4 z-50 bg-green-500/90 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-green-400/50">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="toast-animate fixed top-20 right-4 z-50 bg-red-500/90 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-red-400/50">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<?php if(auth()->guard()->check()): ?>
<!-- ── Premium Global Footer for Auth Users ── -->
<footer class="mt-auto border-t border-gray-200/60 dark:border-gray-800/60 bg-white/50 dark:bg-gray-900/50 backdrop-blur-md relative z-10 py-6 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center shadow-md">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="font-bold text-sm bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent font-outfit">StartupConnect</span>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">© <?php echo e(date('Y')); ?> StartupConnect. Built to accelerate innovation.</p>
        <div class="flex items-center gap-4 text-xs font-semibold text-gray-500 dark:text-gray-400">
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Help Center</a>
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Privacy</a>
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Terms</a>
        </div>
    </div>
</footer>
<?php endif; ?>

</div><!-- /page-content -->

<?php echo $__env->yieldPushContent('scripts'); ?>

<script>
// ── Page transition + progress bar ──────────────────────────────────────
(function() {
    const bar = document.getElementById('nprogress');
    const page = document.getElementById('page-content');

    if (page) page.classList.add('page-transition-in');

    let width = 0;
    function tick() {
        width = width < 85 ? width + (85 - width) * 0.08 : width;
        if (bar) bar.style.width = width + '%';
        if (width < 85) requestAnimationFrame(tick);
    }
    tick();
    window.addEventListener('load', () => {
        if (bar) { bar.style.width = '100%'; setTimeout(() => bar.style.opacity = '0', 300); }
    });

    document.addEventListener('click', function(e) {
        const a = e.target.closest('a[href]');
        if (!a) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('mailto') || a.target === '_blank') return;
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;
        e.preventDefault();
        if (page) {
            page.style.animation = 'pageOut 0.2s ease forwards';
            if (bar) { bar.style.opacity = '1'; bar.style.width = '30%'; }
            setTimeout(() => { window.location.href = href; }, 200);
        } else {
            window.location.href = href;
        }
    });

    document.addEventListener('submit', function() {
        if (bar) { bar.style.opacity = '1'; bar.style.width = '60%'; }
    });
})();

// ── Scroll reveal stagger ───────────────────────────────────────────────
(function() {
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { 
            if (e.isIntersecting) { 
                e.target.classList.add('visible'); 
                obs.unobserve(e.target); 
            } 
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    
    // Add delays automatically to children of stagger containers
    document.querySelectorAll('.stagger-container').forEach(container => {
        container.querySelectorAll('.reveal').forEach((el, i) => {
            el.style.transitionDelay = `${i * 0.1}s`;
        });
    });
    
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
})();

// ── 3D Tilt cards ──────────────────────────────────────────────────────
document.querySelectorAll('.tilt-card').forEach(card => {
    card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        const x = (e.clientY - r.top - r.height/2) / r.height * -10;
        const y = (e.clientX - r.left - r.width/2) / r.width * 10;
        card.style.setProperty('--tilt-x', x+'deg');
        card.style.setProperty('--tilt-y', y+'deg');
    });
    card.addEventListener('mouseleave', () => {
        card.style.setProperty('--tilt-x', '0deg');
        card.style.setProperty('--tilt-y', '0deg');
    });
});

// ── Magnetic Buttons ───────────────────────────────────────────────────
document.querySelectorAll('.magnetic').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
    });
    btn.addEventListener('mouseleave', () => {
        btn.style.transform = 'translate(0px, 0px)';
    });
});

// ── Cursor Glow Effect ─────────────────────────────────────────────────
(function() {
    const cursorGlow = document.getElementById('cursor-glow');
    if (!cursorGlow) return;
    
    window.addEventListener('mousemove', (e) => {
        const x = e.clientX;
        const y = e.clientY;
        cursorGlow.style.background = `radial-gradient(600px circle at ${x}px ${y}px, rgba(99, 102, 241, 0.08), transparent 40%)`;
        cursorGlow.style.opacity = '1';
    });
})();

// ── Animated Particle Canvas ───────────────────────────────────────────
(function() {
    const canvas = document.getElementById('particle-canvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];
    
    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }
    
    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.size = Math.random() * 1.5 + 0.5;
            this.speedX = Math.random() * 0.5 - 0.25;
            this.speedY = Math.random() * 0.5 - 0.25;
            this.opacity = Math.random() * 0.5 + 0.1;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            if (this.x < 0 || this.x > width) this.speedX *= -1;
            if (this.y < 0 || this.y > height) this.speedY *= -1;
        }
        draw() {
            ctx.fillStyle = document.documentElement.classList.contains('dark') 
                ? `rgba(255, 255, 255, ${this.opacity})` 
                : `rgba(99, 102, 241, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    
    function init() {
        resize();
        particles = [];
        const particleCount = Math.min(Math.floor(window.innerWidth / 15), 100);
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }
    
    function animate() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => {
            p.update();
            p.draw();
        });
        requestAnimationFrame(animate);
    }
    
    window.addEventListener('resize', () => {
        resize();
        init();
    });
    
    init();
    animate();
})();

// ── Count-up animation ─────────────────────────────────────────────────
function animateValue(el) {
    const target = parseInt(el.dataset.count || el.textContent.replace(/,/g, ''), 10);
    if (isNaN(target) || el.dataset.animated) return;
    el.dataset.animated = '1';
    const duration = 1500;
    const start = performance.now();
    function tick(now) {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 4); // Quartic ease out
        el.textContent = Math.floor(target * eased).toLocaleString();
        if (progress < 1) requestAnimationFrame(tick);
        else el.textContent = target.toLocaleString();
    }
    requestAnimationFrame(tick);
}
const countObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { animateValue(e.target); countObs.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('[data-count]').forEach(el => countObs.observe(el));
</script>

</body>
</html>
<?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/layouts/app.blade.php ENDPATH**/ ?>