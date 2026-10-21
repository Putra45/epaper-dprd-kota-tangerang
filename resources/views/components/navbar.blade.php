@php
    $latestSlug = \App\Support\DemoEditions::latest()['slug'];
    $links = [
        ['label' => 'Beranda', 'href' => url('/'), 'active' => request()->is('/')],
        ['label' => 'Majalah', 'href' => url('/majalah'), 'active' => request()->is('majalah*')],
        ['label' => 'Arsip', 'href' => url('/arsip'), 'active' => request()->is('arsip*')],
        ['label' => 'Tentang Kami', 'href' => '#kontak', 'active' => false],
    ];
@endphp

<header x-data="{ menu: false }" class="sticky top-0 z-50 bg-surface/95 backdrop-blur-md border-b border-[#D1DBE8]/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="flex items-center gap-3.5 group shrink-0">
            <span class="w-11 h-11 rounded-lg bg-primary flex items-center justify-center shadow-md shadow-primary/25 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                </svg>
            </span>
            <span class="flex flex-col">
                <span class="font-bold tracking-tight text-lg text-charcoal leading-tight">E-Paper DPRD</span>
                <span class="text-[11px] font-semibold tracking-wider text-muted uppercase">Kota Tangerang • Parlementaria</span>
            </span>
        </a>

        <nav class="hidden md:flex items-center gap-1 lg:gap-2 text-sm font-semibold text-muted" aria-label="Navigasi utama">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                   @if ($link['active']) aria-current="page" @endif
                   class="px-3.5 py-2 rounded-full transition-colors {{ $link['active'] ? 'text-secondary bg-secondary/10 font-bold' : 'hover:text-charcoal hover:bg-[#D1DBE8]/50' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <form action="{{ url('/majalah') }}" method="GET" role="search" class="relative hidden sm:block w-52 md:w-56 lg:w-72">
                <label for="cari-global" class="sr-only">Cari edisi atau topik</label>
                <input id="cari-global" name="q" type="search" value="{{ request('q') }}" placeholder="Cari edisi, topik..."
                       class="w-full pl-9 pr-4 py-2 text-sm bg-surface border border-[#D1DBE8] rounded-full focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary placeholder-muted transition">
                <svg class="w-4 h-4 text-muted absolute left-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
            </form>

            <a href="{{ url('/baca/' . $latestSlug) }}" class="hidden lg:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-charcoal bg-surface border border-[#D1DBE8] rounded-full hover:bg-bg shadow-sm transition">
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                Mode Flipbook
            </a>

            <button type="button" @click="menu = !menu" :aria-expanded="menu" aria-controls="menu-ponsel"
                    class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-[#D1DBE8] bg-surface text-charcoal">
                <span class="sr-only">Buka menu</span>
                <svg x-show="!menu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="menu" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div id="menu-ponsel" x-show="menu" x-cloak x-transition.opacity class="md:hidden border-t border-[#D1DBE8] bg-surface">
        <div class="px-4 py-4 space-y-3">
            <form action="{{ url('/majalah') }}" method="GET" role="search" class="relative sm:hidden">
                <label for="cari-ponsel" class="sr-only">Cari edisi atau topik</label>
                <input id="cari-ponsel" name="q" type="search" placeholder="Cari edisi, topik..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm bg-surface border border-[#D1DBE8] rounded-full focus:outline-none focus:ring-2 focus:ring-secondary">
                <svg class="w-4 h-4 text-muted absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" stroke-linecap="round" stroke-width="2"/></svg>
            </form>
            <nav class="flex flex-col text-sm font-semibold text-charcoal" aria-label="Navigasi ponsel">
                @foreach ($links as $link)
                    <a href="{{ $link['href'] }}" @click="menu = false" class="px-3 py-2.5 rounded-lg {{ $link['active'] ? 'text-secondary bg-secondary/10' : 'hover:bg-[#D1DBE8]/50' }}">{{ $link['label'] }}</a>
                @endforeach
                <a href="{{ url('/baca/' . $latestSlug) }}" class="px-3 py-2.5 rounded-lg text-secondary">Baca edisi terbaru</a>
            </nav>
        </div>
    </div>
</header>
