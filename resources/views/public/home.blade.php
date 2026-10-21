<x-app-layout>
    <x-slot name="title">ePaper DPRD Kota Tangerang — Majalah Parlementaria</x-slot>

    {{-- Hero: edisi terbaru --}}
    @if($latest)
    <section class="relative bg-ink text-white overflow-hidden py-14 lg:py-20 border-b border-[#0F3460]">
        {{-- Background Foto Gedung DPRD Semi-Transparan --}}
        <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden" aria-hidden="true">
            <img src="{{ asset('images/hero-bg-dprd.jpg') }}" alt="Gedung DPRD Kota Tangerang" class="w-full h-full object-cover object-center scale-105 opacity-25">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0F3460] via-transparent to-[#0F3460]/80"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0F3460]/80 via-transparent to-[#0F3460]/80"></div>
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-crimson-light/20 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                <div class="lg:col-span-5 flex justify-center hero-book-perspective order-2 lg:order-1">
                    <a href="{{ url('/baca/' . $latest->slug) }}" class="hero-book-cover block w-64 sm:w-72 md:w-80 aspect-[1/1.34] rounded-r-md rounded-l-sm overflow-hidden border-t border-r border-red-400/20 shadow-2xl" aria-label="Baca {{ $latest->category }} Edisi {{ $latest->id }}">
                        <x-magazine-cover :edition="$latest" size="hero" />
                    </a>
                </div>

                <div class="lg:col-span-7 order-1 lg:order-2 space-y-6 text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2">
                        <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-widest text-accent bg-accent/10 border border-accent/30 rounded">EDISI TERBARU</span>
                        <span class="text-xs text-neutral-400 font-medium">{{ ucfirst($latest->category) }} &bull; {{ $latest->release_date ? $latest->release_date->translatedFormat('F Y') : '' }}</span>
                    </div>

                    <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight leading-[1.18]">{{ $latest->title }}</h1>

                    <p class="text-base sm:text-lg text-neutral-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                        {{ $latest->description ?: 'Laporan utama dari Kota Tangerang: capaian fungsi legislasi daerah, pengawasan tata kelola perkotaan, dan tindak lanjut aspirasi masyarakat.' }}
                    </p>

                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                        <a href="{{ url('/baca/' . $latest->slug) }}" class="inline-flex items-center gap-2.5 px-6 py-3 rounded-lg bg-crimson-light hover:bg-crimson-soft text-white font-semibold text-sm shadow-lg shadow-crimson/30 hover:shadow-glow transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            Baca Sekarang
                        </a>
                        @if ($latest->allow_download)
                            <a href="{{ url('/unduh/' . $latest->slug) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white/5 hover:bg-white/10 text-white font-medium text-sm border border-neutral-700 hover:border-neutral-500 transition-all">
                                <svg class="w-4 h-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                Unduh PDF
                            </a>
                        @endif
                    </div>

                    <ul class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-y-2 gap-x-4 text-xs text-neutral-400 font-medium">
                        <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-accent" aria-hidden="true"></span>{{ $latest->release_date ? $latest->release_date->translatedFormat('d F Y') : '' }}</li>
                        <li aria-hidden="true">•</li>
                        <li>{{ $latest->total_pages }} Halaman</li>
                        <li aria-hidden="true">•</li>
                        <li>Flipbook Interaktif</li>
                        @if ($latest->allow_download)
                            <li aria-hidden="true">•</li>
                            <li>PDF Tersedia</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="py-20 text-center bg-ink text-white">
        <h2 class="text-2xl font-bold">Belum ada edisi yang diterbitkan.</h2>
        <p class="text-neutral-400 mt-2">Silakan unggah PDF pertama Anda melalui panel admin.</p>
    </section>
    @endif

    {{-- Rak edisi --}}
    @if(count($shelf) > 0)
    <section class="py-14 sm:py-16 lg:py-20 bg-cream max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-labelledby="judul-rak">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-neutral-200">
            <div>
                <h2 id="judul-rak" class="font-serif text-3xl sm:text-4xl font-bold text-neutral-900 tracking-tight">Rak Edisi</h2>
                <p class="text-sm text-neutral-500 mt-1.5">Seluruh terbitan majalah Parlementaria dan buletin resmi DPRD Kota Tangerang.</p>
            </div>
            <x-shelf-filters />
        </div>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 sm:gap-7 lg:gap-8">
            @foreach ($shelf as $edition)
                <x-edition-card :edition="$edition" />
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ url('/arsip') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-full border border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 hover:border-neutral-400 transition shadow-sm">
                Lihat Semua Arsip Edisi Terdahulu
                <svg class="w-4 h-4 text-crimson-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
            </a>
        </div>
    </section>
    @endif
</x-app-layout>
