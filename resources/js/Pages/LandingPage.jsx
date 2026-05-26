import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Hero3D from '../components/Hero3D';
import Stats from '../components/Stats';
import Features from '../components/Features';
import StartupShowcase from '../components/StartupShowcase';
import Timeline from '../components/Timeline';
import { ArrowRight, Sparkles } from 'lucide-react';

gsap.registerPlugin(ScrollTrigger);

export default function LandingPage({ isAuthenticated, stats, featuredStartups }) {
  const containerRef = useRef(null);
  const [isDark, setIsDark] = React.useState(true);

  useEffect(() => {
    const checkDark = document.documentElement.classList.contains('dark');
    setIsDark(checkDark);
  }, []);

  const toggleTheme = () => {
    if (document.documentElement.classList.contains('dark')) {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('theme', 'light');
      setIsDark(false);
    } else {
      document.documentElement.classList.add('dark');
      localStorage.setItem('theme', 'dark');
      setIsDark(true);
    }
  };

  useEffect(() => {
    // Smooth scroll setup or global GSAP context
    let ctx = gsap.context(() => {
      // Global reveal animations
      gsap.utils.toArray('.gsap-reveal').forEach((elem) => {
        gsap.fromTo(elem, 
          { autoAlpha: 0, y: 50 },
          {
            autoAlpha: 1, 
            y: 0, 
            duration: 1, 
            ease: 'power3.out',
            scrollTrigger: {
              trigger: elem,
              start: 'top 85%',
            }
          }
        );
      });
    }, containerRef);
    
    return () => ctx.revert();
  }, []);

  return (
    <div ref={containerRef} className="relative bg-slate-50 text-slate-900 dark:bg-[#090909] dark:text-white min-h-screen font-sans selection:bg-purple-500/30 transition-colors duration-300">
      {/* Background ambient glows & cybernetic grid */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div className="absolute top-[-10%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-600/15 to-transparent blur-[140px]" />
        <div className="absolute bottom-[-10%] right-[-10%] w-[70vw] h-[70vw] rounded-full bg-gradient-to-bl from-pink-600/15 to-transparent blur-[160px]" />
        <div className="absolute top-[40%] left-[20%] w-[40vw] h-[40vw] rounded-full bg-gradient-to-br from-fuchsia-600/10 to-transparent blur-[120px] opacity-70 animate-float-slow" />
        
        {/* Fine Digital Cybernetic Grid lines */}
        <div className="absolute inset-0 bg-[linear-gradient(to_right,rgba(99,102,241,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(99,102,241,0.03)_1px,transparent_1px)] bg-[size:4rem_4rem] dark:bg-[linear-gradient(to_right,rgba(255,255,255,0.015)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.015)_1px,transparent_1px)]" />
      </div>

      {/* Navbar overlay */}
      <nav className="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-4 backdrop-blur-xl bg-white/80 dark:bg-[#090909]/85 border-b border-slate-200/50 dark:border-white/5 shadow-sm transition-all duration-300">
        <div className="flex items-center gap-2">
          <div className="w-8 h-8 rounded-full overflow-hidden relative bg-white flex items-center justify-center shadow-sm border border-slate-200/45 dark:border-white/10 flex-shrink-0">
            <img src="/images/logo.png" className="w-full h-full object-cover scale-[1.7] -translate-y-[15%] flex-shrink-0" alt="Syncora Icon" />
          </div>
          <img src="/images/logo-text.png" className="h-6 w-auto object-contain dark:invert transition-all duration-300 hidden sm:block" alt="Syncora Text" />
        </div>
        <div className="flex items-center gap-6 text-sm font-semibold text-slate-600 dark:text-white/70">
          {/* Theme Switcher Button */}
          <button onClick={toggleTheme} className="p-2 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-white transition-colors duration-300 hover:scale-105" aria-label="Toggle Theme">
            {isDark ? (
              <svg className="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
              </svg>
            ) : (
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
              </svg>
            )}
          </button>
          {isAuthenticated ? (
            <a href="/dashboard" className="px-6 py-2.5 rounded-full bg-gradient-to-r from-indigo-600 to-fuchsia-600 hover:from-indigo-500 hover:to-fuchsia-500 text-white font-bold transition-all duration-300 shadow-md hover:scale-105 flex items-center gap-2">
              Go to Dashboard <ArrowRight className="w-4 h-4" />
            </a>
          ) : (
            <>
              <a href="/login" className="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors relative py-1 group/nav">
                Sign In
                <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-fuchsia-500 group-hover:w-full transition-all duration-300"></span>
              </a>
              <a href="/register" className="px-6 py-2.5 rounded-full bg-slate-950 text-white dark:bg-white dark:text-black hover:bg-slate-800 dark:hover:bg-gray-150 transition-all duration-300 font-bold flex items-center gap-1.5 shadow-md hover:scale-105">
                Get Started <ArrowRight className="w-4 h-4" />
              </a>
            </>
          )}
        </div>
      </nav>

      {/* Main Content Sections */}
      <div className="relative z-10">
        <Hero3D isAuthenticated={isAuthenticated} />
        
        <Stats stats={stats} />
        
        <div className="max-w-7xl mx-auto px-6">
          <Features />
        </div>

        <StartupShowcase featuredStartups={featuredStartups} />
        
        <div className="max-w-7xl mx-auto px-6">
          <Timeline />
        </div>
      </div>

      {/* Footer */}
      <footer className="relative z-10 border-t border-slate-200/60 dark:border-white/10 py-12 mt-32 bg-white dark:bg-black transition-colors duration-300">
        <div className="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-slate-600 dark:text-white/70">
          <div className="flex items-center gap-2">
            <div className="w-6 h-6 rounded-full overflow-hidden relative bg-white flex items-center justify-center shadow-sm border border-slate-200/45 dark:border-white/10 flex-shrink-0">
              <img src="/images/logo.png" className="w-full h-full object-cover scale-[1.7] -translate-y-[15%] flex-shrink-0" alt="Syncora Icon" />
            </div>
            <img src="/images/logo-text.png" className="h-5 w-auto object-contain dark:invert transition-all duration-300" alt="Syncora Text" />
          </div>
          <p className="text-slate-400 dark:text-white/40 text-sm">© 2026 Syncora. Accelerating innovation.</p>
          <div className="flex gap-6 text-sm text-slate-400 dark:text-white/50">
            <a href="#" className="hover:text-slate-900 dark:hover:text-white transition">Twitter</a>
            <a href="#" className="hover:text-slate-900 dark:hover:text-white transition">LinkedIn</a>
            <a href="#" className="hover:text-slate-900 dark:hover:text-white transition">Privacy</a>
          </div>
        </div>
      </footer>
    </div>
  );
}
