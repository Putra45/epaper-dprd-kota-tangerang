@props([
    'edition',
    'size' => 'shelf', // shelf | hero
])

@php
    $hero = $size === 'hero';
    $coverUrl = $edition->cover_url;
@endphp

@if (!empty($coverUrl))
    <div class="relative w-full h-full overflow-hidden bg-neutral-900 group/cover">
        {{-- Spine shadow overlay untuk efek buku fisik di sisi kiri --}}
        <div class="absolute inset-y-0 left-0 {{ $hero ? 'w-6 sm:w-8' : 'w-3.5 sm:w-4' }} bg-gradient-to-r from-black/40 via-black/15 to-transparent pointer-events-none z-10"></div>
        {{-- Garis pantulan kertas di samping spine --}}
        <div class="absolute inset-y-0 left-[2px] w-[1px] bg-white/20 pointer-events-none z-10"></div>

        <img src="{{ $coverUrl }}" alt="Sampul {{ $edition->category }} - {{ $edition->title }}"
             {{ $attributes->merge(['class' => 'w-full h-full object-fill']) }} loading="{{ $hero ? 'eager' : 'lazy' }}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

        {{-- Fallback jika file gambar tidak ditemukan di storage --}}
        <div style="display: none;" class="w-full h-full bg-gradient-to-br from-primary via-[#1A5A9A] to-[#0F3460] flex-col justify-between overflow-hidden select-none {{ $hero ? 'p-6 sm:p-7' : 'p-4' }}" role="img">
            <div class="border-b border-red-200/25 pb-1.5 flex justify-between items-center">
                <span class="text-[9px] font-bold uppercase tracking-wider text-white/90">DPRD Kota Tangerang</span>
                <span class="text-[9px] font-semibold bg-white/20 px-2 py-0.5 rounded text-white tracking-wider uppercase">{{ $edition->category }}</span>
            </div>
            <p class="mt-2 text-xs font-bold text-accent line-clamp-2">{{ $edition->title }}</p>
            <div class="border-t border-[#1A5A9A]/40 pt-2">
                <p class="font-serif italic font-bold text-white text-base">{{ ucfirst($edition->category) }}</p>
            </div>
        </div>
    </div>
@else
    {{-- Sampul cadangan bergaya buku, dipakai jika gambar sampul dari PDF belum selesai diproses --}}
    <div {{ $attributes->merge(['class' => 'relative w-full h-full bg-gradient-to-br from-primary via-[#1A5A9A] to-[#0F3460] flex flex-col justify-between overflow-hidden select-none ' . ($hero ? 'p-6 sm:p-7' : 'p-4')]) }} role="img" aria-label="Sampul {{ $edition->category }} - {{ $edition->title }}">
        <div class="absolute inset-y-0 left-0 {{ $hero ? 'w-8' : 'w-4' }} cover-spine pointer-events-none z-20"></div>

        <div class="relative z-10 {{ $hero ? 'pt-1' : '' }}">
            <div class="flex items-center justify-between border-b border-[#1A5A9A]/40 {{ $hero ? 'pb-3' : 'pb-1.5' }}">
                <span class="{{ $hero ? 'text-[10px] sm:text-xs tracking-widest' : 'text-[9px] tracking-wider' }} font-bold uppercase text-white/90">DPRD Kota Tangerang</span>
                <span class="text-[9px] font-semibold bg-white/20 px-2 py-0.5 rounded text-white tracking-wider uppercase">{{ $edition->category }}</span>
            </div>
            <p class="mt-3 text-xs sm:text-sm tracking-wide font-extrabold text-accent line-clamp-2">{{ $edition->title }}</p>
            <p class="text-[10px] text-white/70 tracking-wide mt-0.5">{{ $edition->release_date ? $edition->release_date->translatedFormat('d M Y') : '' }}</p>
        </div>

        <div class="relative z-10 my-auto text-center">
            <div class="mx-auto {{ $hero ? 'w-16 h-16' : 'w-10 h-10' }} rounded-full border border-white/20 flex items-center justify-center bg-black/15">
                <svg class="{{ $hero ? 'w-8 h-8' : 'w-5 h-5' }} text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>
            </div>
            <p class="text-[10px] tracking-widest uppercase font-semibold text-white/80 mt-2">ePaper Resmi</p>
        </div>

        <div class="relative z-10 border-t border-[#1A5A9A]/40 {{ $hero ? 'pt-3' : 'pt-2' }}">
            <p class="font-serif italic font-bold text-white leading-tight {{ $hero ? 'text-2xl sm:text-3xl drop-shadow-md' : 'text-base sm:text-lg' }}">{{ ucfirst($edition->category) }}</p>
            @if ($hero)
                <p class="text-[11px] text-white/80 font-medium tracking-wide mt-1">Sekretariat DPRD Kota Tangerang</p>
            @endif
        </div>
    </div>
@endif
