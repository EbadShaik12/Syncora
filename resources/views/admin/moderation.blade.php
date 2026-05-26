@extends('layouts.app')
@section('title', 'Content Moderation — Admin')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Administration</p>
            <h1 class="text-3xl font-black">Content <span class="bg-gradient-to-r from-red-500 to-orange-500 bg-clip-text text-transparent">Moderation</span></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review flagged profiles, reports and take action.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xl">⏳</div>
            <div>
                <p class="text-3xl font-black text-amber-700 dark:text-amber-300">{{ $pendingCount }}</p>
                <p class="text-sm font-bold text-amber-600 dark:text-amber-400">Pending Review</p>
            </div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/30 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center text-white text-xl">✅</div>
            <div>
                <p class="text-3xl font-black text-green-700 dark:text-green-300">{{ $reviewedCount }}</p>
                <p class="text-sm font-bold text-green-600 dark:text-green-400">Reviewed</p>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-400 flex items-center justify-center text-white text-xl">🚫</div>
            <div>
                <p class="text-3xl font-black text-gray-700 dark:text-gray-300">{{ $dismissedCount }}</p>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Dismissed</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-xl text-sm font-medium">✅ {{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 mb-6 flex gap-3">
        <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-red-400">
            <option value="">All Statuses</option>
            <option value="pending" @selected(request('status')=='pending')>⏳ Pending</option>
            <option value="reviewed" @selected(request('status')=='reviewed')>✅ Reviewed</option>
            <option value="dismissed" @selected(request('status')=='dismissed')>🚫 Dismissed</option>
        </select>
        <select name="reason" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-red-400">
            <option value="">All Reasons</option>
            <option value="spam" @selected(request('reason')=='spam')>Spam</option>
            <option value="inappropriate" @selected(request('reason')=='inappropriate')>Inappropriate</option>
            <option value="fake" @selected(request('reason')=='fake')>Fake Account</option>
            <option value="harassment" @selected(request('reason')=='harassment')>Harassment</option>
            <option value="other" @selected(request('reason')=='other')>Other</option>
        </select>
        <button type="submit" class="px-5 py-2 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition">Filter</button>
        <a href="{{ route('admin.moderation.index') }}" class="px-5 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition">Reset</a>
    </form>

    {{-- Flags Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <th class="px-6 py-3">Reported Content</th>
                        <th class="px-6 py-3">Reason</th>
                        <th class="px-6 py-3">Reporter</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($flags as $flag)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4">
                            @if($flag->flaggable)
                                @if($flag->flaggable instanceof \App\Models\User)
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $flag->flaggable->logoUrl() }}" class="w-8 h-8 rounded-lg">
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white text-xs">{{ $flag->flaggable->companyName() }}</p>
                                            <p class="text-[10px] text-gray-400">{{ ucfirst($flag->flaggable->role) }} account</p>
                                        </div>
                                    </div>
                                @elseif($flag->flaggable instanceof \App\Models\Challenge)
                                    <div>
                                        <p class="font-bold text-xs text-gray-900 dark:text-white">{{ $flag->flaggable->title }}</p>
                                        <p class="text-[10px] text-gray-400">Challenge</p>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">{{ class_basename($flag->flaggable_type) }} #{{ $flag->flaggable_id }}</span>
                                @endif
                            @else
                                <span class="text-xs text-red-400">Content deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $reasonColor = match($flag->reason) {
                                    'spam' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
                                    'inappropriate' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    'fake' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                                    'harassment' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold capitalize {{ $reasonColor }}">{{ $flag->reason }}</span>
                            @if($flag->notes)
                                <p class="text-[11px] text-gray-400 mt-1 max-w-[200px] truncate" title="{{ $flag->notes }}">{{ $flag->notes }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($flag->reporter)
                                <div class="flex items-center gap-2">
                                    <img src="{{ $flag->reporter->logoUrl() }}" class="w-6 h-6 rounded-md">
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $flag->reporter->companyName() }}</span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">Deleted user</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $flag->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            @php
                                $sc = match($flag->status) {
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                    'reviewed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                    'dismissed' => 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold capitalize {{ $sc }}">{{ $flag->status }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1 justify-end">
                                @if($flag->status === 'pending')
                                    <form method="POST" action="{{ route('admin.moderation.review', $flag) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="reviewed">
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 transition">✅ Resolve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.moderation.review', $flag) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="dismissed">
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-200 transition">🚫 Dismiss</button>
                                    </form>
                                    @if($flag->flaggable instanceof \App\Models\User)
                                    <form method="POST" action="{{ route('admin.moderation.ban', $flag) }}" onsubmit="return confirm('Suspend this user?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 transition">🔨 Ban</button>
                                    </form>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">Reviewed {{ $flag->reviewed_at?->diffForHumans() }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-gray-400">
                            <div class="text-4xl mb-3">🛡️</div>
                            <p class="font-medium">No flagged content. Platform is clean!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $flags->links() }}</div>
</div>
@endsection
