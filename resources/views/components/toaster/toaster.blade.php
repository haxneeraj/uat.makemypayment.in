{{--
  Toaster component.
  Place once in your layout: <x-toaster.toaster />
  Trigger from Livewire: $this->dispatch('toast', type: 'success', message: 'Done!')
--}}
@php
$positions = [
    'top-right'     => 'top-8 right-8',
    'top-left'      => 'top-8 left-8',
    'top-center'    => 'top-8 left-1/2 -translate-x-1/2',
    'bottom-right'  => 'bottom-8 right-8',
    'bottom-left'   => 'bottom-8 left-8',
    'bottom-center' => 'bottom-8 left-1/2 -translate-x-1/2',
];
@endphp

<div
    x-data="{
        toastClasses(type) {
            const map = {
                success: 'bg-white border-t-4 border-t-emerald-500',
                error:   'bg-white border-t-4 border-t-red-500',
                warning: 'bg-white border-t-4 border-t-amber-500',
                info:    'bg-white border-t-4 border-t-blue-500',
            };
            return map[type] ?? map.info;
        },
        titleColor(type) {
            const map = {
                success: 'text-emerald-700',
                error:   'text-red-700',
                warning: 'text-amber-700',
                info:    'text-blue-700',
            };
            return map[type] ?? map.info;
        },
        progressColor(type) {
            const map = {
                success: 'bg-emerald-500',
                error:   'bg-red-500',
                warning: 'bg-amber-500',
                info:    'bg-blue-500',
            };
            return map[type] ?? map.info;
        },
        iconPath(type) {
            const icons = {
                success: '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5 text-emerald-600\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M22 11.08V12a10 10 0 1 1-5.93-9.14\'/><polyline points=\'22 4 12 14.01 9 11.01\'/></svg>',
                error:   '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5 text-red-600\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><circle cx=\'12\' cy=\'12\' r=\'10\'/><line x1=\'15\' y1=\'9\' x2=\'9\' y2=\'15\'/><line x1=\'9\' y1=\'9\' x2=\'15\' y2=\'15\'/></svg>',
                warning: '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5 text-amber-600\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z\'/><line x1=\'12\' y1=\'9\' x2=\'12\' y2=\'13\'/><line x1=\'12\' y1=\'17\' x2=\'12.01\' y2=\'17\'/></svg>',
                info:    '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5 text-blue-600\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><circle cx=\'12\' cy=\'12\' r=\'10\'/><line x1=\'12\' y1=\'8\' x2=\'12\' y2=\'12\'/><line x1=\'12\' y1=\'16\' x2=\'12.01\' y2=\'16\'/></svg>',
            };
            return icons[type] ?? icons.info;
        },
    }"
>
    @foreach($positions as $pos => $classes)
    {{-- One fixed container per position --}}
    <div class="fixed z-[9999] flex flex-col gap-2 pointer-events-none w-80 {{ $classes }}">
        <template
            x-for="toast in ($store.toaster?.toasts ?? []).filter(t => t.position === '{{ $pos }}')"
            :key="toast.id"
        >
            <div
                class="pointer-events-auto relative w-full rounded-lg shadow-lg overflow-hidden"
                :class="toastClasses(toast.type)"
                x-show="toast.visible"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                @mouseenter="$store.toaster.pause(toast.id)"
                @mouseleave="$store.toaster.resume(toast.id)"
                role="alert"
                aria-live="assertive"
            >
                {{-- Body --}}
                <div class="flex items-start gap-3 px-4 py-3">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-0.5" x-html="iconPath(toast.type)"></div>

                    {{-- Text --}}
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-sm font-semibold leading-snug"
                            :class="titleColor(toast.type)"
                            x-show="toast.title"
                            x-text="toast.title"
                        ></p>
                        <p
                            class="text-sm text-slate-600 leading-snug"
                            :class="toast.title ? 'mt-0.5' : ''"
                            x-text="toast.message"
                        ></p>
                    </div>

                    {{-- Close button --}}
                    <button
                        type="button"
                        class="flex-shrink-0 -mt-0.5 -mr-1 p-1 rounded text-slate-400 hover:text-slate-600 hover:bg-slate-100/50 transition-colors focus:outline-none"
                        @click="$store.toaster.dismiss(toast.id)"
                        aria-label="Close notification"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                {{-- Progress bar --}}
                <div
                    class="absolute bottom-0 left-0 h-1 transition-[width] ease-linear"
                    :class="progressColor(toast.type)"
                    :style="`width: ${toast.progress}%; transition-duration: 40ms`"
                    x-show="toast.duration > 0"
                ></div>
            </div>
        </template>
    </div>
    @endforeach
</div>
