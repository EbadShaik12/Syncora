import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { TrendingUp, Users } from 'lucide-react';

gsap.registerPlugin(ScrollTrigger);

export default function StartupShowcase({ featuredStartups }) {
  const sectionRef = useRef(null);
  const wrapperRef = useRef(null);

  useEffect(() => {
    let ctx = gsap.context(() => {
      const wrapper = wrapperRef.current;
      const amountToScroll = wrapper.scrollWidth - window.innerWidth;

      gsap.to(wrapper, {
        x: -amountToScroll,
        ease: "none",
        scrollTrigger: {
          trigger: sectionRef.current,
          start: "top top",
          end: `+=${amountToScroll}`,
          pin: true,
          scrub: 1,
        }
      });
    }, sectionRef);

    return () => ctx.revert();
  }, [featuredStartups]); // Re-run scroll trigger calculation if dynamic items load

  const dbStartups = (featuredStartups || []).map(u => ({
    name: u.startup_profile?.company_name || u.name,
    tag: u.startup_profile?.industry?.name || 'Technology',
    stage: u.startup_profile?.stage === 'mvp' ? 'MVP Stage' : (u.startup_profile?.stage === 'growth' ? 'Growth Stage' : 'Scaling Stage'),
    desc: u.startup_profile?.elevator_pitch || 'Innovative startup accelerated on the Syncora B2B partnership network.',
  }));

  const startups = dbStartups.length > 0 ? dbStartups : [
    { name: "NeuralCart AI", tag: "Artificial Intelligence", stage: "MVP Stage", desc: "Real-time deep learning behavioral product recommendation engine for global e-commerce systems." },
    { name: "MediSync", tag: "HealthTech", stage: "Growth Stage", desc: "Cloud-based hospital management platform that simplifies clinical workflows and patient scheduling systems." },
    { name: "FinFlow", tag: "FinTech", stage: "MVP Stage", desc: "Embedded banking API layer allowing high-growth software startups to deploy digital accounts in days." },
    { name: "GreenHarvest", tag: "AgriTech", stage: "Growth Stage", desc: "IoT precision agriculture sensors and crop health analysis powered by local weather data models." },
    { name: "SecureNet", tag: "Cybersecurity", stage: "Growth Stage", desc: "Enterprise zero-trust defense shielding digital assets from zero-day threats autonomously." },
  ];

  return (
    <section ref={sectionRef} className="h-screen bg-slate-100 dark:bg-[#050505] flex flex-col justify-center overflow-hidden border-y border-slate-200/50 dark:border-white/5 relative z-10 transition-colors duration-300">
      
      <div className="absolute top-20 left-10 md:left-20 z-20">
        <div className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-wider mb-2 border border-indigo-500/20">
          Global Pool
        </div>
        <h2 className="text-4xl md:text-5xl font-black font-outfit text-slate-900 dark:text-white mb-2">Featured Innovators</h2>
        <p className="text-slate-500 dark:text-white/50 font-semibold text-sm sm:text-base">Scroll down to explore active high-growth enterprises</p>
      </div>

      <div ref={wrapperRef} className="flex gap-8 px-10 md:px-20 mt-20 w-fit">
        {startups.map((s, i) => (
          <div key={i} className="w-[350px] md:w-[450px] h-[400px] shrink-0 rounded-[2.5rem] bg-white dark:bg-[#0d0d12] border border-slate-200/60 dark:border-white/5 p-8 flex flex-col relative overflow-hidden group shadow-md hover:shadow-2xl transition-all duration-300">
            
            <div className="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <div className="flex justify-between items-start mb-6 relative z-10">
              <div className="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-indigo-500/10 to-fuchsia-500/10 flex items-center justify-center font-black text-2xl font-outfit border border-slate-200/60 dark:border-white/5 group-hover:from-indigo-500/20 group-hover:to-fuchsia-500/20 text-slate-800 dark:text-white transition-colors duration-500">
                {s.name.charAt(0)}
              </div>
              <span className="px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-500 dark:text-white/60 tracking-wider uppercase">
                {s.tag}
              </span>
            </div>
            
            <h3 className="text-3xl font-bold font-outfit mb-4 relative z-10 text-slate-900 dark:text-white">{s.name}</h3>
            <p className="text-slate-500 dark:text-white/50 text-base leading-relaxed flex-1 relative z-10 font-semibold">{s.desc}</p>
            
            <div className="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-white/10 relative z-10">
              <div className="flex items-center gap-2 text-slate-500 dark:text-white/60 text-sm font-semibold">
                <TrendingUp className="w-4 h-4 text-emerald-400 animate-pulse" /> {s.stage}
              </div>
              <a href="/login" className="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white dark:bg-white dark:hover:bg-slate-150 dark:text-slate-950 text-xs font-black transition-all shadow-md shadow-indigo-500/20 dark:shadow-none hover:scale-[1.05] flex items-center justify-center">
                View Profile
              </a>
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}
