<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'Majalah digital resmi Sekretariat DPRD Kota Tangerang. Baca gratis, kapan saja, di perangkat apa saja.' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? 'Majalah digital resmi Sekretariat DPRD Kota Tangerang.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="id_ID">

    @include('partials.fonts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-cream text-neutral-800 font-sans antialiased min-h-screen flex flex-col selection:bg-crimson selection:text-white">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:px-4 focus:py-2 focus:bg-crimson focus:text-white focus:rounded-md">Lewati ke konten</a>

    <x-navbar />

    <main id="konten" class="flex-grow">
        {{ $slot }}
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
