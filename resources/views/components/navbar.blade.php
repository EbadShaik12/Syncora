<nav x-data="navbarApp()" x-init="init()" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" 
    :class="scrolled ? 'bg-white/95 dark:bg-[#0d0d12]/95 border-b border-slate-200/80 dark:border-white/10 shadow-md backdrop-blur-2xl' : 'bg-white/80 dark:bg-[#0d0d12]/80 border-b border-slate-200/40 dark:border-white/5 shadow-sm backdrop-blur-xl'"
    @scroll.window="scrolled = (window.pageYOffset > 10)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 relative">

            {{-- 1. Left: Brand Logo --}}
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group transition duration-300 hover:scale-[1.02]">
                    <div class="w-8 h-8 rounded-full overflow-hidden relative bg-white flex items-center justify-center shadow-sm border border-slate-200/45 dark:border-white/10 flex-shrink-0">
                        <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-cover scale-[1.7] -translate-y-[15%] flex-shrink-0" alt="Syncora Icon">
                    </div>
                    <img src="{{ asset('images/logo-text.png') }}" class="h-6 w-auto object-contain dark:invert transition-all duration-300 hidden sm:block" alt="Syncora Text">
                </a>
            </div>

            {{-- 2. Center: Navigation links (active class set server-side by PHP) --}}
            <div class="hidden md:flex flex-1 justify-center items-center">
                <div class="flex items-center gap-2">
                    @if(auth()->user()->isStartup())
                        <a href="{{ route('startup.dashboard') }}" class="nav-link {{ request()->routeIs('startup.dashboard*') ? 'nav-link-active' : '' }}">Dashboard</a>
                        <a href="{{ route('startup.swipe') }}" class="nav-link {{ request()->routeIs('startup.swipe*') ? 'nav-link-active' : '' }}">Discover</a>
                        <a href="{{ route('startup.challenges') }}" class="nav-link {{ request()->routeIs('startup.challenges*') ? 'nav-link-active' : '' }}">Challenges</a>
                    @elseif(auth()->user()->isCorporate())
                        <a href="{{ route('corporate.dashboard') }}" class="nav-link {{ request()->routeIs('corporate.dashboard*') ? 'nav-link-active' : '' }}">Dashboard</a>
                        <a href="{{ route('corporate.swipe') }}" class="nav-link {{ request()->routeIs('corporate.swipe*') ? 'nav-link-active' : '' }}">Discover</a>
                        <a href="{{ route('corporate.challenges.index') }}" class="nav-link {{ request()->routeIs('corporate.challenges.*', 'corporate.challenges') ? 'nav-link-active' : '' }}">Challenges</a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'nav-link-active' : '' }}">📊 Analytics</a>
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : '' }}">👥 Users</a>
                        <a href="{{ route('admin.moderation.index') }}" class="nav-link {{ request()->routeIs('admin.moderation.*') ? 'nav-link-active' : '' }}">🛡️ Moderation</a>
                        <a href="{{ route('admin.industries.index') }}" class="nav-link {{ request()->routeIs('admin.industries.*') ? 'nav-link-active' : '' }}">🏷️ Industries</a>
                        <a href="{{ route('admin.badges.index') }}" class="nav-link {{ request()->routeIs('admin.badges.*') ? 'nav-link-active' : '' }}">🏅 Badges</a>
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'nav-link-active' : '' }}">📁 Reports</a>
                    @endif
                    @unless(auth()->user()->isAdmin())
                        <a href="{{ route('search') }}" class="nav-link {{ request()->routeIs('search*') ? 'nav-link-active' : '' }}">Search</a>
                        <a href="{{ route('chat.index') }}" class="nav-link {{ request()->routeIs('chat.*') ? 'nav-link-active' : '' }}">
                            Messages
                            <span x-show="unreadMessages > 0" x-text="unreadMessages > 9 ? '9+' : unreadMessages"
                                class="ml-1.5 bg-red-500 text-white text-[10px] font-black rounded-full px-2 py-0.5 leading-none shadow-md"></span>
                        </a>
                        <a href="{{ route('leaderboard') }}" class="nav-link {{ request()->routeIs('leaderboard*') ? 'nav-link-active' : '' }}">🏆 Ranks</a>
                    @endunless
                </div>
            </div>

            {{-- 3. Right: Secondary Platform Controls --}}
            <div class="flex items-center gap-2">

                {{-- Light / Dark Mode Toggle --}}
                <button @click="toggleTheme()" class="p-1.5 rounded-lg text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800 transition duration-200" aria-label="Toggle theme mode">
                    <svg x-show="!isDark" x-cloak class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <svg x-show="isDark" x-cloak class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Compact Notification Bell --}}
                @unless(auth()->user()->isAdmin())
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open; if(open) { loadNotifications(); markAllSeen(); }"
                        class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-zinc-800 transition duration-200 relative">
                        <svg class="w-5 h-5" :class="notifCount > 0 ? 'text-primary-500 animate-pulse' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="notifCount > 0" x-cloak
                            x-text="notifCount"
                            class="absolute top-1 right-1 bg-red-500 text-white text-[8px] font-black rounded-full w-4 h-4 flex items-center justify-center shadow">
                        </span>
                    </button>

                    {{-- Dropdown notifications --}}
                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute right-0 mt-3 w-96 bg-white dark:bg-[#0d0d12] rounded-2xl shadow-xl border border-slate-200/80 dark:border-zinc-800/80 overflow-hidden origin-top-right z-50">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800/50 flex items-center justify-between bg-slate-50/50 dark:bg-zinc-800/30">
                            <h3 class="font-black text-xs text-slate-800 dark:text-white uppercase tracking-wider">Notifications</h3>
                            <button @click="markAllRead()" class="text-[10px] font-black text-primary-600 hover:text-primary-700 uppercase tracking-widest">Mark all read</button>
                        </div>
                        <div class="max-h-[360px] overflow-y-auto hide-scrollbar divide-y divide-slate-100 dark:divide-zinc-800/50">
                            <template x-if="notifications.length === 0">
                                <div class="py-12 text-center text-slate-400 dark:text-zinc-500 text-xs">No notifications.</div>
                            </template>
                            <template x-for="n in notifications" :key="n.id">
                                <a :href="n.link || '#'" @click="markRead(n.id)" class="flex gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" :class="n.icon_color">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" :d="n.icon_svg"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-xs leading-snug text-slate-800 dark:text-zinc-200" x-text="n.title"></p>
                                        <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-2" x-text="n.body"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="block text-center py-3 text-[10px] font-black text-primary-600 border-t border-slate-100 dark:border-zinc-800/50 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-zinc-850">View all</a>
                    </div>
                </div>
                @endunless

                {{-- User Avatar Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2 p-1 rounded-2xl hover:bg-slate-100 dark:hover:bg-zinc-800/50 transition group"
                        id="user-menu-button">
                        <div class="relative">
                            @if(auth()->user()->profile() && auth()->user()->profile()->logo)
                                <img src="{{ auth()->user()->logoUrl() }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-white dark:ring-zinc-800 shadow transition group-hover:scale-105">
                            @else
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-xs shadow-md transition group-hover:scale-105
                                    @if(auth()->user()->isStartup()) bg-gradient-to-br from-violet-500 to-purple-700
                                    @elseif(auth()->user()->isCorporate()) bg-gradient-to-br from-blue-500 to-cyan-600
                                    @else bg-gradient-to-br from-amber-500 to-orange-600 @endif">
                                    {{ strtoupper(substr(auth()->user()->companyName(), 0, 1)) }}
                                </div>
                            @endif
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-white dark:border-[#0d0d12] shadow-sm"></span>
                        </div>
                        <svg class="hidden sm:block w-3 h-3 text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open" @click.outside="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                        class="absolute right-0 mt-3 w-72 rounded-2xl overflow-hidden origin-top-right z-50 shadow-2xl"
                        style="background:#ffffff; border:1px solid #e2e8f0;">

                        {{-- Header --}}
                        <div style="
                            @if(auth()->user()->isStartup()) background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
                            @elseif(auth()->user()->isCorporate()) background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
                            @else background: linear-gradient(135deg, #d97706 0%, #ef4444 100%); @endif
                            padding: 18px 20px;">
                            <div style="display:flex; align-items:center; gap:14px;">
                                @if(auth()->user()->profile() && auth()->user()->profile()->logo)
                                    <img src="{{ auth()->user()->logoUrl() }}"
                                         style="width:52px;height:52px;border-radius:12px;object-fit:cover;border:2px solid rgba(255,255,255,0.4);box-shadow:0 4px 12px rgba(0,0,0,0.25);">
                                @else
                                    <div style="width:52px;height:52px;border-radius:12px;background:rgba(255,255,255,0.25);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:900;color:#fff;border:2px solid rgba(255,255,255,0.4);box-shadow:0 4px 12px rgba(0,0,0,0.2);flex-shrink:0;">
                                        {{ strtoupper(substr(auth()->user()->companyName(), 0, 1)) }}
                                    </div>
                                @endif
                                <div style="min-width:0;flex:1;">
                                    <p style="font-weight:800;color:#ffffff;font-size:15px;line-height:1.3;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ auth()->user()->companyName() }}
                                    </p>
                                    <p style="color:rgba(255,255,255,0.8);font-size:12px;margin:3px 0 6px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ auth()->user()->email }}
                                    </p>
                                    <span style="display:inline-block;background:rgba(255,255,255,0.25);color:#ffffff;font-size:10px;font-weight:800;padding:2px 10px;border-radius:20px;letter-spacing:0.08em;text-transform:uppercase;border:1px solid rgba(255,255,255,0.4);">
                                        {{ auth()->user()->role }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Menu Items --}}
                        <div style="padding:8px;">
                            @if(auth()->user()->isStartup())
                                <a href="{{ route('startup.profile.edit') }}" @click="open = false"
                                   style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background 0.15s ease;"
                                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;line-height:1.3;">My Profile</p>
                                        <p style="font-size:11px;color:#64748b;margin:1px 0 0;">Edit startup info</p>
                                    </div>
                                </a>
                                <a href="{{ route('startup.applications') }}" @click="open = false"
                                   style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background 0.15s ease;"
                                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:10px;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;line-height:1.3;">My Applications</p>
                                        <p style="font-size:11px;color:#64748b;margin:1px 0 0;">Track challenge submissions</p>
                                    </div>
                                </a>
                            @elseif(auth()->user()->isCorporate())
                                <a href="{{ route('corporate.profile.edit') }}" @click="open = false"
                                   style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background 0.15s ease;"
                                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;line-height:1.3;">My Profile</p>
                                        <p style="font-size:11px;color:#64748b;margin:1px 0 0;">Edit company profile</p>
                                    </div>
                                </a>
                                <a href="{{ route('corporate.challenges.index') }}" @click="open = false"
                                   style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background 0.15s ease;"
                                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:10px;background:#cffafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="#0891b2" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;line-height:1.3;">My Challenges</p>
                                        <p style="font-size:11px;color:#64748b;margin:1px 0 0;">Manage challenges</p>
                                    </div>
                                </a>
                            @elseif(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" @click="open = false"
                                   style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background 0.15s ease;"
                                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;line-height:1.3;">Analytics</p>
                                        <p style="font-size:11px;color:#64748b;margin:1px 0 0;">Platform overview</p>
                                    </div>
                                </a>
                                <a href="{{ route('admin.users.index') }}" @click="open = false"
                                   style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background 0.15s ease;"
                                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;line-height:1.3;">Manage Users</p>
                                        <p style="font-size:11px;color:#64748b;margin:1px 0 0;">Approve &amp; moderate</p>
                                    </div>
                                </a>

                            @endif

                            @unless(auth()->user()->isAdmin())
                            <a href="{{ route('notifications.index') }}" @click="open = false"
                               style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background 0.15s ease;"
                               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                <div style="width:36px;height:36px;border-radius:10px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg width="16" height="16" fill="none" stroke="#4f46e5" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <div style="flex:1;">
                                    <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;line-height:1.3;">Notifications</p>
                                    <p style="font-size:11px;color:#64748b;margin:1px 0 0;">Platform activity</p>
                                </div>
                                <span x-show="notifCount > 0" x-text="notifCount"
                                      style="background:#ef4444;color:#fff;font-size:10px;font-weight:800;border-radius:20px;padding:1px 7px;"></span>
                            </a>
                            @endunless

                            {{-- Divider --}}
                            <div style="height:1px;background:#f1f5f9;margin:6px 0;"></div>

                            {{-- Sign out --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;width:100%;background:transparent;border:none;cursor:pointer;transition:background 0.15s ease;text-align:left;"
                                        onmouseover="this.style.background='#fff1f2'" onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#dc2626;margin:0;line-height:1.3;">Sign Out</p>
                                        <p style="font-size:11px;color:#64748b;margin:1px 0 0;">End session</p>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>



                {{-- Mobile Drawer Toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-zinc-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-cloak x-transition class="md:hidden pb-4 border-t border-slate-100 dark:border-white/5 pt-4 space-y-1 px-2 bg-white/95 dark:bg-[#0d0d12]/95 backdrop-blur-xl">
            @if(auth()->user()->isStartup())
                <a href="{{ route('startup.dashboard') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('startup.dashboard*') ? 'true' : 'false' }} }">Dashboard</a>
                <a href="{{ route('startup.swipe') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('startup.swipe*') ? 'true' : 'false' }} }">Discover</a>
                <a href="{{ route('startup.challenges') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('startup.challenges*') ? 'true' : 'false' }} }">Challenges</a>
            @elseif(auth()->user()->isCorporate())
                <a href="{{ route('corporate.dashboard') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('corporate.dashboard*') ? 'true' : 'false' }} }">Dashboard</a>
                <a href="{{ route('corporate.swipe') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('corporate.swipe*') ? 'true' : 'false' }} }">Discover</a>
                <a href="{{ route('corporate.challenges.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('corporate.challenges.*', 'corporate.challenges') ? 'true' : 'false' }} }">Challenges</a>
            @elseif(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('admin.dashboard*') ? 'true' : 'false' }} }">📊 Analytics</a>
                <a href="{{ route('admin.users.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">👥 Users</a>
                <a href="{{ route('admin.moderation.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('admin.moderation.*') ? 'true' : 'false' }} }">🛡️ Moderation</a>
                <a href="{{ route('admin.industries.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('admin.industries.*') ? 'true' : 'false' }} }">🏷️ Industries</a>
                <a href="{{ route('admin.badges.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('admin.badges.*') ? 'true' : 'false' }} }">🏅 Badges</a>
                <a href="{{ route('admin.reports.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">📁 Reports</a>
            @endif
            @unless(auth()->user()->isAdmin())
                <div class="h-px bg-slate-100 dark:bg-zinc-800 my-2 mx-3"></div>
                <a href="{{ route('search') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('search*') ? 'true' : 'false' }} }">Search</a>
                <a href="{{ route('chat.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('chat.*') ? 'true' : 'false' }} }">Messages</a>
                <a href="{{ route('leaderboard') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('leaderboard*') ? 'true' : 'false' }} }">Ranks</a>
                <a href="{{ route('notifications.index') }}" class="mobile-nav-link" :class="{ 'mobile-nav-link-active': {{ request()->routeIs('notifications.*') ? 'true' : 'false' }} }">Notifications</a>
            @endunless
        </div>
    </div>
</nav>

{{-- ═══════ MATCH CELEBRATION MODAL ═══════ --}}
<div x-data="navbarApp()" x-show="showMatchModal" x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div class="relative bg-white dark:bg-zinc-950 rounded-2xl shadow-xl border border-slate-200 dark:border-zinc-800 p-8 max-w-md w-full text-center overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2">

        {{-- Overlapping Brand Rings --}}
        <div class="flex items-center justify-center gap-2 mb-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-indigo-600 flex items-center justify-center text-white shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="w-6 h-px bg-slate-200 dark:bg-zinc-800"></div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
        </div>

        <h2 class="text-2xl font-black font-outfit text-slate-900 dark:text-white mb-2">It's a Match!</h2>
        <p class="text-slate-500 dark:text-zinc-400 text-sm font-medium mb-6 leading-relaxed" x-text="matchBody"></p>

        <div class="flex gap-3">
            <button @click="dismissMatch()"
                class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 rounded-xl font-bold text-sm transition">
                Later
            </button>
            <a :href="matchLink"
                class="flex-1 px-4 py-2.5 bg-slate-950 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 rounded-xl font-bold text-sm transition flex items-center justify-center gap-1.5 shadow-sm">
                Send Message 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function navbarApp() {
    return {
        scrolled: false,
        notifCount:     0,
        unreadMessages: 0,
        notifications:  [],
        showMatchModal: false,
        matchBody:      '',
        matchLink:      '{{ route("chat.index") }}',
        seenMatchId:    parseInt(localStorage.getItem('seenMatchId') || '0'),
        isDark: document.documentElement.classList.contains('dark'),
        mobileOpen:     false,

        init() {
            this.pollCount();
            setInterval(() => this.pollCount(), 10000); // every 10s
            this.isDark = document.documentElement.classList.contains('dark');
        },

        toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                this.isDark = false;
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                this.isDark = true;
            }
        },

        async pollCount() {
            try {
                const res  = await fetch('{{ route("notifications.count") }}');
                const data = await res.json();
                this.notifCount = data.count;

                if (data.latest_match && data.latest_match.id > this.seenMatchId) {
                    this.matchBody   = data.latest_match.body;
                    this.matchLink   = data.latest_match.link || '{{ route("chat.index") }}';
                    this.showMatchModal = true;
                    localStorage.setItem('seenMatchId', data.latest_match.id);
                    this.seenMatchId = data.latest_match.id;
                }
            } catch(e) {}
        },

        async loadNotifications() {
            try {
                const res  = await fetch('{{ route("notifications.dropdown") }}');
                const data = await res.json();
                this.notifications = data.notifications;
            } catch(e) {}
        },

        markAllSeen() {
            // Visually clear badge
        },

        async markRead(id) {
            this.notifications = this.notifications.map(n =>
                n.id === id ? { ...n, is_unread: false } : n
            );
            try {
                await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const res  = await fetch('{{ route("notifications.count") }}');
                const data = await res.json();
                this.notifCount = data.count;
            } catch(e) {}
        },

        async markAllRead() {
            this.notifications = this.notifications.map(n => ({ ...n, is_unread: false }));
            this.notifCount = 0;
            try {
                await fetch('{{ route("notifications.readAll") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
            } catch(e) {}
        },

        dismissMatch() {
            this.showMatchModal = false;
        },
    };
}
</script>
@endpush
