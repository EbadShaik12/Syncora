<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Syncora') - {{ config('app.name') }}</title>

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
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(20px) saturate(150%); -webkit-backdrop-filter: blur(20px) saturate(150%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.04);
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
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 40px rgba(31, 38, 135, 0.06), inset 0 1px 0 rgba(255,255,255,0.9);
        }
        .dark .glass-card-strong {
            background: rgba(13, 13, 18, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 40px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.02);
            backdrop-filter: blur(24px);
        }

        /* ── Card Lift (clean B2B SaaS hover) ── */
        .card-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .card-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.08), 0 4px 12px -5px rgba(0, 0, 0, 0.03);
            border-color: #cbd5e1;
        }
        .dark .card-lift:hover {
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.5);
            border-color: #334155;
        }

        /* ── Gradient Text ── */
        .text-gradient {
            background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
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

        /* ═══════════════════════════════════════════════
           NAVBAR ACTIVE STYLES — applied to every page
           ═══════════════════════════════════════════════ */
        .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 6px 13px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            border-radius: 6px;
            white-space: nowrap;
            transition: color 0.15s ease;
            text-decoration: none;
        }
        .nav-link:hover { color: #1e293b; }
        .dark .nav-link { color: #94a3b8; }
        .dark .nav-link:hover { color: #f1f5f9; }

        /* Active = dark filled rounded block (like HOME in reference) */
        .nav-link.nav-link-active {
            background-color: #2e3a46 !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            border-radius: 6px !important;
            padding: 6px 14px !important;
            box-shadow: 0 1px 5px rgba(0,0,0,0.22) !important;
        }
        .dark .nav-link.nav-link-active {
            background-color: #3f4d5a !important;
            color: #ffffff !important;
        }

        /* Mobile nav links */
        .mobile-nav-link {
            display: block;
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #475569;
            border-radius: 8px;
            white-space: nowrap;
            transition: background 0.15s ease, color 0.15s ease;
            text-decoration: none;
        }
        .mobile-nav-link:hover { background-color: #f1f5f9; color: #0f172a; }
        .dark .mobile-nav-link { color: #94a3b8; }
        .dark .mobile-nav-link:hover { background-color: #1e293b; color: #f8fafc; }
        .mobile-nav-link.mobile-nav-link-active {
            background-color: #2e3a46 !important;
            color: #ffffff !important;
            font-weight: 800 !important;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-[#090909] dark:text-white min-h-screen flex flex-col overflow-x-hidden relative transition-colors duration-300">

<!-- ── Background Layers ── -->

<!-- ── Top progress bar ── -->
<div id="nprogress" style="width:0"></div>

<!-- ── Page wrapper (for transitions) ── -->
<div id="page-content" class="flex-1 flex flex-col relative z-10 w-full">

@auth
    @include('components.navbar')
@endauth

<main class="flex-1 w-full @auth pt-16 @endauth">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000); confetti({particleCount: 100, spread: 70, origin: { y: 0.6 }});" x-transition
            class="toast-animate fixed top-20 right-4 z-50 bg-green-500/90 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-green-400/50">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="toast-animate fixed top-20 right-4 z-50 bg-red-500/90 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-red-400/50">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @yield('content')
</main>

@auth
<!-- ── Premium Global Footer for Auth Users ── -->
<footer class="mt-auto border-t border-gray-200/60 dark:border-gray-800/60 bg-white/50 dark:bg-gray-900/50 backdrop-blur-md relative z-10 py-6 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full overflow-hidden relative bg-white flex items-center justify-center shadow-sm border border-slate-200/45 dark:border-white/10 flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-cover scale-[1.7] -translate-y-[15%] flex-shrink-0" alt="Syncora Icon">
            </div>
            <img src="{{ asset('images/logo-text.png') }}" class="h-5 w-auto object-contain dark:invert transition-all duration-300" alt="Syncora Text">
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">© {{ date('Y') }} Syncora. Built to accelerate innovation.</p>
        <div class="flex items-center gap-4 text-xs font-semibold text-gray-500 dark:text-gray-400">
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Help Center</a>
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Privacy</a>
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Terms</a>
        </div>
    </div>
</footer>
@endauth

</div><!-- /page-content -->

@stack('scripts')

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

    // ── Premium Unsaved Changes Modal and Protection System ──────────────────
    window.showUnsavedChangesModal = function(onConfirm, onCancel) {
        let modal = document.getElementById('unsaved-changes-modal');
        if (modal) modal.remove();

        modal = document.createElement('div');
        modal.id = 'unsaved-changes-modal';
        modal.className = 'fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0';
        
        modal.innerHTML = `
            <div class="glass-card-strong w-full max-w-md rounded-2xl overflow-hidden shadow-2xl border border-gray-200/80 dark:border-gray-800/80 transform scale-95 transition-all duration-300 opacity-0 bg-white/95 dark:bg-[#0d0d12]/95">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4 text-amber-500">
                        <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white font-outfit">Unsaved Changes</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        You have unsaved changes on this page. Leaving will discard all edits you've made. Are you sure you want to proceed?
                    </p>
                    <div class="flex gap-3 justify-end">
                        <button id="uc-modal-cancel" class="px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Cancel
                        </button>
                        <button id="uc-modal-confirm" class="px-4 py-2 text-sm font-semibold rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-md shadow-amber-500/20 transition">
                            Discard & Leave
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Force reflow
        modal.offsetWidth;

        modal.classList.remove('opacity-0');
        modal.querySelector('.glass-card-strong').classList.remove('scale-95', 'opacity-0');

        const close = (confirmed) => {
            modal.classList.add('opacity-0');
            modal.querySelector('.glass-card-strong').classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.remove();
                if (confirmed) {
                    onConfirm();
                } else {
                    onCancel();
                }
            }, 200);
        };

        modal.querySelector('#uc-modal-cancel').addEventListener('click', () => close(false));
        modal.querySelector('#uc-modal-confirm').addEventListener('click', () => close(true));
        modal.addEventListener('click', (e) => {
            if (e.target === modal) close(false);
        });
    };

    let trackedForms = [];

    function initFormTracking() {
        trackedForms = [];
        document.querySelectorAll('form[data-warn-unsaved]').forEach(form => {
            const serialize = () => {
                const formData = new FormData(form);
                const data = {};
                for (const [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        data[key] = `${value.name}-${value.size}`;
                    } else {
                        if (key.endsWith('[]')) {
                            if (!data[key]) data[key] = [];
                            data[key].push(value);
                        } else {
                            data[key] = value;
                        }
                    }
                }
                return JSON.stringify(data);
            };

            const initialState = serialize();
            let isSubmitted = false;

            form.addEventListener('submit', () => {
                isSubmitted = true;
            });

            trackedForms.push({
                isDirty: () => {
                    if (isSubmitted) return false;
                    return serialize() !== initialState;
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormTracking);
    } else {
        initFormTracking();
    }

    window.hasUnsavedChanges = function() {
        return trackedForms.some(f => f.isDirty());
    };

    window.addEventListener('beforeunload', function(e) {
        if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    document.addEventListener('click', function(e) {
        const a = e.target.closest('a[href]');
        if (!a) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('mailto') || a.target === '_blank') return;
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;
        
        e.preventDefault();

        const navigate = () => {
            if (page) {
                page.style.animation = 'pageOut 0.2s ease forwards';
                if (bar) { bar.style.opacity = '1'; bar.style.width = '30%'; }
                setTimeout(() => { window.location.href = href; }, 200);
            } else {
                window.location.href = href;
            }
        };

        if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
            window.showUnsavedChangesModal(navigate, () => {});
        } else {
            navigate();
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
