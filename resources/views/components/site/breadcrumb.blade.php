<div
    class="w-full h-[100px] relative flex items-center justify-between bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 px-8 md:px-20 mb-12 overflow-hidden border-b border-slate-800">
    <div class="absolute top-3 left-1/2 -translate-x-1/2 flex items-center gap-2 px-4 py-1 rounded-full bg-gradient-to-r from-amber-400/25 via-rose-400/25 to-indigo-500/25 border border-amber-300/60 shadow-lg shadow-amber-400/30 backdrop-blur">
        <span class="h-2 w-2 rounded-full bg-amber-300 animate-ping"></span>
        <span class="text-xs font-semibold text-amber-50">Happy New Year 2026</span>
        <span class="h-2 w-2 rounded-full bg-rose-300 animate-ping delay-200"></span>
    </div>
    <div class="max-w-7xl flex items-center gap-4 z-10 px-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-slate-100 font-semibold hover:text-indigo-400 transition-colors"
            aria-label="Go to Home">
            {{-- Home SVG Icon --}}
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                aria-hidden="true">
                <path d="M3 12L12 3l9 9" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9 21V15h6v6" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M19 21H5a2 2 0 01-2-2v-7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Home</span>
        </a>
        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-100 font-bold" aria-current="page">{{ $title }}</span>
    </div>
    <div class="hidden md:block absolute inset-0 right-0 h-full pointer-events-none z-0">
        {{-- Animated Shapes (improved visibility) --}}
        <div
            class="absolute right-10 top-1/2 transform -translate-y-1/2 w-28 h-28 rounded-full bg-indigo-500/40 blur-xl animate-pulse">
        </div>
        <div class="absolute right-32 top-1/3 w-20 h-20 rounded-full bg-indigo-400/30 blur-lg animate-pulse-slow"></div>
        <div
            class="absolute right-56 top-2/3 w-24 h-24 rounded-full bg-emerald-500/30 blur-lg animate-pulse delay-300">
        </div>
        <div
            class="absolute right-80 top-1/4 w-16 h-16 rounded-full bg-emerald-400/25 blur-md animate-pulse-slow delay-500">
        </div>
        <div
            class="absolute right-[28rem] top-3/4 w-20 h-20 rounded-full bg-indigo-400/25 blur-md animate-pulse delay-700">
        </div>
        {{-- Payment-related SVGs with animation --}}
        <div class="absolute right-16 top-1/2 transform -translate-y-1/2 z-10">
            <svg class="h-10 w-10 animate-bounce" viewBox="0 0 24 24" fill="none">
                <rect x="3" y="7" width="18" height="10" rx="2" fill="#6366f1" />
                <rect x="7" y="11" width="10" height="2" rx="1" fill="#fff" />
                <circle cx="8" cy="12" r="1" fill="#fff" />
            </svg>
        </div>
        <div class="absolute right-40 top-1/3 z-10">
            <svg class="h-8 w-8 animate-spin-slow" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="#10b981" stroke-width="2" fill="#1e293b" />
                <path d="M8 12h8" stroke="#10b981" stroke-width="2" stroke-linecap="round" />
                <path d="M12 8v8" stroke="#10b981" stroke-width="2" stroke-linecap="round" />
            </svg>
        </div>
        <div class="absolute right-64 top-2/3 z-10">
            <svg class="h-9 w-9 animate-bounce delay-500" viewBox="0 0 24 24" fill="none">
                <rect x="6" y="6" width="12" height="12" rx="3" fill="#10b981" />
                <path d="M9 12h6" stroke="#fff" stroke-width="2" stroke-linecap="round" />
            </svg>
        </div>
        <div class="absolute right-96 top-1/4 z-10">
            <svg class="h-7 w-7 animate-bounce delay-700" viewBox="0 0 24 24" fill="none">
                <ellipse cx="12" cy="12" rx="8" ry="5" fill="#6366f1" />
                <path d="M8 12h8" stroke="#fff" stroke-width="2" stroke-linecap="round" />
            </svg>
        </div>
        <div class="absolute right-6 top-6 h-3 w-3 rounded-full bg-amber-300/90 animate-ping"></div>
        <div class="absolute right-14 top-10 h-2.5 w-2.5 rounded-full bg-emerald-300/80 animate-ping delay-200"></div>
        <div class="absolute right-24 top-5 z-10">
            <svg class="h-6 w-6 animate-spin-slow" viewBox="0 0 24 24" fill="none">
                <path d="M12 2l1.5 4.5L18 8l-4.5 1.5L12 14l-1.5-4.5L6 8l4.5-1.5L12 2z" fill="#fcd34d" />
                <circle cx="12" cy="12" r="1.2" fill="#fff" />
            </svg>
        </div>
        <div class="absolute right-12 bottom-6 z-10">
            <svg class="h-8 w-8 animate-pulse" viewBox="0 0 24 24" fill="none">
                <path d="M12 4v4M12 16v4M4 12h4M16 12h4M6.5 6.5l2.5 2.5M15 15l2.5 2.5M6.5 17.5L9 15M15 9l2.5-2.5" stroke="#f97316" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="12" r="2" fill="#fcd34d" />
            </svg>
        </div>
        <div class="absolute right-36 bottom-10 z-10">
            <svg class="h-7 w-7 animate-spin-slow" viewBox="0 0 24 24" fill="none">
                <path d="M12 3l1 3 3 1-3 1-1 3-1-3-3-1 3-1 1-3z" fill="#22d3ee" />
                <circle cx="12" cy="12" r="1.3" fill="#fff" />
            </svg>
        </div>
        <div class="absolute right-20 top-16 h-2 w-2 rounded-full bg-rose-300 animate-ping delay-300"></div>
        <div class="absolute right-[22rem] top-10 z-10">
            <svg class="h-9 w-9 animate-bounce delay-300" viewBox="0 0 24 24" fill="none">
                <path d="M12 2l2 5 5 2-5 2-2 5-2-5-5-2 5-2 2-5z" fill="#fb7185" />
                <circle cx="12" cy="12" r="1.5" fill="#fff" />
            </svg>
        </div>
    </div>
</div>
