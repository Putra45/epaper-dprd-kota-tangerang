@props(['title', 'url'])

<div x-data="{ open: false, copied: false }" @keydown.window.escape="open = false" id="share-modal-container">
    <!-- Trigger Button -->
    <button @click="open = true" type="button" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-stone-800 hover:bg-stone-700 text-stone-200 text-xs font-semibold transition-colors">
        <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
        </svg>
        <span class="hidden sm:inline">Bagikan</span>
    </button>

    <!-- Modal Backdrop & Dialog -->
    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <!-- Backdrop -->
            <div @click="open = false" x-show="open" x-transition.opacity class="fixed inset-0 bg-stone-900/70 backdrop-blur-xs"></div>

            <!-- Modal Content -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="open" x-transition class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all border border-[var(--color-neutral-border)]">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <h3 class="text-base font-bold text-[var(--color-deep-charcoal)]">Bagikan E-Paper Ini</h3>
                        <button @click="open = false" type="button" class="text-stone-400 hover:text-stone-600 p-1">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="mt-4 space-y-4">
                        <p class="text-xs text-stone-500 line-clamp-2">
                            {{ $title }}
                        </p>

                        <!-- Share Buttons Grid -->
                        <div class="grid grid-cols-3 gap-3 pt-2">
                            <!-- WhatsApp -->
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($title . ' ' . request()->fullUrl()) }}" target="_blank" class="flex flex-col items-center justify-center p-3 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                                <span class="text-lg font-bold">WA</span>
                                <span class="text-[10px] font-bold mt-1">WhatsApp</span>
                            </a>

                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="flex flex-col items-center justify-center p-3 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                <span class="text-lg font-bold">FB</span>
                                <span class="text-[10px] font-bold mt-1">Facebook</span>
                            </a>

                            <!-- X / Twitter -->
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($title) }}&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="flex flex-col items-center justify-center p-3 rounded-xl bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                                <span class="text-lg font-bold">X</span>
                                <span class="text-[10px] font-bold mt-1">Twitter (X)</span>
                            </a>
                        </div>

                        <!-- Copy Link Input -->
                        <div class="pt-3">
                            <label class="block text-xs font-bold text-stone-700 mb-1">Tautan Langsung / Deep Link:</label>
                            <div class="flex items-center gap-2">
                                <input type="text" readonly value="{{ request()->fullUrl() }}" class="flex-grow px-3 py-2 rounded-lg bg-stone-100 border border-stone-200 text-xs font-mono text-stone-700 select-all focus:outline-none">
                                <button @click="navigator.clipboard.writeText('{{ request()->fullUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)" type="button" class="px-3 py-2 rounded-lg bg-[var(--color-primary-red)] text-white text-xs font-bold hover:bg-[var(--color-primary-red-hover)] transition-colors shrink-0">
                                    <span x-show="!copied">Salin</span>
                                    <span x-show="copied" style="display: none;">Tersalin!</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
