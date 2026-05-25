@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('components.back-button', ['fallback' => route('admin.dashboard'), 'label' => 'Back to Dashboard'])
    <h1 class="text-3xl font-bold mb-6">User Management</h1>

    <!-- Filters -->
    <form method="GET" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 mb-6 grid md:grid-cols-4 gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or email" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
        <select name="role" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
            <option value="">All roles</option>
            <option value="startup" @selected(request('role')=='startup')>Startup</option>
            <option value="corporate" @selected(request('role')=='corporate')>Corporate</option>
        </select>
        <select name="status" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-primary-500 outline-none">
            <option value="">All statuses</option>
            <option value="approved" @selected(request('status')=='approved')>Approved</option>
            <option value="pending" @selected(request('status')=='pending')>Pending</option>
            <option value="rejected" @selected(request('status')=='rejected')>Rejected</option>
            <option value="suspended" @selected(request('status')=='suspended')>Suspended</option>
        </select>
        <button class="bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium">Filter</button>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Joined</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->logoUrl() }}" class="w-9 h-9 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $user->companyName() }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs capitalize {{ $user->isStartup() ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' }}">{{ $user->role }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColor = match($user->status) {
                                    'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    'suspended' => 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs capitalize {{ $statusColor }}">{{ $user->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1">
                                @if($user->status === 'pending')
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">@csrf<button class="px-2 py-1 text-xs rounded bg-green-100 text-green-700 hover:bg-green-200">Approve</button></form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline">@csrf<button class="px-2 py-1 text-xs rounded bg-red-100 text-red-700 hover:bg-red-200">Reject</button></form>
                                @endif
                                <form method="POST" action="{{ route('admin.users.toggleSuspend', $user) }}" class="inline">@csrf<button class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700 hover:bg-yellow-200">{{ $user->status === 'suspended' ? 'Unsuspend' : 'Suspend' }}</button></form>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this user permanently?');">@csrf @method('DELETE')<button class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Delete</button></form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-12 text-gray-500">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
