@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between pt-4 mt-1">

        {{-- Mobile Pagination --}}
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl border border-[#dde2ef] bg-[#f8f9fc] text-sm font-semibold text-slate-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </span>
            @else
                <button type="button" wire:click="previousPage" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl border border-[#dde2ef] bg-white text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:border-indigo-200 hover:text-[#2b3990] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl border border-[#dde2ef] bg-white text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:border-indigo-200 hover:text-[#2b3990] transition">
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl border border-[#dde2ef] bg-[#f8f9fc] text-sm font-semibold text-slate-400 cursor-not-allowed">
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>

        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">

            {{-- Result Info --}}
            <p class="text-sm text-slate-500">
                Showing
                <span class="font-semibold text-slate-800">{{ $paginator->firstItem() ?? 0 }}</span>
                –
                <span class="font-semibold text-slate-800">{{ $paginator->lastItem() ?? 0 }}</span>
                of
                <span class="font-semibold text-slate-800">{{ $paginator->total() }}</span>
                results
            </p>

            {{-- Page Buttons --}}
            <div class="flex items-center gap-1">

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl border border-[#dde2ef] bg-[#f8f9fc] text-slate-400 cursor-not-allowed">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <button type="button"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-2xl border border-[#dde2ef] bg-white text-slate-500 hover:bg-indigo-50 hover:border-indigo-200 hover:text-[#2b3990] transition">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif

                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl text-sm font-semibold text-slate-400 bg-[#f8f9fc] border border-[#dde2ef]">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}"
                                    aria-current="page"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-2xl text-sm font-bold bg-[#2b3990] text-white shadow-sm shadow-indigo-200">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}"
                                    wire:loading.attr="disabled"
                                    aria-label="Go to page {{ $page }}"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-2xl text-sm font-semibold text-slate-700 border border-[#dde2ef] bg-white hover:bg-indigo-50 hover:border-indigo-200 hover:text-[#2b3990] transition">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <button type="button"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-2xl border border-[#dde2ef] bg-white text-slate-500 hover:bg-indigo-50 hover:border-indigo-200 hover:text-[#2b3990] transition">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl border border-[#dde2ef] bg-[#f8f9fc] text-slate-400 cursor-not-allowed">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif

            </div>
        </div>
    </nav>
@endif
