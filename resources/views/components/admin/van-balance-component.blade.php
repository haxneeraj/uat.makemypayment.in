<div class="flex items-center gap-3">
    <div>
        @if($balanceVisible)
            <span class="text-2xl sm:text-3xl font-black text-slate-900">₹{{ number_format($balance, 2) }}</span>
        @else
            <span wire:loading.remove wire:target="toggleBalance" class="text-2xl sm:text-3xl font-black text-slate-900 tracking-wider">••••••</span>
            <span wire:loading wire:target="toggleBalance" class="text-2xl sm:text-3xl font-black text-slate-900 tracking-wider">
                <svg class="w-6 h-6 text-slate-400 animate-spin inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </span>
        @endif
    </div>
    <button
        wire:click="toggleBalance"
        wire:loading.attr="disabled"
        wire:target="toggleBalance"
        class="inline-flex items-center justify-center rounded-md border border-[#d8c0ff] bg-white/80 p-2 text-[#6d4ca2] hover:bg-white disabled:opacity-60 transition"
        title="Show or hide balance"
    >
        @if($balanceVisible)
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.584 10.587a2 2 0 002.829 2.829M9.88 4.24A9.955 9.955 0 0112 4c5.523 0 10 4.477 10 8 0 1.346-.654 2.594-1.757 3.64M6.228 6.228C4.217 7.529 2.777 9.328 2 12c1.273 4.057 5.063 7 9.543 7 1.53 0 2.98-.343 4.276-.955" />
            </svg>
        @else
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        @endif
    </button>
</div>
