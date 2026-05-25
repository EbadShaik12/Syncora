@extends('layouts.app')
@section('title', 'Register as Startup')

@push('styles')
<style>
    /* Floating labels */
    .input-group { position: relative; margin-bottom: 1.25rem; }
    .input-group input, .input-group select, .input-group textarea { 
        padding: 1.5rem 1rem 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.4);
    }
    .dark .input-group input, .dark .input-group select, .dark .input-group textarea { background: rgba(0, 0, 0, 0.2); }
    .input-group label {
        position: absolute; left: 1rem; top: 1.2rem;
        transition: all 0.2s ease; pointer-events: none;
        color: #6b7280; font-size: 0.875rem; font-weight: 500;
    }
    .dark .input-group label { color: #9ca3af; }
    .input-group input:focus ~ label, .input-group input:not(:placeholder-shown) ~ label,
    .input-group select:focus ~ label, .input-group select:not(:placeholder-shown) ~ label,
    .input-group textarea:focus ~ label, .input-group textarea:not(:placeholder-shown) ~ label {
        top: 0.35rem; font-size: 0.65rem; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;
    }
    .dark .input-group input:focus ~ label, .dark .input-group input:not(:placeholder-shown) ~ label,
    .dark .input-group select:focus ~ label, .dark .input-group select:not(:placeholder-shown) ~ label,
    .dark .input-group textarea:focus ~ label, .dark .input-group textarea:not(:placeholder-shown) ~ label { color: #60a5fa; }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden bg-transparent">
    
    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute top-20 left-10 w-96 h-96 bg-blue-500/20 rounded-full blur-[100px] blob"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-500/20 rounded-full blur-[100px] blob" style="animation-delay:-5s"></div>
        <div class="absolute inset-0 noise opacity-20"></div>
    </div>

    <div class="w-full max-w-4xl relative z-10 stagger-container grid lg:grid-cols-5 gap-8 items-start mt-8">
        
        {{-- Left: Info panel --}}
        <div class="lg:col-span-2 lg:sticky lg:top-24 text-center lg:text-left reveal">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-blue-600 transition mb-8 magnetic">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to roles
            </a>
            
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 items-center justify-center text-white mb-6 shadow-xl shadow-blue-500/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            
            <h2 class="text-4xl font-black mb-4 font-outfit">Startup <span class="text-gradient from-blue-400 to-indigo-600">Application</span></h2>
            <p class="text-gray-600 dark:text-gray-300 mb-8 font-medium">Join the premier network for high-growth startups and visionary corporate partners.</p>
            
            <div class="hidden lg:block space-y-4 text-sm">
                <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">1</div>
                    <span class="font-medium">Basic Account Info</span>
                </div>
                <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">2</div>
                    <span class="font-medium">Company Profile</span>
                </div>
                <div class="flex items-center gap-3 text-gray-400 dark:text-gray-600">
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-600 font-bold">3</div>
                    <span>Complete Profile Later</span>
                </div>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="lg:col-span-3 reveal reveal-delay-2 glass-card-strong rounded-3xl p-8 shadow-2xl border-glow">
            <form method="POST" action="{{ route('register.startup') }}">
                @csrf
                
                {{-- Account Section --}}
                <div class="mb-8 relative">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-6 h-6 rounded bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 font-bold text-xs">1</div>
                        <h3 class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-sm">Account Details</h3>
                        <div class="flex-1 h-px bg-gradient-to-r from-gray-200 dark:from-gray-700 to-transparent ml-2"></div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="input-group">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="name">Your Full Name</label>
                            @error('name')<p class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</p>@enderror
                        </div>
                        <div class="input-group">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="email">Work Email</label>
                            @error('email')<p class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4 mt-2">
                        <div class="input-group">
                            <input type="password" name="password" id="password" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="password">Password</label>
                            @error('password')<p class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</p>@enderror
                        </div>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder=" "
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white">
                            <label for="password_confirmation">Confirm Password</label>
                        </div>
                    </div>
                </div>

                {{-- Company Section --}}
                <div class="relative">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-6 h-6 rounded bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 font-bold text-xs">2</div>
                        <h3 class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-sm">Company Profile</h3>
                        <div class="flex-1 h-px bg-gradient-to-r from-gray-200 dark:from-gray-700 to-transparent ml-2"></div>
                    </div>

                    <div class="input-group">
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white">
                        <label for="company_name">Startup Name</label>
                        @error('company_name')<p class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4 mt-2">
                        <div class="input-group">
                            <select name="industry_id" id="industry_id" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white appearance-none">
                                <option value=""></option>
                                @foreach($industries as $i)<option value="{{ $i->id }}" {{ old('industry_id') == $i->id ? 'selected' : '' }}>{{ $i->name }}</option>@endforeach
                            </select>
                            <label for="industry_id">Industry</label>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            @error('industry_id')<p class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</p>@enderror
                        </div>
                        <div class="input-group">
                            <select name="stage" id="stage" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white appearance-none">
                                <option value="idea" {{ old('stage') == 'idea' ? 'selected' : '' }}>Idea / Pre-seed</option>
                                <option value="mvp" {{ old('stage') == 'mvp' ? 'selected' : '' }}>MVP / Seed</option>
                                <option value="growth" {{ old('stage') == 'growth' ? 'selected' : '' }}>Growth / Series A</option>
                                <option value="scale" {{ old('stage') == 'scale' ? 'selected' : '' }}>Scaling / Series B+</option>
                            </select>
                            <label for="stage">Current Stage</label>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group mt-2">
                        <textarea name="elevator_pitch" id="elevator_pitch" rows="3" required minlength="20" maxlength="1000" placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition resize-none text-gray-900 dark:text-white">{{ old('elevator_pitch') }}</textarea>
                        <label for="elevator_pitch">Elevator Pitch (Problem & Solution)</label>
                        @error('elevator_pitch')<p class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="input-group mt-2">
                        <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder=" "
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition text-gray-900 dark:text-white">
                        <label for="city">City / Location (Optional)</label>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="submit" class="shimmer-btn w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-4 rounded-xl shadow-xl shadow-blue-500/30 transition hover:scale-[1.02] flex items-center justify-center gap-2">
                        Create Startup Account
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    
                    <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                        By registering, you agree to our <a href="#" class="text-blue-600 hover:underline">Terms</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
