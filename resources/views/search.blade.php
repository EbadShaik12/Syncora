@extends('layouts.app')
@section('title', 'Search ' . ($targetRole === 'startup' ? 'Startups' : 'Corporates'))

@push('styles')
<style type="text/tailwindcss">
    .filter-chip { @apply inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border transition-all select-none; }
    .filter-chip-inactive { @apply border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400 bg-white/50 dark:bg-gray-900/50 hover:border-primary-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30; }
    .filter-chip-active   { @apply border-primary-500 bg-gradient-to-r from-primary-500 to-purple-600 text-white shadow-lg shadow-primary-500/30; }
    
    .result-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .result-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 30px 60px rgba(99,102,241,0.15); border-color: rgba(168,85,247,0.5); }
    
    input[type=range] { accent-color: #a855f7; }
    
    /* Custom Scrollbar for Sidebar */
    .filter-sidebar::-webkit-scrollbar { width: 4px; }
    .filter-sidebar::-webkit-scrollbar-track { background: transparent; }
    .filter-sidebar::-webkit-scrollbar-thumb { background: rgba(168,85,247,0.2); border-radius: 10px; }
    .filter-sidebar:hover::-webkit-scrollbar-thumb { background: rgba(168,85,247,0.5); }

    /* Skeleton overlay during filter navigation */
    #results-skeleton { display:none; }
    body.searching #results-skeleton { display:grid; }
    body.searching #results-real { opacity:0.3; pointer-events:none; filter: blur(4px); transition: all 0.3s; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10" x-data="searchPage()">
    @include('components.back-button', ['fallback' => route('dashboard'), 'label' => 'Back to Dashboard'])

    {{-- ── Header ── --}}
    <div class="reveal flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 text-xs font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400 mb-4 border border-primary-200 dark:border-primary-800/50">
                <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                Database
            </div>
            <h1 class="text-4xl sm:text-5xl font-black font-outfit text-gray-900 dark:text-white mb-2">
                Discover <span class="text-gradient from-primary-500 to-purple-600">{{ $targetRole === 'startup' ? 'Startups' : 'Corporate Partners' }}</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">
                {{ $results->total() }} result{{ $results->total() !== 1 ? 's' : '' }} found powered by AI matching
                @if(request()->anyFilled(['q','industry_id','city','stage','budget_min','budget_max','tech_tag']))
                    · <a href="{{ route('search') }}" class="text-primary-600 font-bold hover:underline">Clear all filters</a>
                @endif
            </p>
        </div>
        {{-- Sort toggle --}}
        <div class="flex items-center gap-2 glass-card rounded-2xl p-1.5 shadow-lg border-glow">
            <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}"
                class="px-5 py-2.5 text-sm font-bold rounded-xl transition {{ request('sort') !== 'score' ? 'bg-gradient-to-r from-gray-800 to-gray-900 text-white shadow-md' : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                Newest
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'score']) }}"
                class="px-5 py-2.5 text-sm font-bold rounded-xl transition {{ request('sort') === 'score' ? 'bg-gradient-to-r from-primary-600 to-purple-600 text-white shadow-lg shadow-primary-500/30' : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <span class="mr-1">✨</span> Best Match
            </a>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ══ FILTER SIDEBAR ══ --}}
        <aside class="reveal reveal-delay-1 lg:w-80 flex-shrink-0">
            <form method="GET" id="filter-form" @submit.prevent="updateResults()" @input.debounce.300ms="updateResults()" @change="updateResults()" class="space-y-6 sticky top-24 filter-sidebar max-h-[calc(100vh-8rem)] overflow-y-auto pr-2 pb-10">
                {{-- Keep sort & tech_tag params --}}
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                <input type="hidden" name="tech_tag" id="active-tech-tag" value="{{ request('tech_tag') }}">

                {{-- Keyword --}}
                <div class="glass-card-strong rounded-3xl p-6 border-glow">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Search
                    </label>
                    <div x-data="searchSuggest()" class="relative">
                        <input type="text" name="q" id="q" value="{{ request('q') }}"
                            placeholder="Company name or keyword…"
                            @input.debounce.300ms="fetchSuggestions($event.target.value)"
                            @focus="show = suggestions.length > 0"
                            @click.outside="show = false"
                            class="w-full px-4 py-3.5 text-sm rounded-xl border border-gray-200 dark:border-gray-700/50 bg-white/50 dark:bg-gray-900/50 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 transition text-gray-900 dark:text-white font-medium">
                        {{-- Autocomplete dropdown --}}
                        <div x-show="show && suggestions.length" x-cloak
                            class="absolute z-20 left-0 right-0 mt-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <template x-for="s in suggestions" :key="s.id">
                                <a :href="s.url" class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 dark:hover:bg-purple-900/30 transition">
                                    <img :src="s.avatar" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 shadow-sm">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="s.name"></p>
                                        <p class="text-[10px] font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider mt-0.5" x-text="s.industry"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Industry --}}
                <div class="glass-card-strong rounded-3xl p-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-4">Industry</label>
                    <select name="industry_id" class="w-full text-sm px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700/50 bg-white/50 dark:bg-gray-900/50 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 transition text-gray-900 dark:text-white font-medium appearance-none">
                        <option value="">All Industries</option>
                        @foreach($industries as $ind)
                            <option value="{{ $ind->id }}" @selected(request('industry_id') == $ind->id)>{{ $ind->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Location --}}
                <div class="glass-card-strong rounded-3xl p-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-4">City</label>
                    <input type="text" name="city" value="{{ request('city') }}" placeholder="e.g. Mumbai, Bangalore…"
                        class="w-full text-sm px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700/50 bg-white/50 dark:bg-gray-900/50 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/30 transition text-gray-900 dark:text-white font-medium">
                </div>

                {{-- Stage (startups only) --}}
                @if($targetRole === 'startup')
                <div class="glass-card-strong rounded-3xl p-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-4">Startup Stage</label>
                    <div class="space-y-3">
                        @foreach(['idea' => '💡 Idea Phase', 'mvp' => '🔧 MVP Ready', 'growth' => '📈 Growth Stage', 'scale' => '🚀 Scaling Up'] as $val => $label)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="radio" name="stage" value="{{ $val }}" {{ request('stage') === $val ? 'checked' : '' }}
                                    class="peer appearance-none w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full checked:border-purple-500 transition">
                                <div class="absolute w-2.5 h-2.5 rounded-full bg-purple-500 opacity-0 peer-checked:opacity-100 transition scale-0 peer-checked:scale-100"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">{{ $label }}</span>
                        </label>
                        @endforeach
                        @if(request('stage'))
                        <a href="{{ request()->fullUrlWithQuery(['stage' => null]) }}" class="block mt-4 text-xs font-bold text-purple-500 hover:text-purple-600 transition uppercase tracking-wider">Clear Stage Filter</a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Budget (corporates only) --}}
                @if($targetRole === 'corporate')
                <div class="glass-card-strong rounded-3xl p-6" x-data="budgetSlider()">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-4">Budget Range</label>
                    <div class="flex items-center justify-between text-sm font-bold text-purple-600 dark:text-purple-400 mb-4 bg-purple-50 dark:bg-purple-900/30 px-4 py-2 rounded-xl border border-purple-100 dark:border-purple-800/50">
                        <span>₹<span x-text="(minVal/100000).toFixed(0)+'L'"></span></span>
                        <span>-</span>
                        <span>₹<span x-text="(maxVal/100000).toFixed(0)+'L'"></span></span>
                    </div>
                    <div class="relative h-2 bg-gray-200 dark:bg-gray-700 rounded-full mb-6">
                        <div class="absolute h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full" 
                             :style="`left: ${(minVal/5000000)*100}%; right: ${100 - (maxVal/5000000)*100}%`"></div>
                        <input type="range" name="budget_min" x-model="minVal" :min="0" :max="5000000" step="100000"
                            class="absolute w-full -top-2 opacity-0 cursor-pointer" @change="$el.form.submit()">
                        <input type="range" name="budget_max" x-model="maxVal" :min="0" :max="5000000" step="100000"
                            class="absolute w-full -top-2 opacity-0 cursor-pointer" @change="$el.form.submit()">
                    </div>
                </div>
                @endif

                {{-- Tech tags --}}
                @if($allTags->count() > 0)
                <div class="glass-card-strong rounded-3xl p-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-4">Tech Tags</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($allTags->take(20) as $tag)
                        <button type="button" @click="setTechTag('{{ $tag }}')"
                            class="filter-chip"
                            :class="activeTag === '{{ $tag }}' ? 'filter-chip-active' : 'filter-chip-inactive'">
                            #{{ $tag }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Apply / Reset --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 shimmer-btn bg-gradient-to-r from-primary-600 to-purple-600 text-white text-sm font-bold py-3.5 rounded-xl hover:scale-[1.02] transition shadow-xl shadow-primary-500/30 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Apply
                    </button>
                    <a href="{{ route('search') }}" class="px-5 py-3.5 text-sm font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center justify-center shadow-sm">
                        Reset
                    </a>
                </div>
            </form>
        </aside>

        {{-- ══ RESULTS ══ --}}
        <div class="flex-1 min-w-0">

            {{-- Mobile filter bar --}}
            <div class="lg:hidden mb-6 reveal reveal-delay-2">
                <form method="GET" class="flex gap-3 overflow-x-auto pb-4 hide-scrollbar">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…"
                        class="flex-1 min-w-[200px] text-sm px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-900/50 outline-none focus:border-purple-500 backdrop-blur-md">
                    <select name="industry_id" class="text-sm px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-900/50 outline-none focus:border-purple-500 backdrop-blur-md">
                        <option value="">Industry</option>
                        @foreach($industries as $ind)
                        <option value="{{ $ind->id }}" @selected(request('industry_id') == $ind->id)>{{ $ind->name }}</option>
                        @endforeach
                    </select>
                    <button class="px-6 py-3 bg-gradient-to-r from-primary-600 to-purple-600 text-white text-sm font-bold rounded-xl flex-shrink-0 shadow-lg">Go</button>
                </form>
            </div>

            {{-- Skeleton loader (shown while navigating) --}}
            <div id="results-skeleton" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @for($s = 0; $s < 6; $s++)
                <div class="glass-card rounded-3xl overflow-hidden shadow-lg border border-gray-100 dark:border-gray-800">
                    <div class="h-24 bg-gray-200 dark:bg-gray-800 animate-pulse"></div>
                    <div class="px-6 pb-6 pt-2">
                        <div class="flex items-end gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-gray-300 dark:bg-gray-700 animate-pulse -mt-8 ring-4 ring-white dark:ring-gray-900"></div>
                            <div class="flex-1 pt-2">
                                <div class="h-4 bg-gray-200 dark:bg-gray-800 rounded w-3/4 mb-2 animate-pulse"></div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-800 rounded w-1/2 animate-pulse"></div>
                            </div>
                        </div>
                        <div class="flex gap-2 mb-4">
                            <div class="h-5 w-16 bg-gray-200 dark:bg-gray-800 rounded-full animate-pulse"></div>
                            <div class="h-5 w-20 bg-gray-200 dark:bg-gray-800 rounded-full animate-pulse"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-3 bg-gray-200 dark:bg-gray-800 rounded w-full animate-pulse"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-800 rounded w-5/6 animate-pulse"></div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <div id="search-results-container">
                @include('partials.search-results')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function searchPage() {
    return {
        activeTag: '{{ request('tech_tag') }}',
        setTechTag(tag) {
            if (this.activeTag === tag) {
                this.activeTag = '';
            } else {
                this.activeTag = tag;
            }
            document.getElementById('active-tech-tag').value = this.activeTag;
            this.updateResults();
        },
        resetFilters() {
            const form = document.getElementById('filter-form');
            form.reset();
            
            // Explicitly clear radio button selections
            form.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
            
            // Clear active tag
            this.activeTag = '';
            document.getElementById('active-tech-tag').value = '';
            
            // If budget sliders exist reset them
            const minSlider = form.querySelector('input[name="budget_min"]');
            if (minSlider) {
                minSlider.value = 0;
                this.$root.querySelector('[x-data="budgetSlider()"]').__x.$data.minVal = 0;
            }
            const maxSlider = form.querySelector('input[name="budget_max"]');
            if (maxSlider) {
                maxSlider.value = 5000000;
                this.$root.querySelector('[x-data="budgetSlider()"]').__x.$data.maxVal = 5000000;
            }

            this.updateResults();
        },
        updateResults(page = null) {
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            params.set('ajax', '1');
            if (page) {
                params.set('page', page);
            }
            
            // Show loading skeleton
            document.body.classList.add('searching');
            const skeleton = document.getElementById('results-skeleton');
            if (skeleton) skeleton.style.display = 'grid';
            
            fetch('{{ route("search") }}?' + params.toString())
                .then(res => res.text())
                .then(html => {
                    document.getElementById('search-results-container').innerHTML = html;
                    
                    // Staggered reveal visible micro-animation
                    document.querySelectorAll('#search-results-container .reveal').forEach((el, index) => {
                        setTimeout(() => el.classList.add('visible'), index * 50);
                    });
                    
                    document.body.classList.remove('searching');
                    if (skeleton) skeleton.style.display = 'none';
                    
                    // Smooth scroll up to top of results grid on page changes
                    if (page) {
                        document.getElementById('search-results-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                    
                    // Update browser history URL
                    params.delete('ajax');
                    const newUrl = '{{ route("search") }}?' + params.toString();
                    window.history.pushState({ path: newUrl }, '', newUrl);
                })
                .catch(err => {
                    console.error("AJAX search failed", err);
                    document.body.classList.remove('searching');
                    if (skeleton) skeleton.style.display = 'none';
                });
        },
        init() {
            // Intercept pagination link clicks dynamically
            document.getElementById('search-results-container').addEventListener('click', e => {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const page = url.searchParams.get('page');
                    this.updateResults(page);
                }
            });
        }
    };
}

function searchSuggest() {
    return {
        suggestions: [],
        show: false,
        async fetchSuggestions(q) {
            if (q.length < 2) { this.suggestions = []; this.show = false; return; }
            try {
                const res = await fetch('{{ route("search.suggestions") }}?q=' + encodeURIComponent(q));
                this.suggestions = await res.json();
                this.show = this.suggestions.length > 0;
            } catch(e) {}
        },
    };
}

function budgetSlider() {
    return {
        minVal: {{ request('budget_min', 0) }},
        maxVal: {{ request('budget_max', 5000000) }},
    };
}
</script>
@endpush
@endsection
