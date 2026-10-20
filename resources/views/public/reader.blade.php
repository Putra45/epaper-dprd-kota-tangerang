<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $edition->title }} - E-Paper DPRD Kota Tangerang</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- PageFlip -->
    <script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>

    <style data-purpose="flipbook-visuals">


        .paper-texture {
            background-image: radial-gradient(#f0eee8 1px, transparent 1px);
            background-size: 8px 8px;
        }

        .drop-cap::first-letter {
            float: left;
            font-size: 3.4rem;
            line-height: 0.82;
            padding-top: 4px;
            padding-right: 8px;
            padding-bottom: 2px;
            font-family: 'Merriweather', serif;
            font-weight: 700;
            color: var(--color-crimson, #B3131B);
        }

        .thumbnail-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .thumbnail-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 9999px;
        }
        .thumbnail-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
        }
        .thumbnail-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--color-crimson, #B3131B);
        }

        /* StPageFlip Styles */
        .stFp-page {
            background-color: transparent;
        }
        
        .page-wrapper {
            width: 100%;
            height: 100%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
        }
        .page-wrapper img, .page-wrapper > div {
            image-rendering: auto;
            image-rendering: high-quality;
            -webkit-backface-visibility: hidden;
        }
    </style>
</head>
<body class="bg-[#19191d] text-gray-200 min-h-screen flex flex-col font-sans select-none overflow-hidden antialiased" 
      x-data="readerData()" 
      @mousemove="checkMouse($event)" 
      @mouseleave="showHeader = false">
    <!-- TopHeader (Auto-hiding like macOS Dock) -->
    <header class="absolute top-0 left-0 right-0 w-full border-b border-white/5 bg-[#19191d] z-50 transition-transform duration-300 ease-in-out"
            :style="showHeader ? 'transform: translateY(0%);' : 'transform: translateY(-100%);'">
        <div class="max-w-[1920px] mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ $back }}" class="inline-flex items-center space-x-2 text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-full transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="text-sm font-medium">Kembali</span>
                </a>
                
                <div class="hidden md:flex items-center space-x-3 border-l border-white/10 pl-6">
                    <div>
                        <h1 class="text-sm font-bold text-white uppercase tracking-wider truncate max-w-[300px]">{{ $edition->title }}</h1>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-0.5">Sekretariat Dewan Perwakilan Rakyat Daerah Kota Tangerang</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-3 px-4 border-x border-white/10">
                    <span class="text-xs font-medium text-gray-300 font-mono tracking-tight" x-text="pageText"></span>
                </div>

                <div class="flex items-center space-x-2">
                    <button @click="toggleFullscreen" class="text-gray-400 hover:text-white p-1.5 rounded bg-white/5 hover:bg-white/10 transition mr-2" type="button" title="Layar Penuh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                    </button>
                    <button class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/5 rounded transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Bagikan
                    </button>
                    @if($edition->allow_download)
                    <a href="{{ route('download', $edition->slug) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-brand-crimson hover:bg-brand-crimsonDark rounded transition shadow-sm" target="_blank">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- FlipbookStage -->
    <main class="flex-1 relative flex items-center justify-center overflow-hidden" id="stage-container">
        <!-- Navigation Buttons -->
        <button @click="prev" x-show="currentPage > 0" class="absolute left-3 md:left-8 z-30 w-11 h-11 md:w-13 md:h-13 rounded-full bg-black/60 hover:bg-brand-crimson text-white/80 hover:text-white border border-white/10 hover:border-brand-crimson flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-2xl backdrop-blur-md focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <button @click="next" x-show="currentPage < totalPages - 1" class="absolute right-3 md:right-8 z-30 w-11 h-11 md:w-13 md:h-13 rounded-full bg-black/60 hover:bg-brand-crimson text-white/80 hover:text-white border border-white/10 hover:border-brand-crimson flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-2xl backdrop-blur-md focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </button>

        @php
            $w = $edition->flipbook_width ?? 540;
            $h = $edition->flipbook_height ?? 730;
            $maxW = $w * 2;
            $aspectRatio = $maxW / $h;
        @endphp
        <!-- Book Container -->
        <!-- Gunakan transisi untuk menggeser kontainer sehingga cover berada tepat di tengah -->
        <div id="book-container" class="relative mx-auto transition-transform duration-500 ease-out z-10" 
             style="height: 95vh; max-height: 95vh; max-width: 100%; aspect-ratio: {{ $aspectRatio }};"
             :style="currentPage === 0 ? { transform: 'translateX(-25%)' } : (currentPage === totalPages - 1 ? { transform: 'translateX(25%)' } : {})">
            <div id="flipbook" class="w-full h-full">
                @foreach($pages as $index => $page)
                    @if(in_array($page['id'], $edition->excluded_pages ?? []))
                        @continue
                    @endif
                    
                    @if($page['number'] === 1)
                        <!-- Cover Page -->
                        <div class="page text-neutral-800 bg-white">
                            <div class="page-wrapper paper-texture flex-col h-full w-full {{ !$page['hires'] && !($page['number'] === 1 && $edition->cover_url) ? 'p-8' : '' }}">
                                @if($page['hires'])
                                    <img src="{{ $page['hires'] }}" alt="Cover" class="w-full h-full object-fill">
                                @elseif($edition->cover_url)
                                    <img src="{{ $edition->cover_url }}" alt="Cover" class="w-full h-full object-fill">
                                @else
                                    <h1 class="text-6xl font-bold font-serif opacity-10">1</h1>
                                @endif
                            </div>
                        </div>
                    @else
                        @if($edition->is_spread)
                            <!-- Inner Pages (Split Spreads) -->
                            <!-- Left Page -->
                            <div class="page text-neutral-800 bg-white">
                                <div class="page-wrapper paper-texture relative overflow-hidden flex-col h-full w-full {{ !$page['hires'] ? 'p-8' : '' }}">
                                    @if($page['hires'])
                                        <div class="w-full h-full" style="background-image: url('{{ $page['hires'] }}'); background-size: 200% 100%; background-position: left center; background-repeat: no-repeat;"></div>
                                    @else
                                        <h1 class="text-6xl font-bold font-serif opacity-10">{{ $page['number'] }}L</h1>
                                    @endif
                                    <!-- Spine Shadow (Left) -->
                                    <div style="position: absolute; top: 0; bottom: 0; right: 0; width: 50px; pointer-events: none; z-index: 10; background: linear-gradient(to left, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.05) 40%, transparent 100%); mix-blend-mode: multiply;"></div>
                                </div>
                            </div>
                            <!-- Right Page -->
                            <div class="page text-neutral-800 bg-white">
                                <div class="page-wrapper paper-texture relative overflow-hidden flex-col h-full w-full {{ !$page['hires'] ? 'p-8' : '' }}">
                                    @if($page['hires'])
                                        <div class="w-full h-full" style="background-image: url('{{ $page['hires'] }}'); background-size: 200% 100%; background-position: right center; background-repeat: no-repeat;"></div>
                                    @else
                                        <h1 class="text-6xl font-bold font-serif opacity-10">{{ $page['number'] }}R</h1>
                                    @endif
                                    <!-- Spine Shadow (Right) -->
                                    <div style="position: absolute; top: 0; bottom: 0; left: 0; width: 50px; pointer-events: none; z-index: 10; background: linear-gradient(to right, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.05) 40%, transparent 100%); mix-blend-mode: multiply;"></div>
                                </div>
                            </div>
                        @else
                            <!-- Normal Single Page -->
                            <div class="page text-neutral-800 bg-white">
                                <div class="page-wrapper paper-texture relative overflow-hidden flex-col h-full w-full {{ !$page['hires'] ? 'p-8' : '' }}">
                                    @if($page['hires'])
                                        <img src="{{ $page['hires'] }}" alt="Page {{ $page['number'] }}" class="w-full h-full object-fill">
                                    @else
                                        <h1 class="text-6xl font-bold font-serif opacity-10">{{ $page['number'] }}</h1>
                                    @endif
                                    
                                    <!-- Dynamic Spine Shadow based on odd/even -->
                                    @if($page['number'] % 2 == 0)
                                        <div style="position: absolute; top: 0; bottom: 0; right: 0; width: 50px; pointer-events: none; z-index: 10; background: linear-gradient(to left, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.05) 40%, transparent 100%); mix-blend-mode: multiply;"></div>
                                    @else
                                        <div style="position: absolute; top: 0; bottom: 0; left: 0; width: 50px; pointer-events: none; z-index: 10; background: linear-gradient(to right, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.05) 40%, transparent 100%); mix-blend-mode: multiply;"></div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </main>



    <script>
        function readerData() {
            return {
                flipBook: null,
                currentPage: {{ max(0, $startPage - 1) }},
                totalPages: 1,
                showHeader: false,
                checkMouse(e) {
                    this.showHeader = e.clientY < 90;
                },
                
                get pageText() {
                    if (this.currentPage === 0) return `Halaman 1 dari ${this.totalPages}`;
                    if (this.currentPage === this.totalPages - 1) return `Halaman ${this.totalPages} dari ${this.totalPages}`;
                    
                    let p1 = this.currentPage % 2 === 0 ? this.currentPage : this.currentPage + 1;
                    let p2 = p1 + 1;
                    return `Halaman ${p1}-${Math.min(p2, this.totalPages)} dari ${this.totalPages}`;
                },
                
                init() {
                    const el = document.getElementById('flipbook');
                    this.flipBook = new St.PageFlip(el, {
                        width: {{ $edition->flipbook_width ?? 540 }},
                        height: {{ $edition->flipbook_height ?? 730 }},
                        size: "stretch",
                        minWidth: 315,
                        maxWidth: {{ $edition->flipbook_width ?? 540 }},
                        minHeight: 420,
                        maxHeight: {{ $edition->flipbook_height ?? 730 }},
                        maxShadowOpacity: 0.5,
                        showCover: true,
                        mobileScrollSupport: false,
                        usePortrait: true
                    });
                    
                    this.flipBook.loadFromHTML(document.querySelectorAll('.page'));
                    this.totalPages = this.flipBook.getPageCount();
                    
                    if (this.currentPage > 0) {
                        this.flipBook.turnToPage(this.currentPage);
                    }
                    
                    this.flipBook.on('flip', (e) => {
                        this.currentPage = e.data;
                    });
                    
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowRight') this.next();
                        if (e.key === 'ArrowLeft') this.prev();
                    });
                },
                
                next() {
                    if (this.flipBook) this.flipBook.flipNext();
                },
                
                prev() {
                    if (this.flipBook) this.flipBook.flipPrev();
                },
                
                goToPage(pageIndex) {
                    if (this.flipBook) this.flipBook.flip(pageIndex);
                },
                
                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>
