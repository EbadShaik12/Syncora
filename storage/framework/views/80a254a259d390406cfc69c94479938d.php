<nav x-data="navbarApp()" x-init="init()" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" 
    :class="{'bg-[#0d0d12]/80 backdrop-blur-xl border-b border-white/5 shadow-sm': scrolled, 'bg-transparent border-transparent': !scrolled}"
    @scroll.window="scrolled = (window.pageYOffset > 10)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            
            <div class="flex items-center gap-8">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-primary-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="font-black font-outfit text-xl bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent group-hover:opacity-80 transition-opacity hidden sm:block">StartupConnect</span>
                </a>

                
                <div class="hidden md:flex items-center gap-2">
                    <?php if(auth()->user()->isStartup()): ?>
                        <a href="<?php echo e(route('startup.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('startup.dashboard') ? 'nav-link-active' : ''); ?>">Dashboard</a>
                        <a href="<?php echo e(route('startup.swipe')); ?>" class="nav-link <?php echo e(request()->routeIs('startup.swipe') ? 'nav-link-active' : ''); ?>">Discover</a>
                        <a href="<?php echo e(route('startup.challenges')); ?>" class="nav-link <?php echo e(request()->routeIs('startup.challenges') ? 'nav-link-active' : ''); ?>">Challenges</a>
                    <?php elseif(auth()->user()->isCorporate()): ?>
                        <a href="<?php echo e(route('corporate.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('corporate.dashboard') ? 'nav-link-active' : ''); ?>">Dashboard</a>
                        <a href="<?php echo e(route('corporate.swipe')); ?>" class="nav-link <?php echo e(request()->routeIs('corporate.swipe') ? 'nav-link-active' : ''); ?>">Discover</a>
                        <a href="<?php echo e(route('corporate.challenges.index')); ?>" class="nav-link <?php echo e(request()->routeIs('corporate.challenges.*') ? 'nav-link-active' : ''); ?>">Challenges</a>
                    <?php elseif(auth()->user()->isAdmin()): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'nav-link-active' : ''); ?>">Dashboard</a>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'nav-link-active' : ''); ?>">Users</a>
                    <?php endif; ?>
                    <?php if (! (auth()->user()->isAdmin())): ?>
                        <a href="<?php echo e(route('search')); ?>" class="nav-link <?php echo e(request()->routeIs('search') ? 'nav-link-active' : ''); ?>">Search</a>
                        <a href="<?php echo e(route('chat.index')); ?>" class="nav-link <?php echo e(request()->routeIs('chat.*') ? 'nav-link-active' : ''); ?>">
                            Messages
                            <span x-show="unreadMessages > 0" x-text="unreadMessages > 9 ? '9+' : unreadMessages"
                                class="ml-1.5 bg-gradient-to-r from-primary-500 to-pink-500 text-white text-[10px] font-black rounded-full px-2 py-0.5 leading-none shadow-md shadow-primary-500/40"></span>
                        </a>
                        <a href="<?php echo e(route('leaderboard')); ?>" class="nav-link <?php echo e(request()->routeIs('leaderboard') ? 'nav-link-active' : ''); ?>">
                            🏆 Ranks
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="flex items-center gap-3">

                

                
                <?php if (! (auth()->user()->isAdmin())): ?>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open; if(open) { loadNotifications(); markAllSeen(); }"
                        class="relative p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5 transition-transform"
                             :class="notifCount > 0 ? 'text-primary-500' : ''"
                             x-effect="if(notifCount > 0) { $el.classList.add('animate-bounce'); setTimeout(()=>$el.classList.remove('animate-bounce'),1000); }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="notifCount > 0" x-cloak
                            x-text="notifCount > 99 ? '99+' : notifCount"
                            class="absolute -top-1 -right-1 bg-gradient-to-br from-pink-500 to-rose-500 text-white text-[9px] font-black rounded-full min-w-[20px] h-[20px] flex items-center justify-center px-1 shadow-md ring-2 ring-white dark:ring-gray-900">
                        </span>
                    </button>

                    
                    <div x-show="open" @click.outside="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
                        class="absolute right-0 mt-3 w-96 bg-[#0d0d12]/95 backdrop-blur-xl rounded-[2rem] shadow-[0_30px_60px_rgba(0,0,0,0.5)] border border-white/5 overflow-hidden origin-top-right border-glow">

                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800/50 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                            <div class="flex items-center gap-2">
                                <h3 class="font-black text-lg text-gray-900 dark:text-white">Notifications</h3>
                                <span x-show="notifCount > 0" x-text="notifCount"
                                    class="bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 text-xs font-bold px-2.5 py-0.5 rounded-full"></span>
                            </div>
                            <button @click="markAllRead()" class="text-xs font-bold text-primary-600 hover:text-primary-700 transition uppercase tracking-wider">
                                Mark all read
                            </button>
                        </div>

                        <div class="max-h-[420px] overflow-y-auto hide-scrollbar divide-y divide-gray-100 dark:divide-gray-800/50">
                            <template x-if="notifications.length === 0">
                                <div class="py-16 text-center">
                                    <div class="text-5xl mb-4 drop-shadow-md">🔔</div>
                                    <p class="text-base font-bold text-gray-900 dark:text-white">You're all caught up!</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No new notifications.</p>
                                </div>
                            </template>

                            <template x-for="n in notifications" :key="n.id">
                                <a :href="n.link || '#'" @click="markRead(n.id)"
                                    class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/60 transition group cursor-pointer relative overflow-hidden"
                                    :class="n.is_unread ? 'bg-primary-50/40 dark:bg-primary-900/10' : ''">
                                    
                                    <div x-show="n.is_unread" class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500"></div>

                                    <div :class="n.icon_color" class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md mt-1 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" :d="n.icon_svg"></path>
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="font-bold text-sm leading-snug group-hover:text-primary-600 transition-colors" x-text="n.title" :class="n.is_unread ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300'"></p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 font-medium leading-relaxed" x-text="n.body"></p>
                                        <p class="text-[10px] font-bold text-primary-500 uppercase tracking-widest mt-2" x-text="n.time_ago"></p>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <a href="<?php echo e(route('notifications.index')); ?>"
                            class="block text-center py-4 text-sm font-bold text-primary-600 dark:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800/60 border-t border-gray-100 dark:border-gray-800/50 transition uppercase tracking-widest">
                            View all notifications →
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                
                <div x-data="{ open: false }" class="relative">
                    
                    <button @click="open = !open"
                        class="flex items-center gap-2 p-1 rounded-2xl hover:bg-white/5 transition-all duration-200 group"
                        id="user-menu-button">
                        
                        <div class="relative">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-sm shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:rotate-3
                                <?php if(auth()->user()->isStartup()): ?> bg-gradient-to-br from-violet-500 to-purple-700 shadow-violet-500/30
                                <?php elseif(auth()->user()->isCorporate()): ?> bg-gradient-to-br from-blue-500 to-cyan-600 shadow-blue-500/30
                                <?php else: ?> bg-gradient-to-br from-amber-500 to-orange-600 shadow-amber-500/30 <?php endif; ?>">
                                <?php echo e(strtoupper(substr(auth()->user()->companyName(), 0, 1))); ?>

                            </div>
                            
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-[#090909] shadow-sm shadow-emerald-400/50"></span>
                        </div>
                        <svg class="hidden sm:block w-3.5 h-3.5 text-gray-500 group-hover:text-white transition-colors duration-200" :class="open ? 'rotate-180' : ''" style="transition: transform 0.2s ease" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    
                    <div x-show="open" @click.outside="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                        class="absolute right-0 mt-3 w-72 rounded-3xl overflow-hidden origin-top-right z-50"
                        style="background: rgba(10,8,22,0.97); box-shadow: 0 25px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.06), inset 0 1px 0 rgba(255,255,255,0.05); backdrop-filter: blur(24px);">

                        
                        <div class="relative overflow-hidden px-5 pt-5 pb-4">
                            
                            <div class="absolute inset-0 opacity-20
                                <?php if(auth()->user()->isStartup()): ?> bg-gradient-to-br from-violet-600 via-purple-700 to-transparent
                                <?php elseif(auth()->user()->isCorporate()): ?> bg-gradient-to-br from-blue-600 via-cyan-700 to-transparent
                                <?php else: ?> bg-gradient-to-br from-amber-600 via-orange-700 to-transparent <?php endif; ?>">
                            </div>
                            <div class="relative flex items-center gap-4">
                                
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-2xl flex-shrink-0
                                    <?php if(auth()->user()->isStartup()): ?> bg-gradient-to-br from-violet-500 to-purple-700
                                    <?php elseif(auth()->user()->isCorporate()): ?> bg-gradient-to-br from-blue-500 to-cyan-600
                                    <?php else: ?> bg-gradient-to-br from-amber-500 to-orange-600 <?php endif; ?>"
                                    style="box-shadow: 0 8px 24px rgba(0,0,0,0.4)">
                                    <?php echo e(strtoupper(substr(auth()->user()->companyName(), 0, 1))); ?>

                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-black text-white text-base leading-tight truncate"><?php echo e(auth()->user()->companyName()); ?></p>
                                    <p class="text-xs text-gray-400 font-medium truncate mt-0.5"><?php echo e(auth()->user()->email); ?></p>
                                    
                                    <div class="mt-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                            <?php if(auth()->user()->isStartup()): ?> bg-violet-500/20 text-violet-300 border border-violet-500/30
                                            <?php elseif(auth()->user()->isCorporate()): ?> bg-blue-500/20 text-blue-300 border border-blue-500/30
                                            <?php else: ?> bg-amber-500/20 text-amber-300 border border-amber-500/30 <?php endif; ?>">
                                            <?php if(auth()->user()->isStartup()): ?>
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            <?php elseif(auth()->user()->isCorporate()): ?>
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                            <?php else: ?>
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            <?php endif; ?>
                                            <?php echo e(auth()->user()->role); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="px-2 pb-2 space-y-0.5" style="border-top: 1px solid rgba(255,255,255,0.05);">
                            <?php if(auth()->user()->isStartup()): ?>
                                <a href="<?php echo e(route('startup.profile.edit')); ?>" class="premium-menu-item group" @click="open = false">
                                    <div class="w-8 h-8 rounded-xl bg-violet-500/15 flex items-center justify-center flex-shrink-0 group-hover:bg-violet-500/25 transition-colors">
                                        <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-200 group-hover:text-white transition-colors">My Profile</p>
                                        <p class="text-[11px] text-gray-500">View & edit your startup info</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-400 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                                <a href="<?php echo e(route('startup.applications')); ?>" class="premium-menu-item group" @click="open = false">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-500/25 transition-colors">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-200 group-hover:text-white transition-colors">My Applications</p>
                                        <p class="text-[11px] text-gray-500">Track challenge submissions</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-400 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            <?php elseif(auth()->user()->isCorporate()): ?>
                                <a href="<?php echo e(route('corporate.profile.edit')); ?>" class="premium-menu-item group" @click="open = false">
                                    <div class="w-8 h-8 rounded-xl bg-blue-500/15 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-500/25 transition-colors">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-200 group-hover:text-white transition-colors">My Profile</p>
                                        <p class="text-[11px] text-gray-500">Edit company information</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-400 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                                <a href="<?php echo e(route('corporate.challenges.index')); ?>" class="premium-menu-item group" @click="open = false">
                                    <div class="w-8 h-8 rounded-xl bg-cyan-500/15 flex items-center justify-center flex-shrink-0 group-hover:bg-cyan-500/25 transition-colors">
                                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-200 group-hover:text-white transition-colors">My Challenges</p>
                                        <p class="text-[11px] text-gray-500">Manage posted challenges</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-400 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            <?php endif; ?>

                            
                            <div class="my-1.5 mx-2" style="height:1px; background: rgba(255,255,255,0.05);"></div>

                            
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="premium-menu-item group w-full">
                                    <div class="w-8 h-8 rounded-xl bg-red-500/10 flex items-center justify-center flex-shrink-0 group-hover:bg-red-500/20 transition-colors">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-semibold text-red-400 group-hover:text-red-300 transition-colors">Sign Out</p>
                                        <p class="text-[11px] text-gray-600">End your current session</p>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        
        <div x-data="{ mobileOpen: false }" x-show="mobileOpen" x-cloak x-transition class="md:hidden pb-4 border-t border-white/10 pt-4 space-y-1 px-2 bg-[#0d0d12]/95 backdrop-blur-xl">
            <?php if(auth()->user()->isStartup()): ?>
                <a href="<?php echo e(route('startup.dashboard')); ?>" class="mobile-nav-link">Dashboard</a>
                <a href="<?php echo e(route('startup.swipe')); ?>" class="mobile-nav-link">Discover</a>
                <a href="<?php echo e(route('startup.challenges')); ?>" class="mobile-nav-link">Challenges</a>
            <?php elseif(auth()->user()->isCorporate()): ?>
                <a href="<?php echo e(route('corporate.dashboard')); ?>" class="mobile-nav-link">Dashboard</a>
                <a href="<?php echo e(route('corporate.swipe')); ?>" class="mobile-nav-link">Discover</a>
                <a href="<?php echo e(route('corporate.challenges.index')); ?>" class="mobile-nav-link">My Challenges</a>
            <?php elseif(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="mobile-nav-link">Dashboard</a>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="mobile-nav-link">Users</a>
            <?php endif; ?>
            <?php if (! (auth()->user()->isAdmin())): ?>
                <div class="h-px bg-gray-200 dark:bg-gray-800 my-2 mx-3"></div>
                <a href="<?php echo e(route('search')); ?>" class="mobile-nav-link">Search</a>
                <a href="<?php echo e(route('chat.index')); ?>" class="mobile-nav-link">Messages</a>
                <a href="<?php echo e(route('leaderboard')); ?>" class="mobile-nav-link">Leaderboard</a>
                <a href="<?php echo e(route('notifications.index')); ?>" class="mobile-nav-link">Notifications</a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<div x-data="navbarApp()" x-show="showMatchModal" x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-xl"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100">
    <div class="relative glass-card-strong rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] p-10 max-w-md w-full text-center overflow-hidden border-glow"
        x-transition:enter="transition cubic-bezier(0.175, 0.885, 0.32, 1.275) duration-500"
        x-transition:enter-start="opacity-0 scale-50 translate-y-10"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0">

        
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="absolute w-72 h-72 border border-pink-500/30 rounded-full animate-ping" style="animation-duration: 3s;"></div>
            <div class="absolute w-96 h-96 bg-gradient-to-tr from-primary-500/20 to-pink-500/20 rounded-full blur-[80px]"></div>
        </div>

        
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-4 left-6 text-4xl animate-bounce drop-shadow-lg" style="animation-delay:0.1s">🎊</div>
            <div class="absolute top-8 right-8 text-3xl animate-bounce drop-shadow-lg" style="animation-delay:0.3s">⭐</div>
            <div class="absolute bottom-10 left-8 text-3xl animate-bounce drop-shadow-lg" style="animation-delay:0.2s">✨</div>
            <div class="absolute bottom-6 right-6 text-4xl animate-bounce drop-shadow-lg" style="animation-delay:0.4s">🎉</div>
        </div>

        
        <div class="relative w-28 h-28 mx-auto mb-6 z-10">
            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 opacity-30 animate-ping"></div>
            <div class="relative w-28 h-28 rounded-[2rem] bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-[0_20px_40px_rgba(225,29,72,0.4)] rotate-12 hover:rotate-0 transition-transform duration-500">
                <svg class="w-14 h-14 text-white drop-shadow-md" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
        </div>

        <h2 class="text-4xl font-black font-outfit bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text text-transparent mb-3 relative z-10 drop-shadow-sm">It's a Match!</h2>
        <p class="text-gray-600 dark:text-gray-300 text-base font-medium mb-8 leading-relaxed relative z-10" x-text="matchBody"></p>

        <div class="flex flex-col sm:flex-row gap-4 relative z-10">
            <button @click="dismissMatch()"
                class="flex-1 px-6 py-3.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-2xl font-bold text-sm transition uppercase tracking-wider text-gray-700 dark:text-gray-300">
                Later
            </button>
            <a :href="matchLink"
                class="flex-1 px-6 py-3.5 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white rounded-2xl font-black text-sm shadow-[0_10px_20px_rgba(225,29,72,0.3)] transition hover:scale-105 flex items-center justify-center gap-2">
                Send Message 
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<style>
    .nav-link { @apply px-4 py-2 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100/80 dark:hover:bg-gray-800/80 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-1.5; }
    .nav-link-active { @apply bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 shadow-inner; }
    .menu-item { @apply flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400 rounded-xl transition-all cursor-pointer; }
    .premium-menu-item { display: flex; align-items: center; gap: 12px; padding: 10px 10px; border-radius: 14px; cursor: pointer; transition: background 0.18s ease; width: 100%; }
    .premium-menu-item:hover { background: rgba(255,255,255,0.04); }
    .mobile-nav-link { @apply block px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-primary-600 dark:hover:text-primary-400 transition-colors; }
</style>
<script>
function navbarApp() {
    return {
        scrolled: false,
        notifCount:     0,
        unreadMessages: 0,
        notifications:  [],
        showMatchModal: false,
        matchBody:      '',
        matchLink:      '<?php echo e(route("chat.index")); ?>',
        seenMatchId:    parseInt(localStorage.getItem('seenMatchId') || '0'),

        init() {
            this.pollCount();
            setInterval(() => this.pollCount(), 10000); // every 10s
        },

        async pollCount() {
            try {
                const res  = await fetch('<?php echo e(route("notifications.count")); ?>');
                const data = await res.json();
                this.notifCount = data.count;

                // Trigger match celebration for new unseen match
                if (data.latest_match && data.latest_match.id > this.seenMatchId) {
                    this.matchBody   = data.latest_match.body;
                    this.matchLink   = data.latest_match.link || '<?php echo e(route("chat.index")); ?>';
                    this.showMatchModal = true;
                }
            } catch(e) {}
        },

        async loadNotifications() {
            try {
                const res  = await fetch('<?php echo e(route("notifications.dropdown")); ?>');
                const data = await res.json();
                this.notifications = data.notifications;
            } catch(e) {}
        },

        markAllSeen() {
            // Visually clear badge without server call (server call below)
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
                const res  = await fetch('<?php echo e(route("notifications.count")); ?>');
                const data = await res.json();
                this.notifCount = data.count;
            } catch(e) {}
        },

        async markAllRead() {
            this.notifications = this.notifications.map(n => ({ ...n, is_unread: false }));
            this.notifCount = 0;
            try {
                await fetch('<?php echo e(route("notifications.readAll")); ?>', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
            } catch(e) {}
        },

        dismissMatch() {
            if (this.matchBody) {
                // Store the latest seen match ID
                const latestId = this.notifications.find(n => n.type === 'match')?.id;
                if (latestId) {
                    localStorage.setItem('seenMatchId', latestId);
                    this.seenMatchId = latestId;
                }
            }
            this.showMatchModal = false;
        },
    };
}
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views/components/navbar.blade.php ENDPATH**/ ?>