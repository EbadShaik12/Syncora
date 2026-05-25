import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { TrendingUp, Users } from 'lucide-react';

gsap.registerPlugin(ScrollTrigger);

export default function StartupShowcase() {
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
  }, []);

  const startups = [
    { name: "Nexus AI", tag: "Artificial Intelligence", stage: "Series A", desc: "Predictive analytics for enterprise resource planning." },
    { name: "EcoFlow", tag: "CleanTech", stage: "Seed", desc: "Sustainable energy grid optimization software." },
    { name: "FinEdge", tag: "FinTech", stage: "Series B", desc: "Blockchain-powered cross-border payment routing." },
    { name: "MediSync", tag: "HealthTech", stage: "Pre-Seed", desc: "IoT integration for real-time patient monitoring." },
    { name: "AeroSpace", tag: "DeepTech", stage: "Series A", desc: "Autonomous drone delivery logistics." },
  ];

  return (
    <section ref={sectionRef} className="h-screen bg-[#050505] flex flex-col justify-center overflow-hidden border-y border-white/5 relative z-10">
      
      <div className="absolute top-20 left-10 md:left-20 z-20">
        <h2 className="text-4xl md:text-5xl font-black font-outfit text-white mb-2">Featured Innovators</h2>
        <p className="text-white/50 font-medium">Scroll to explore top tier startups</p>
      </div>

      <div ref={wrapperRef} className="flex gap-8 px-10 md:px-20 mt-20 w-fit">
        {startups.map((s, i) => (
          <div key={i} className="w-[350px] md:w-[450px] h-[400px] shrink-0 rounded-[2.5rem] bg-[#0d0d12] border border-white/5 p-8 flex flex-col relative overflow-hidden group">
            
            <div className="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <div className="flex justify-between items-start mb-6 relative z-10">
              <div className="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center font-black text-2xl font-outfit border border-white/10 group-hover:bg-indigo-500/20 transition-colors">
                {s.name.charAt(0)}
              </div>
              <span className="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-xs font-bold text-white/60 tracking-wider uppercase">
                {s.tag}
              </span>
            </div>
            
            <h3 className="text-3xl font-bold font-outfit mb-4 relative z-10">{s.name}</h3>
            <p className="text-white/50 text-lg leading-relaxed flex-1 relative z-10">{s.desc}</p>
            
            <div className="flex items-center justify-between pt-6 border-t border-white/10 relative z-10">
              <div className="flex items-center gap-2 text-white/60 text-sm font-medium">
                <TrendingUp className="w-4 h-4 text-emerald-400" /> {s.stage}
              </div>
              <button className="px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white text-sm font-bold transition-colors">
                View Profile
              </button>
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}
