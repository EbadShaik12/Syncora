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
      <div className="text-center mb-20 gsap-reveal">
        <h2 className="text-4xl md:text-5xl font-black font-outfit mb-4">Ecosystem Features</h2>
        <p className="text-white/50 max-w-xl mx-auto">Everything you need to build, scale, and manage powerful B2B relationships.</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
        {features.map((f, i) => (
          <div key={i} className="gsap-reveal group relative p-[1px] rounded-[2rem] overflow-hidden bg-gradient-to-br from-white/10 to-transparent hover:from-indigo-500/50 hover:to-fuchsia-500/50 transition-colors duration-500">
            <div className="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
            <div className="relative h-full bg-[#0d0d12] backdrop-blur-xl p-10 rounded-[2rem] border border-white/5">
              <div className="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                {f.icon}
              </div>
              <h3 className="text-2xl font-bold font-outfit mb-3 text-white/90 group-hover:text-white transition-colors">{f.title}</h3>
              <p className="text-white/50 leading-relaxed font-medium">{f.desc}</p>
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}
