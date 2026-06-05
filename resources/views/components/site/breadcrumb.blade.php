<section class="py-4 sm:py-4 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="inline-flex items-center text-slate-500 transition hover:text-slate-700" aria-label="Home">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 11.25L12 4l9 7.25M5.25 10.5v8.25h13.5V10.5" />
                </svg>
            </a>

            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>

            <span class="font-medium text-rose-500" aria-current="page">{{ $title }}</span>
        </nav>

        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-5xl">{{ $title }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ $description ?? '' }}</p>
    </div>
</section>
