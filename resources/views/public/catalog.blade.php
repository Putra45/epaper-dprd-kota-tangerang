<x-app-layout>
    <x-slot name="title">Majalah — ePaper DPRD Kota Tangerang</x-slot>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 bg-cream" aria-labelledby="judul-katalog">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-neutral-200">
            <div>
                <h1 id="judul-katalog" class="font-serif text-3xl sm:text-4xl font-bold text-neutral-900 tracking-tight">Majalah</h1>
                <p class="text-sm text-neutral-500 mt-1.5">
                    @if ($q)
                        {{ count($editions) }} hasil untuk "{{ $q }}".
                    @else
                        Semua edisi yang telah terbit.
                    @endif
                </p>
            </div>
            <x-shelf-filters />
        </div>

        <form method="GET" class="mt-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            @if (request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
            @if (request('tahun'))<input type="hidden" name="tahun" value="{{ request('tahun') }}">@endif

            <div class="relative w-full sm:max-w-sm">
                <label for="cari-katalog" class="sr-only">Cari judul atau nomor edisi</label>
                <input id="cari-katalog" type="search" name="q" value="{{ $q }}" placeholder="Cari judul atau nomor edisi"
                       class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-neutral-300 rounded-full focus:outline-none focus:ring-2 focus:ring-crimson-light focus:border-crimson-light">
                <svg class="w-4 h-4 text-neutral-400 absolute left-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" stroke-linecap="round" stroke-width="2"/></svg>
            </div>

            <div class="flex items-center gap-2 text-sm">
                <label for="urut" class="text-neutral-500">Urutkan</label>
                <select id="urut" name="urut" onchange="this.form.submit()" class="py-2 pl-3 pr-8 rounded-full border border-neutral-300 bg-white text-sm font-semibold text-neutral-700 focus:outline-none focus:ring-2 focus:ring-crimson-light">
                    <option value="terbaru" @selected($sort === 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected($sort === 'terlama')>Terlama</option>
                </select>
                <noscript><button class="px-3 py-2 rounded-full bg-crimson-light text-white text-sm font-semibold">Terapkan</button></noscript>
            </div>
        </form>

        @if (count($editions))
            @php
                $catalogCount = count($editions);
            @endphp
            <div class="mt-10 grid gap-6 sm:gap-7 lg:gap-8
                @if($catalogCount === 1) grid-cols-1 max-w-md mx-auto
                @elseif($catalogCount === 2) grid-cols-1 sm:grid-cols-2 max-w-3xl mx-auto
                @elseif($catalogCount === 3) grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                @else grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4
                @endif">
                @foreach ($editions as $edition)
                    <x-edition-card :edition="$edition" />
                @endforeach
            </div>
        @else
            <div class="mt-16 text-center">
                <p class="font-serif text-xl text-neutral-900">Edisi tidak ditemukan</p>
                <p class="text-sm text-neutral-500 mt-2">Coba kata kunci lain atau <a href="{{ url('/majalah') }}" class="text-crimson-light font-semibold underline underline-offset-2">tampilkan semua edisi</a>.</p>
            </div>
        @endif
    </section>
</x-app-layout>
