import React from 'react';
import { Rocket, Building2, Handshake, Target } from 'lucide-react';

export default function Stats({ stats }) {
  const displayStats = [
    {
      label: "Active Startups",
      value: stats?.startups || 120,
      suffix: "+",
      desc: "Vetted high-growth tech ventures",
      icon: <Rocket className="w-6 h-6 text-indigo-400" />,
      glowColor: "rgba(99, 102, 241, 0.15)",
      borderColor: "group-hover:border-indigo-500/50",
      iconBg: "bg-indigo-500/10 text-indigo-400"
    },
    {
      label: "Enterprise Partners",
      value: stats?.corporates || 48,
      suffix: "+",
      desc: "Visionary corporate leaders",
      icon: <Building2 className="w-6 h-6 text-fuchsia-400" />,
      glowColor: "rgba(240, 46, 170, 0.15)",
      borderColor: "group-hover:border-fuchsia-500/50",
      iconBg: "bg-fuchsia-500/10 text-fuchsia-400"
    },
    {
      label: "Strategic Matches",
      value: stats?.connections || 340,
      suffix: "+",
      desc: "Successful partnership fits",
      icon: <Handshake className="w-6 h-6 text-pink-400" />,
      glowColor: "rgba(244, 63, 94, 0.15)",
      borderColor: "group-hover:border-pink-500/50",
      iconBg: "bg-pink-500/10 text-pink-400"
    },
    {
      label: "Open Challenges",
      value: stats?.challenges || 87,
      suffix: "",
      desc: "Live corporate solution requests",
      icon: <Target className="w-6 h-6 text-emerald-400" />,
      glowColor: "rgba(16, 185, 129, 0.15)",
      borderColor: "group-hover:border-emerald-500/50",
      iconBg: "bg-emerald-500/10 text-emerald-400"
    }
  ];

  return (
    <section className="py-20 relative z-10">
      <div className="max-w-7xl mx-auto px-6">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {displayStats.map((item, idx) => (
            <div
              key={idx}
              className="group relative p-8 rounded-3xl bg-white/70 dark:bg-[#0d0d12]/80 border border-slate-200/50 dark:border-white/5 transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl overflow-hidden"
              style={{
                backdropFilter: 'blur(20px)',
              }}
            >
              {/* Dynamic Glow Spotlight */}
              <div 
                className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none blur-3xl"
                style={{
                  background: `radial-gradient(circle at center, ${item.glowColor} 0%, transparent 60%)`,
                }}
              />

              {/* Glowing Corner Accents */}
              <div className="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-indigo-500/5 to-transparent rounded-bl-full group-hover:scale-110 transition-transform duration-500" />
              
              {/* Icon Container */}
              <div className={`w-12 h-12 rounded-2xl ${item.iconBg} flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500`}>
                {item.icon}
              </div>

              {/* Value and Label */}
              <div className="relative z-10">
                <div className="flex items-baseline mb-2">
                  <span className="text-4xl md:text-5xl font-black font-outfit text-slate-900 dark:text-white tracking-tight">
                    {item.value}
                  </span>
                  <span className="text-2xl font-black text-indigo-500 dark:text-indigo-400 ml-0.5">
                    {item.suffix}
                  </span>
                </div>
                <h3 className="text-sm font-bold text-slate-800 dark:text-white/80 tracking-wider uppercase mb-1">
                  {item.label}
                </h3>
                <p className="text-xs text-slate-500 dark:text-white/40 font-medium">
                  {item.desc}
                </p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
