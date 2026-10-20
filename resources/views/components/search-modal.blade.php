<div x-data="{ open: false }" @keydown.window.escape="open = false" id="search-modal-container">
    <!-- Trigger Button -->
    <button @click="open = true" type="button" class="flex items-center gap-2 px-4 py-2 rounded-lg border border-[var(--color-neutral-border)] text-xs font-semibold text-[var(--color-deep-charcoal)] bg-white hover:bg-stone-50 transition-all shadow-2xs">
        <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <span>Cari Edisi & Teks...</span>
    </button>

    <!-- Modal Backdrop & Dialog -->
    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <!-- Backdrop -->
            <div @click="open = false" x-show="open" x-transition.opacity class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs"></div>

            <!-- Modal Content -->
            <div class="flex min-h-full items-start justify-center p-4 pt-16 sm:p-6 sm:pt-20">
                <div x-show="open" x-transition class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all border border-[var(--color-neutral-border)]">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <h3 class="text-base font-bold text-[var(--color-deep-charcoal)]">Pencarian E-Paper</h3>
                        <button @click="open = false" type="button" class="text-stone-400 hover:text-stone-600 p-1">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ url('/majalah') }}" method="GET" class="mt-4">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Ketik kata kunci atau judul majalah..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-[var(--color-neutral-border)] text-sm focus:outline-none focus:border-[var(--color-primary-red)] focus:ring-1 focus:ring-[var(--color-primary-red)] bg-[var(--color-warm-cream)]" autofocus>
                            <svg class="w-5 h-5 text-stone-400 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <div class="mt-4 flex items-center justify-between text-xs text-stone-500">
                            <span>Pencarian mendukung kata kunci dalam edisi & teks dokumen.</span>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-[var(--color-primary-red)] text-white font-bold hover:bg-[var(--color-primary-red-hover)] transition-colors">
                                Cari Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
