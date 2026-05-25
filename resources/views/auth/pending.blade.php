@extends('layouts.app')
@section('title', 'Pending Approval')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-10">
        <div class="w-20 h-20 mx-auto rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-2xl font-bold mb-3">Awaiting Approval</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Your account is pending admin review. We'll notify you by email once approved.</p>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-primary-600 hover:underline text-sm font-medium">Sign out</button></form>
    </div>
</div>
@endsection
