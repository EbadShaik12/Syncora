import React, { useRef, useMemo } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { OrbitControls, Sphere, Line, Html } from '@react-three/drei';
import * as THREE from 'three';
import { ArrowRight } from 'lucide-react';

// 3D Node representing a startup or corporate
function NetworkNode({ position, color, label }) {
  const mesh = useRef();
  
  useFrame((state) => {
    if (!mesh.current) return;
    const t = state.clock.getElapsedTime();
    mesh.current.position.y = position[1] + Math.sin(t + position[0]) * 0.2;
  });

  return (
    <group position={position}>
      <Sphere ref={mesh} args={[0.13, 32, 32]}>
        <meshStandardMaterial color={color} emissive={color} emissiveIntensity={0.6} toneMapped={false} />
      </Sphere>
      <Html distanceFactor={10} position={[0, 0.25, 0]} center style={{ pointerEvents: 'none' }}>
        <div className="bg-white/50 dark:bg-black/30 backdrop-blur-md px-2 py-0.5 rounded-lg border border-slate-200/30 dark:border-white/5 text-slate-500 dark:text-white/50 text-[9px] font-bold whitespace-nowrap shadow-sm select-none opacity-80 transition-opacity hover:opacity-100">
          {label}
        </div>
      </Html>
    </group>
  );
}

// Network Connections — connections are computed once with useMemo to avoid
// non-deterministic renders that crash React HMR
function Connections({ nodes }) {
  const lines = useMemo(() => {
    const result = [];
    // Use a seeded-like pattern instead of Math.random() to be stable
    for (let i = 0; i < nodes.length; i++) {
      for (let j = i + 1; j < nodes.length; j++) {
        // Deterministic: use index sum to decide connection
        if ((i + j) % 3 !== 0) {
          result.push(
            <Line
              key={`${i}-${j}`}
              points={[nodes[i].position, nodes[j].position]}
              color="white"
              lineWidth={0.5}
              transparent
              opacity={0.12}
            />
          );
        }
      }
    }
    return result;
  }, [nodes]);

  return <>{lines}</>;
}

export default function Hero3D({ isAuthenticated }) {
  // Nodes are pushed horizontally to the far left and far right periphery to keep the center layout clean and clear
  const nodes = [
    { position: [-3.2, 1.4, 0], color: '#6366f1', label: 'Tech Startup' },
    { position: [3.2, 1.6, -1], color: '#ec4899', label: 'Enterprise' },
    { position: [-2.8, -1.5, 1], color: '#a855f7', label: 'Investor' },
    { position: [-3.6, -0.2, -2], color: '#6366f1', label: 'AI Platform' },
    { position: [3.0, -1.0, 2], color: '#ec4899', label: 'Corporate' },
  ];

  return (
    <div className="relative w-full h-screen min-h-[650px] flex items-center justify-center overflow-hidden">
      
      {/* 3D Canvas Background */}
      <div className="absolute inset-0 z-0 opacity-60">
        <Canvas camera={{ position: [0, 0, 7], fov: 45 }}>
          <ambientLight intensity={0.25} />
          <pointLight position={[10, 10, 10]} intensity={1.5} />
          <group rotation={[0.2, -0.2, 0]}>
            {nodes.map((node, i) => (
              <NetworkNode key={i} {...node} />
            ))}
            <Connections nodes={nodes} />
          </group>
          <OrbitControls enableZoom={false} enablePan={false} autoRotate autoRotateSpeed={0.4} />
        </Canvas>
      </div>

      {/* Hero Content Overlay */}
      <div className="relative z-10 text-center px-6 max-w-5xl mx-auto flex flex-col items-center mt-12 md:mt-16">
        <div className="gsap-reveal inline-flex items-center gap-2.5 px-4.5 py-2.5 rounded-full border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur-md mb-8 shadow-sm">
          <span className="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse shadow-[0_0_8px_rgba(99,102,241,0.8)]"></span>
          <span className="text-xs sm:text-sm font-bold tracking-wider text-slate-700 dark:text-white/80 uppercase">The Future of Enterprise Acceleration</span>
        </div>
        
        <h1 className="gsap-reveal text-5xl sm:text-7xl md:text-8xl font-black font-outfit leading-[1.08] tracking-tight mb-8">
          Accelerate your <br />
          <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-fuchsia-500 to-pink-500 dark:from-indigo-400 dark:via-fuchsia-400 dark:to-pink-400 drop-shadow-sm">
            Partnership Pipeline
          </span>
        </h1>
        
        <p className="gsap-reveal text-base sm:text-lg md:text-xl text-slate-600 dark:text-white/60 max-w-3xl mx-auto mb-12 font-medium leading-relaxed">
          The premium matching platform uniting high-growth startups and visionary corporate enterprises through automated problem-solution fit analysis.
        </p>

        <div className="gsap-reveal flex flex-col sm:flex-row gap-4 items-center justify-center w-full sm:w-auto">
          {isAuthenticated ? (
            <a href="/dashboard" className="group relative overflow-hidden rounded-full bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white px-10 py-4.5 font-bold text-sm tracking-wide transition-all hover:scale-105 shadow-xl shadow-indigo-500/25">
              <span className="relative z-10 flex items-center gap-2">Go to Dashboard <ArrowRight className="w-4 h-4" /></span>
            </a>
          ) : (
            <>
              <a href="/register" className="group relative overflow-hidden rounded-full bg-slate-950 text-white dark:bg-white dark:text-black px-9 py-4.5 font-bold text-sm tracking-wide transition-all hover:scale-105 shadow-xl shadow-slate-950/20 dark:shadow-white/10 flex items-center gap-2">
                <span className="relative z-10 flex items-center gap-2">Explore Opportunities <ArrowRight className="w-4 h-4" /></span>
                <div className="absolute inset-0 bg-gradient-to-r from-indigo-600 to-fuchsia-600 dark:from-indigo-200 dark:to-fuchsia-200 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
              </a>
              <a href="/login" className="group px-9 py-4.5 rounded-full border border-slate-200 dark:border-white/20 bg-white/70 dark:bg-white/5 backdrop-blur-sm text-slate-700 dark:text-white font-bold text-sm tracking-wide transition-all hover:bg-slate-200 dark:hover:bg-white/10 hover:border-slate-300 dark:hover:border-white/40">
                Join Network
              </a>
            </>
          )}
        </div>
      </div>
      
      {/* Scroll indicator */}
      <div className="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-400 dark:text-white/40 animate-bounce">
        <span className="text-[10px] uppercase tracking-[0.2em] font-bold">Scroll to Explore</span>
        <div className="w-[1px] h-12 bg-gradient-to-b from-slate-400 to-transparent dark:from-white/40 dark:to-transparent"></div>
      </div>
    </div>
  );
}
