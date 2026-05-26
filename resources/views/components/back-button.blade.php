{{--
    Universal Back Button Component
    Usage: @include('components.back-button') or @include('components.back-button', ['fallback' => route('some.route'), 'label' => 'Back to Challenges'])
--}}
@php
    $fallback = $fallback ?? url()->previous(route('dashboard'));
    $label    = $label    ?? 'Back';
@endphp

<div class="mb-6">
    <a href="{{ $fallback }}"
       onclick="if (window.hasUnsavedChanges && window.hasUnsavedChanges()) { event.preventDefault(); window.showUnsavedChangesModal(() => { if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ $fallback }}'; } }, () => {}); } else if (window.history.length > 1) { event.preventDefault(); window.history.back(); }"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
              text-gray-500 dark:text-gray-400
              hover:text-gray-900 dark:hover:text-white
              hover:bg-white/10 dark:hover:bg-white/5
              border border-transparent hover:border-white/10
              transition-all duration-200 group select-none">
        <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>{{ $label }}</span>
    </a>
</div>
