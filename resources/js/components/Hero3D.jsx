import React, { useRef } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { OrbitControls, Sphere, Line, Html } from '@react-three/drei';
import * as THREE from 'three';

// 3D Node representing a startup or corporate
function NetworkNode({ position, color, label }) {
  const mesh = useRef();
  
  useFrame((state) => {
    const t = state.clock.getElapsedTime();
    mesh.current.position.y = position[1] + Math.sin(t + position[0]) * 0.2;
  });

  return (
    <group position={position}>
      <Sphere ref={mesh} args={[0.15, 32, 32]}>
        <meshStandardMaterial color={color} emissive={color} emissiveIntensity={0.5} toneMapped={false} />
      </Sphere>
      <Html distanceFactor={10} position={[0, 0.3, 0]} center style={{ pointerEvents: 'none' }}>
        <div className="bg-black/50 backdrop-blur-md px-2 py-1 rounded border border-white/10 text-white/80 text-[10px] whitespace-nowrap">
          {label}
        </div>
      </Html>
    </group>
  );
}

// Network Connections
function Connections({ nodes }) {
  const lines = [];
  for (let i = 0; i < nodes.length; i++) {
    for (let j = i + 1; j < nodes.length; j++) {
      if (Math.random() > 0.4) {
        lines.push(
          <Line
            key={`${i}-${j}`}
            points={[nodes[i].position, nodes[j].position]}
            color="white"
            lineWidth={0.5}
            transparent
            opacity={0.15}
          />
        );
      }
    }
  }
  return <>{lines}</>;
}

export default function Hero3D() {
  const nodes = [
    { position: [-2, 1, 0], color: '#6366f1', label: 'Tech Startup' },
    { position: [2, 1.5, -1], color: '#ec4899', label: 'Enterprise' },
    { position: [0, -1, 1], color: '#a855f7', label: 'Investor' },
    { position: [-1.5, -1.5, -2], color: '#6366f1', label: 'AI Platform' },
    { position: [1.5, -0.5, 2], color: '#ec4899', label: 'Corporate' },
  ];

  return (
    <div className="relative w-full h-screen min-h-[800px] flex items-center justify-center overflow-hidden">
      
      {/* 3D Canvas Background */}
      <div className="absolute inset-0 z-0 opacity-60">
        <Canvas camera={{ position: [0, 0, 6], fov: 45 }}>
          <ambientLight intensity={0.2} />
          <pointLight position={[10, 10, 10]} intensity={1.5} />
          <group rotation={[0.2, -0.2, 0]}>
            {nodes.map((node, i) => (
              <NetworkNode key={i} {...node} />
            ))}
            <Connections nodes={nodes} />
          </group>
          <OrbitControls enableZoom={false} enablePan={false} autoRotate autoRotateSpeed={0.5} />
        </Canvas>
      </div>

      {/* Hero Content Overlay */}
      <div className="relative z-10 text-center px-6 max-w-5xl mx-auto flex flex-col items-center">
        <div className="gsap-reveal inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md mb-8">
          <span className="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
          <span className="text-sm font-medium tracking-wide text-white/80">The Future of B2B Networking</span>
        </div>
        
        <h1 className="gsap-reveal text-6xl md:text-8xl font-black font-outfit leading-tight tracking-tight mb-6">
          Connecting <br />
          <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-fuchsia-400 to-pink-400">
            Startups with Corporates
          </span>
        </h1>
        
        <p className="gsap-reveal text-lg md:text-xl text-white/60 max-w-2xl mx-auto mb-10 font-medium">
          A premium immersive platform designed to forge powerful partnerships, accelerate innovation, and drive mutual growth in the modern tech ecosystem.
        </p>

        <div className="gsap-reveal flex flex-col sm:flex-row gap-4 items-center justify-center">
          <a href="/register" className="group relative overflow-hidden rounded-full bg-white text-black px-8 py-4 font-bold text-sm tracking-wide transition-transform hover:scale-105">
            <span className="relative z-10 flex items-center gap-2">Explore Opportunities</span>
            <div className="absolute inset-0 bg-gradient-to-r from-indigo-200 to-fuchsia-200 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
          </a>
          <a href="/login" className="group px-8 py-4 rounded-full border border-white/20 bg-white/5 backdrop-blur-sm font-bold text-sm tracking-wide transition-all hover:bg-white/10 hover:border-white/40">
            Join Network
          </a>
        </div>
      </div>
      
      {/* Scroll indicator */}
      <div className="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/40 animate-bounce">
        <span className="text-[10px] uppercase tracking-[0.2em] font-bold">Scroll to Explore</span>
        <div className="w-[1px] h-12 bg-gradient-to-b from-white/40 to-transparent"></div>
      </div>
    </div>
  );
}
