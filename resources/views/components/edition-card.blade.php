@props(['edition'])

@php
    $isModel = $edition instanceof \App\Models\Edition;
    $slug = $isModel ? $edition->slug : ($edition['slug'] ?? '');
    $title = $isModel ? $edition->title : ($edition['title'] ?? 'Edisi Majalah');
    $category = $isModel ? $edition->category : ($edition['category'] ?? 'majalah');
    $releaseDate = $isModel ? $edition->release_date : ($edition['release_date'] ?? null);
    $totalPages = $isModel ? $edition->total_pages : ($edition['total_pages'] ?? 0);
    $description = $isModel ? $edition->description : ($edition['description'] ?? '');
    $allowDownload = $isModel ? $edition->allow_download : ($edition['allow_download'] ?? false);
    $readUrl = url('/baca/' . $slug);
    $downloadUrl = url('/unduh/' . $slug);
@endphp

<article class="group flex flex-col h-full bg-white rounded-xl p-3 sm:p-3.5 border border-neutral-200/80 hover:border-crimson-light/40 shadow-xs hover:shadow-lg transition-all duration-300">
    {{-- Area Sampul Majalah --}}
    <div class="relative w-full aspect-[1/1.34] rounded-lg overflow-hidden bg-neutral-100 shadow-sm group-hover:shadow-md transition-all duration-300">
        <a href="{{ $readUrl }}" class="block w-full h-full" tabindex="-1" aria-hidden="true">
            <x-magazine-cover :edition="$edition" />
        </a>

        {{-- Aksi Cepat Saat Hover --}}
        <div class="absolute inset-0 z-30 bg-black/60 backdrop-blur-[2px] flex flex-col items-center justify-center gap-2 p-3 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto group-focus-within:opacity-100 group-focus-within:pointer-events-auto transition-opacity duration-200">
            <a href="{{ $readUrl }}" class="w-full py-2 px-3 bg-crimson-light hover:bg-crimson-soft text-white text-xs font-semibold rounded-lg text-center shadow-md transition active:scale-95 flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                Baca Flipbook
            </a>
            @if ($allowDownload)
                <a href="{{ $downloadUrl }}" class="w-full py-1.5 px-3 bg-white/20 hover:bg-white/30 text-white text-xs font-medium rounded-lg text-center transition flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    Unduh PDF
                </a>
            @endif
        </div>
    </div>

    {{-- Area Konten: Judul, Meta & Deskripsi di Bawah Cover --}}
    <div class="mt-3.5 flex flex-col flex-grow">
        {{-- Metadata Row: Kategori, Tanggal & Halaman --}}
        <div class="flex items-center justify-between text-[11px] text-neutral-500 font-medium mb-1.5">
            <div class="flex items-center gap-1.5">
                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-crimson-light bg-crimson/10 border border-crimson/20 rounded">
                    {{ $category }}
                </span>
                <span>{{ $releaseDate ? ($releaseDate instanceof \Carbon\Carbon ? $releaseDate->translatedFormat('d M Y') : date('d M Y', strtotime($releaseDate))) : 'Terbaru' }}</span>
            </div>
            @if($totalPages > 0)
                <span class="flex items-center gap-1 text-neutral-400">
                    {{ $totalPages }} Hlm
                </span>
            @endif
        </div>

        {{-- Judul Edisi --}}
        <h3 class="text-sm font-bold text-neutral-900 group-hover:text-crimson-light transition-colors leading-snug line-clamp-2">
            <a href="{{ $readUrl }}">{{ $title }}</a>
        </h3>

        {{-- Ringkasan Deskripsi --}}
        <p class="text-xs text-neutral-500 line-clamp-2 leading-relaxed mt-1.5 font-normal flex-grow">
            {{ $description ?: 'Edisi resmi publikasi DPRD Kota Tangerang menyajikan ragam informasi kegiatan legislasi dan aspirasi masyarakat.' }}
        </p>

        {{-- Action Link --}}
        <div class="mt-3 pt-2.5 border-t border-neutral-100 flex items-center justify-between">
            <a href="{{ $readUrl }}" class="inline-flex items-center gap-1 text-xs font-semibold text-crimson-light group-hover:text-crimson-soft transition-colors">
                Baca Sekarang
                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
            </a>
            @if ($allowDownload)
                <a href="{{ $downloadUrl }}" class="text-neutral-400 hover:text-neutral-700 transition-colors p-1" title="Unduh PDF" aria-label="Unduh PDF {{ $title }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </a>
            @endif
        </div>
    </div>
</article>
