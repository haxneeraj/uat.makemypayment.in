/**
 * Toaser – lightweight Alpine.js toast/notification store.
 *
 * Usage from a Livewire component:
 *   $this->dispatch('toast', type: 'success', message: 'Done!');
 *   $this->dispatch('toast', type: 'error',   title: 'Oops', message: 'Something went wrong.');
 *
 * Supported options (all optional except message):
 *   type     : 'success' | 'error' | 'warning' | 'info'  (default: 'info')
 *   title    : string
 *   message  : string
 *   duration : ms before auto-dismiss, 0 = never          (default: 4000)
 *   position : 'top-right' | 'top-left' | 'top-center'
 *              'bottom-right' | 'bottom-left' | 'bottom-center'
 *                                                          (default: 'top-right')
 *
 * JS API:
 *   Alpine.store('toaster').success('Saved!')
 *   Alpine.store('toaster').error('Failed.', { title: 'Error', duration: 0 })
 *   Alpine.store('toaster').add({ type, title, message, duration, position })
 *   Alpine.store('toaster').dismiss(id)
 */

let _seq = 0;
const _timers = {};

const toasterStore = {
    toasts: [],

    /** Main entry point – add a toast and start its countdown. */
    add(opts = {}) {
        const id       = ++_seq;
        const duration = opts.duration !== undefined ? opts.duration : 4000;

        this.toasts.push({
            id,
            type:     opts.type     || 'info',
            title:    opts.title    || null,
            message:  opts.message  || '',
            position: opts.position || 'top-right',
            duration,
            progress: 100,
            paused:   false,
            visible:  true,
        });

        if (duration > 0) {
            const step = 40;                                   // interval ms
            const dec  = (100 / duration) * step;
            _timers[id] = setInterval(() => {
                const t = this.toasts.find(t => t.id === id);
                if (!t) { clearInterval(_timers[id]); delete _timers[id]; return; }
                if (t.paused) return;
                t.progress -= dec;
                if (t.progress <= 0) { t.progress = 0; this.dismiss(id); }
            }, step);
        }

        return id;
    },

    dismiss(id) {
        if (_timers[id]) { clearInterval(_timers[id]); delete _timers[id]; }
        const i = this.toasts.findIndex(t => t.id === id);
        if (i !== -1) this.toasts.splice(i, 1);
    },

    pause(id) {
        const t = this.toasts.find(t => t.id === id);
        if (t) t.paused = true;
    },

    resume(id) {
        const t = this.toasts.find(t => t.id === id);
        if (t) t.paused = false;
    },

    /** Convenience helpers */
    success(message, opts = {}) { return this.add({ ...opts, type: 'success', message }); },
    error  (message, opts = {}) { return this.add({ ...opts, type: 'error',   message }); },
    warning(message, opts = {}) { return this.add({ ...opts, type: 'warning', message }); },
    info   (message, opts = {}) { return this.add({ ...opts, type: 'info',    message }); },
};

/**
 * Register the Alpine store + browser-event bridge.
 * Call this in your app.js BEFORE Alpine boots
 * (i.e. inside a 'alpine:init' listener, which Livewire fires automatically).
 */
export function setupToaser() {
    document.addEventListener('alpine:init', () => {
        Alpine.store('toaster', toasterStore);
    });

    // Bridge: Livewire $this->dispatch('toast', ...) → store
    window.addEventListener('toast', (e) => {
        const detail = e.detail ?? {};
        // Livewire 4 wraps named params as an array-like – normalise
        const opts = Array.isArray(detail) ? (detail[0] ?? {}) : detail;
        if (window.Alpine) {
            Alpine.store('toaster').add(opts);
        }
    });
}
