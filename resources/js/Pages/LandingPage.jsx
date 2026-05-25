import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Hero3D from '../components/Hero3D';
import Features from '../components/Features';
import StartupShowcase from '../components/StartupShowcase';
import Timeline from '../components/Timeline';
import { ArrowRight, Sparkles } from 'lucide-react';

gsap.registerPlugin(ScrollTrigger);

export default function LandingPage() {
  const containerRef = useRef(null);

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
    <div ref={containerRef} className="relative bg-[#090909] text-white min-h-screen font-sans selection:bg-purple-500/30">
      {/* Background ambient glows */}
      <div className="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div className="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px]" />
        <div className="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] rounded-full bg-fuchsia-600/10 blur-[150px]" />
      </div>

      {/* Navbar overlay */}
      <nav className="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-6 backdrop-blur-md bg-[#090909]/40 border-b border-white/5">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
            <Sparkles className="w-5 h-5 text-white" />
          </div>
          <span className="font-outfit font-bold text-xl tracking-wide">StartupConnect</span>
        </div>
        <div className="flex items-center gap-6 text-sm font-medium text-white/70">
          <a href="/login" className="hover:text-white transition-colors">Sign In</a>
          <a href="/register" className="px-5 py-2.5 rounded-full bg-white text-black hover:bg-gray-200 transition-colors font-semibold flex items-center gap-2">
            Get Started <ArrowRight className="w-4 h-4" />
          </a>
        </div>
      </nav>

      {/* Main Content Sections */}
      <div className="relative z-10">
        <Hero3D />
        
        <div className="max-w-7xl mx-auto px-6">
          <Features />
        </div>

        <StartupShowcase />
        
        <div className="max-w-7xl mx-auto px-6">
          <Timeline />
        </div>
      </div>

      {/* Footer */}
      <footer className="relative z-10 border-t border-white/10 py-12 mt-32 bg-black">
        <div className="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
          <div className="flex items-center gap-3">
            <Sparkles className="w-5 h-5 text-indigo-500" />
            <span className="font-outfit font-semibold tracking-wide">StartupConnect</span>
          </div>
          <p className="text-white/40 text-sm">© 2026 StartupConnect. Accelerating innovation.</p>
          <div className="flex gap-6 text-sm text-white/50">
            <a href="#" className="hover:text-white transition">Twitter</a>
            <a href="#" className="hover:text-white transition">LinkedIn</a>
            <a href="#" className="hover:text-white transition">Privacy</a>
          </div>
        </div>
      </footer>
    </div>
  );
}
