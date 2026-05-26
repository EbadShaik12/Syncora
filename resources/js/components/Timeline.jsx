import React from 'react';
import { ArrowUpRight } from 'lucide-react';

export default function Timeline() {
  const stories = [
    {
      year: "2024",
      title: "Global Bank x FinEdge",
      desc: "Successfully integrated blockchain routing, reducing transaction costs by 40%.",
      gradient: "from-blue-500/20 to-cyan-500/20"
    },
    {
      year: "2025",
      title: "HealthCorp x MediSync",
      desc: "Deployed IoT monitoring across 50 hospitals, improving response times.",
      gradient: "from-emerald-500/20 to-teal-500/20"
    },
    {
      year: "2026",
      title: "LogisTech x AeroSpace",
      desc: "Piloted autonomous drone delivery network covering 100 sq miles.",
      gradient: "from-fuchsia-500/20 to-pink-500/20"
    }
  ];

  return (
    <section className="py-32">
      <div className="text-center mb-24 gsap-reveal">
        <div className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-pink-500/10 text-pink-600 dark:text-pink-400 font-black text-[10px] uppercase tracking-wider mb-4 border border-pink-500/20">
          Case Studies
        </div>
        <h2 className="text-4xl md:text-5xl font-black font-outfit text-slate-900 dark:text-white mb-4">Success Stories</h2>
        <p className="text-slate-500 dark:text-white/50 max-w-xl mx-auto font-semibold text-sm sm:text-base">Real enterprise partnerships forged on Syncora that transformed global industries.</p>
      </div>

      <div className="relative max-w-4xl mx-auto">
        {/* Center Line */}
        <div className="absolute left-4 md:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-indigo-500 via-purple-500 to-pink-500 -translate-x-1/2 shadow-[0_0_8px_rgba(168,85,247,0.5)]"></div>

        <div className="space-y-12">
          {stories.map((story, i) => (
            <div key={i} className={`gsap-reveal flex flex-col md:flex-row items-center justify-between gap-8 ${i % 2 === 0 ? 'md:flex-row-reverse' : ''}`}>
              
              {/* Spacer for alternating layout */}
              <div className="hidden md:block md:w-1/2"></div>
              
              {/* Center Dot */}
              <div className="absolute left-4 md:left-1/2 w-4.5 h-4.5 rounded-full bg-white dark:bg-[#090909] border-2 border-indigo-500 -translate-x-1/2 z-10 shadow-[0_0_15px_rgba(99,102,241,0.6)]"></div>
              
              {/* Content Card */}
              <a href="/login" className="w-full pl-12 md:pl-0 md:w-1/2 flex group">
                <div className={`w-full ${i % 2 === 0 ? 'md:mr-12' : 'md:ml-12'} p-8 rounded-[2rem] bg-white dark:bg-[#0d0d12] border border-slate-200/60 dark:border-white/5 hover:border-slate-300 dark:hover:border-white/20 transition-all duration-300 relative overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-1`}>
                  
                  <div className={`absolute inset-0 bg-gradient-to-br ${story.gradient} opacity-0 group-hover:opacity-100 transition-opacity duration-500`}></div>
                  
                  <div className="relative z-10 flex justify-between items-start mb-4">
                    <span className="text-indigo-500 dark:text-indigo-400 font-black text-sm tracking-wider">{story.year}</span>
                    <ArrowUpRight className="w-5 h-5 text-slate-400 dark:text-white/30 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors" />
                  </div>
                  
                  <h3 className="text-2xl font-bold font-outfit mb-3 relative z-10 text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{story.title}</h3>
                  <p className="text-slate-500 dark:text-white/50 leading-relaxed relative z-10 font-semibold text-sm sm:text-base">{story.desc}</p>
                </div>
              </a>
              
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
