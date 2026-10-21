<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin — ePaper Parlementaria DPRD Kota Tangerang')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            crimson: '#B3131B',
                            crimsonHover: '#981017',
                            crimsonLight: '#FDF2F2',
                            cream: '#FAF7F2',
                            warmBg: '#F4F2EE',
                            cardBorder: '#E8E5DF',
                            textMain: '#1F2937',
                            textMuted: '#6B7280'
                        }
                    },
                    fontFamily: {
                        serif: ['Georgia', 'Cambria', 'serif'],
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style data-purpose="custom-layout">
        body {
            background-color: #FAF7F2;
            color: #1F2937;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .dropzone-border {
            border: 2px dashed #B3131B;
        }

        .form-input-focus:focus {
            border-color: #B3131B;
            box-shadow: 0 0 0 1px #B3131B;
        }
    </style>
</head>
<body class="min-h-screen bg-[#FAF7F2] text-[#1F2937] antialiased flex flex-col justify-between">
    @if(session('admin_logged_in'))
    <header class="w-full bg-[#FAF7F2] border-b border-[#E8E5DF] sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 sm:gap-3">
                <div class="flex items-baseline space-x-2">
                    <h1 class="text-2xl sm:text-3xl font-bold font-serif tracking-tight text-gray-900">Panel Admin</h1>
                    <span class="text-sm font-medium text-gray-400">/admin</span>
                </div>
                <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-500">
                    <span class="hidden sm:inline">•</span>
                    <span class="font-medium text-gray-700">ePaper Parlementaria DPRD Kota Tangerang</span>
                    <a class="inline-flex items-center text-xs font-semibold text-[#B3131B] hover:underline ml-2 bg-[#FDF2F2] px-2 py-0.5 rounded transition" href="{{ route('home') }}">
                        &larr; Lihat Portal Utama
                    </a>
                </div>
            </div>
            
            <div class="flex items-center justify-between sm:justify-end space-x-5 text-sm">
                <div class="flex items-center space-x-2.5 pl-2 border-l border-gray-300">
                    <div class="w-8 h-8 rounded-full bg-[#B3131B] text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                        AD
                    </div>
                    <div class="hidden sm:block text-left leading-tight">
                        <p class="text-xs font-bold text-gray-900">Administrator</p>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-[11px] text-[#B3131B] hover:underline">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @endif

    <main class="w-full flex-grow">
        @yield('content')
    </main>

    <footer class="w-full border-t border-gray-200 bg-[#FAF7F2] py-4 text-center text-xs text-gray-500 mt-6">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Sekretariat DPRD Kota Tangerang. Sistem Manajemen ePaper Parlementaria.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
