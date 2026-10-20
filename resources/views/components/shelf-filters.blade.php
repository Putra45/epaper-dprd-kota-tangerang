@props(['action' => url('/majalah')])

@php
    $category = request('kategori');
    $year = request('tahun');
    $pills = [['label' => 'Semua', 'query' => [], 'active' => !$category && !$year]];
    
    $dbCategories = \App\Models\Edition::where('status', 'published')->pluck('category')->unique();
    foreach ($dbCategories as $cat) {
        $pills[] = ['label' => ucfirst($cat), 'query' => ['kategori' => $cat], 'active' => $category === $cat];
    }
    
    $dbYears = \App\Models\Edition::where('status', 'published')->orderBy('release_date', 'desc')->pluck('release_date')->map->format('Y')->unique()->take(3);
    foreach ($dbYears as $y) {
        $pills[] = ['label' => (string) $y, 'query' => ['tahun' => $y], 'active' => (string) $year === (string) $y];
    }
@endphp

<nav class="flex flex-wrap items-center gap-2" aria-label="Saring edisi">
    @foreach ($pills as $pill)
        <a href="{{ $action . ($pill['query'] ? '?' . http_build_query($pill['query']) : '') }}"
           @if ($pill['active']) aria-current="true" @endif
           class="px-4 py-1.5 text-sm font-semibold rounded-full transition {{ $pill['active'] ? 'bg-crimson text-white shadow-sm hover:bg-crimson-dark' : 'bg-white text-neutral-700 border border-neutral-300 hover:bg-neutral-100' }}">
            {{ $pill['label'] }}
        </a>
    @endforeach
</nav>
