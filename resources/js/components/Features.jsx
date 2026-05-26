import React, { useRef } from 'react';
import { Rocket, Target, Handshake, TrendingUp } from 'lucide-react';

export default function Features() {
  const features = [
    {
      title: "Discover Innovation",
      desc: "Swipe through a curated feed of high-potential startups matching your strategic goals.",
      icon: <Rocket className="w-6 h-6 text-indigo-400" />
    },
    {
      title: "Post Challenges",
      desc: "Corporates can broadcast specific problem statements to source targeted solutions.",
      icon: <Target className="w-6 h-6 text-fuchsia-400" />
    },
    {
      title: "Seamless Matching",
      desc: "AI-driven compatibility scoring ensures you connect with the right partners.",
      icon: <Handshake className="w-6 h-6 text-pink-400" />
    },
    {
      title: "Accelerate Growth",
      desc: "Track milestones, manage applications, and measure your partnership success.",
      icon: <TrendingUp className="w-6 h-6 text-emerald-400" />
    }
  ];

  return (
    <section className="py-32 relative">
      <div className="text-center mb-24 gsap-reveal">
        <div className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-wider mb-4 border border-indigo-500/20">
          Core Engine
        </div>
        <h2 className="text-4xl md:text-5xl font-black font-outfit mb-4">Ecosystem Capabilities</h2>
        <p className="text-slate-500 dark:text-white/50 max-w-xl mx-auto font-medium text-base">Everything you need to discover, scale, and manage powerful multi-tenant B2B relationships.</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
        {features.map((f, i) => (
          <div key={i} className="gsap-reveal group relative p-[1px] rounded-3xl overflow-hidden bg-gradient-to-br from-slate-200 to-transparent dark:from-white/10 dark:to-transparent hover:from-indigo-500 hover:to-fuchsia-500 transition-all duration-500 shadow-sm hover:shadow-xl hover:-translate-y-1">
            <div className="absolute inset-0 bg-indigo-500/10 dark:bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
            <div className="relative h-full bg-white/80 dark:bg-[#0d0d12]/90 backdrop-blur-2xl p-10 rounded-[23px] border border-slate-200/50 dark:border-white/5 flex flex-col sm:flex-row gap-6">
              <div className="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-sm relative overflow-hidden">
                <div className="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-fuchsia-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div className="relative z-10">{f.icon}</div>
              </div>
              <div className="flex-1">
                <h3 className="text-2xl font-bold font-outfit mb-3 text-slate-800 dark:text-white/90 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{f.title}</h3>
                <p className="text-slate-500 dark:text-white/50 leading-relaxed font-medium text-sm sm:text-base">{f.desc}</p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}
