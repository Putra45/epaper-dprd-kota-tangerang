<x-app-layout>
    <x-slot name="title">Arsip — E-Paper DPRD Kota Tangerang</x-slot>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16" aria-labelledby="judul-arsip">
        <div class="pb-8 border-b border-neutral-200">
            <h1 id="judul-arsip" class="font-serif text-3xl sm:text-4xl font-bold text-neutral-900 tracking-tight">Arsip</h1>
            <p class="text-sm text-neutral-500 mt-1.5">Terbitan DPRD Kota Tangerang, dikelompokkan menurut tahun.</p>
        </div>

        @foreach ($archives as $year => $items)
            <div class="mt-12" aria-labelledby="tahun-{{ $year }}">
                <div class="flex items-baseline gap-3 mb-6">
                    <h2 id="tahun-{{ $year }}" class="font-serif text-2xl font-bold text-neutral-900">{{ $year }}</h2>
                    <span class="text-sm text-neutral-500">{{ count($items) }} edisi</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 sm:gap-7 lg:gap-8">
                    @foreach ($items as $edition)
                        <x-edition-card :edition="$edition" />
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
</x-app-layout>
