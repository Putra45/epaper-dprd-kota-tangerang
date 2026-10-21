@extends('layouts.admin')

@section('title', 'Review & Terbitkan Edisi — ePaper Parlementaria')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>
@endpush

@section('content')
<div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow" x-data="reviewData()">
    
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Finalisasi Edisi</h1>
            <p class="text-sm text-gray-500 mt-1">Lengkapi data majalah dan sesuaikan tampilannya sebelum diterbitkan ke publik.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-[#B3131B] transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm">
            <div class="flex items-center font-bold mb-2">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                Terjadi kesalahan:
            </div>
            <ul class="list-disc pl-9 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.editions.publish', $edition->id) }}" method="POST" enctype="multipart/form-data" id="publishForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            <!-- Kolom Kiri: Form Data -->
            <div class="xl:col-span-3 space-y-6">
                
                <!-- 1. Data Utama -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50/50 px-5 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#B3131B]/10 flex items-center justify-center text-[#B3131B]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Informasi Utama</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5" for="title">Judul Edisi</label>
                            <input class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-[#B3131B] focus:ring-[#B3131B] py-2.5 px-3" id="title" name="title" type="text" value="{{ old('title', str_starts_with($edition->title, 'Draft') ? 'Parlementaria Edisi ' : $edition->title) }}" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5" for="release_date">Tanggal</label>
                                <input class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-[#B3131B] focus:ring-[#B3131B] py-2.5 px-3" id="release_date" name="release_date" type="date" value="{{ old('release_date', $edition->release_date) }}" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5" for="category">Kategori</label>
                                <select class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-[#B3131B] focus:ring-[#B3131B] py-2.5 px-3" id="category" name="category">
                                    <option value="majalah" {{ old('category', $edition->category) == 'majalah' ? 'selected' : '' }}>Majalah</option>
                                    <option value="koran" {{ old('category', $edition->category) == 'koran' ? 'selected' : '' }}>Koran</option>
                                    <option value="buletin" {{ old('category', $edition->category) == 'buletin' ? 'selected' : '' }}>Buletin</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5" for="description">Deskripsi</label>
                            <textarea class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-[#B3131B] focus:ring-[#B3131B] py-2.5 px-3 resize-none" id="description" name="description" rows="3">{{ old('description', $edition->description) }}</textarea>
                        </div>
                        
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="allow_download" value="1" {{ old('allow_download', $edition->allow_download) ? 'checked' : '' }} class="w-4 h-4 text-[#B3131B] bg-white border-gray-300 rounded focus:ring-[#B3131B]">
                                <span class="text-sm font-medium text-gray-800">Izinkan Pengguna Mengunduh PDF</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5" for="cover_image">Ganti Sampul (Opsional)</label>
                            <input class="w-full text-sm rounded-lg border border-gray-300 shadow-sm focus:border-[#B3131B] focus:ring-[#B3131B] py-2 px-3 bg-white" id="cover_image" name="cover_image" type="file" accept="image/*">
                            <p class="text-[11px] text-gray-500 mt-1">Biarkan kosong untuk otomatis menggunakan halaman 1 PDF.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Pengaturan Layout -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50/50 px-5 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Pengaturan Tampilan</h3>
                    </div>
                    
                    <div class="p-5 space-y-6">
                        <div>
                            <div class="flex justify-between mb-2 text-sm font-medium text-gray-700">
                                <label for="width">Lebar Buku (px)</label>
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono text-xs" x-text="width"></span>
                            </div>
                            <input type="range" id="width" name="flipbook_width" min="300" max="1000" x-model="width" @input="updatePreview" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2 text-sm font-medium text-gray-700">
                                <label for="height">Tinggi Buku (px)</label>
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono text-xs" x-text="height"></span>
                            </div>
                            <input type="range" id="height" name="flipbook_height" min="400" max="1200" x-model="height" @input="updatePreview" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>

                        <div class="p-3 bg-blue-50/50 rounded-lg border border-blue-100">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input type="checkbox" name="is_spread" value="1" x-model="isSpread" @change="updatePreview" class="w-4 h-4 text-blue-600 bg-white border-blue-300 rounded focus:ring-blue-600">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-blue-900">Format Dua Halaman (Spread)</p>
                                    <p class="text-[11px] text-blue-700 mt-1 leading-relaxed">Centang jika file PDF Anda menggabungkan 2 halaman menjadi 1 lembar panjang. Sistem akan otomatis memotongnya ke tengah agar pas.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 3. Daftar Halaman -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50/50 px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">Pilih Halaman</h3>
                        </div>
                        <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-medium" x-text="includedPages.length + ' dipilih'"></span>
                    </div>
                    
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-4 px-1">Hapus centang untuk membuang halaman dari majalah final.</p>
                        <div class="max-h-[240px] overflow-y-auto space-y-1.5 pr-2 custom-scrollbar">
                            <template x-for="page in pages" :key="page.id">
                                <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-200 transition-colors" :class="!includedPages.includes(page.id.toString()) ? 'opacity-50 grayscale' : ''">
                                    <input type="checkbox" :value="page.id" x-model="includedPages" @change="updatePreview" class="w-4 h-4 text-green-600 bg-white border-gray-300 rounded focus:ring-green-600">
                                    <div class="w-10 h-14 bg-gray-200 rounded border border-gray-200 overflow-hidden shadow-sm flex items-center justify-center relative">
                                        <template x-if="page.thumb">
                                            <img :src="page.thumb" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!page.thumb">
                                            <span class="text-[10px] font-bold text-gray-400" x-text="page.number"></span>
                                        </template>
                                        
                                        <!-- Crossed out overlay -->
                                        <div x-show="!includedPages.includes(page.id.toString())" class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-500 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold" :class="includedPages.includes(page.id.toString()) ? 'text-gray-900' : 'text-gray-400 line-through'" x-text="page.number === 1 ? 'Sampul Depan (Hal 1)' : 'Halaman ' + page.number"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <template x-for="pageId in excludedPageIds">
                        <input type="hidden" name="excluded_pages[]" :value="pageId">
                    </template>
                </div>

                <div class="sticky bottom-4 z-40">
                    <button type="submit" class="w-full py-4 px-6 bg-[#B3131B] hover:bg-[#981017] text-white font-bold text-lg rounded-2xl shadow-[0_8px_20px_rgba(179,19,27,0.3)] hover:shadow-[0_12px_25px_rgba(179,19,27,0.4)] hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Simpan & Terbitkan Sekarang
                    </button>
                </div>
            </div>

            <!-- Kolom Kanan: Preview -->
            <div class="xl:col-span-9 flex flex-col h-[calc(100vh-140px)] sticky top-6">
                <div class="bg-gray-900 rounded-t-2xl px-6 py-4 flex items-center justify-between border-b border-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-widest ml-2">Live Preview Area</span>
                    </div>
                    <div class="text-xs text-gray-500">
                        Total: <span class="font-bold text-white" x-text="includedPages.length"></span> Halaman
                    </div>
                </div>
                
                <div class="flex-1 bg-[#1a1a1f] rounded-b-2xl overflow-hidden relative border border-gray-800 flex items-center justify-center p-4 lg:p-10 shadow-inner">
                    <!-- Pattern Background -->
                    <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
                    
                    <div id="preview-container" class="relative transition-all duration-300 mx-auto z-10" :style="`max-width: ${width * 2}px; width: 100%; aspect-ratio: ${(width * 2) / height};`">
                        <!-- Garis putus-putus panduan potong tengah (Center Alignment Guide) -->
                        <div class="absolute inset-y-0 left-1/2 w-0 border-l-2 border-dashed border-gray-400/80 transform -translate-x-1/2 z-20 pointer-events-none mix-blend-difference" x-show="isSpread"></div>
                        
                        <div id="flipbook" class="w-full h-full bg-transparent shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                            <!-- Pages injected by JS -->
                        </div>
                    </div>
                    
                    <!-- Instructions Overlay -->
                    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 bg-black/70 backdrop-blur-sm text-white px-5 py-2 rounded-full text-xs font-medium flex items-center gap-2 pointer-events-none border border-white/10">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                        Geser halaman atau klik sudut buku untuk mencoba
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>

<script>
    function reviewData() {
        return {
            width: {{ $edition->flipbook_width ?? 540 }},
            height: {{ $edition->flipbook_height ?? 730 }},
            isSpread: {{ ($edition->is_spread ?? true) ? 'true' : 'false' }},
            pages: [
                @foreach($edition->pages as $p)
                { id: {{ $p->id }}, number: {{ $p->page_number }}, hires: '{{ $p->hires_path ? Storage::url($p->hires_path) : '' }}', thumb: '{{ $p->thumb_path ? Storage::url($p->thumb_path) : '' }}' },
                @endforeach
            ],
            includedPages: [
                @foreach($edition->pages as $p)
                @if(!in_array($p->id, $edition->excluded_pages ?? []))
                '{{ $p->id }}',
                @endif
                @endforeach
            ],
            flipBook: null,
            
            get excludedPageIds() {
                return this.pages.map(p => p.id.toString()).filter(id => !this.includedPages.includes(id));
            },
            
            init() {
                this.initFlipbook();
            },
            
            updatePreview() {
                if (this.flipBook) {
                    this.flipBook.destroy();
                    document.getElementById('flipbook').innerHTML = '';
                }
                setTimeout(() => {
                    this.initFlipbook();
                }, 100);
            },
            
            initFlipbook() {
                const el = document.getElementById('flipbook');
                const activePages = this.pages.filter(p => this.includedPages.includes(p.id.toString()));
                
                if (activePages.length === 0) {
                    el.innerHTML = '<div class="flex flex-col items-center justify-center h-full w-full bg-white text-gray-400 rounded-lg"><svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg><p>Pilih setidaknya 1 halaman untuk preview.</p></div>';
                    return;
                }

                let html = '';
                activePages.forEach((page, index) => {
                    const bgStyle = page.hires ? `background-image: url('${page.hires}');` : '';
                    const defaultHtml = `<div class="p-8 flex items-center justify-center h-full w-full"><h1 class="text-4xl opacity-20 font-bold">${page.number}</h1></div>`;

                    if (index === 0) {
                        html += `
                        <div class="page text-neutral-800 bg-white shadow-[-1px_0_3px_rgba(0,0,0,0.1)]">
                            <div class="h-full w-full relative">
                                ${page.hires ? `<div class="w-full h-full bg-no-repeat bg-cover bg-center" style="${bgStyle}"></div>` : defaultHtml}
                            </div>
                        </div>`;
                    } else {
                        if (this.isSpread) {
                            html += `
                            <div class="page text-neutral-800 bg-white shadow-[1px_0_3px_rgba(0,0,0,0.1)]">
                                <div class="h-full w-full relative overflow-hidden">
                                    ${page.hires ? `<div class="w-full h-full bg-no-repeat" style="${bgStyle} background-size: 200% 100%; background-position: left center;"></div>` : defaultHtml.replace(page.number, page.number+'L')}
                                </div>
                            </div>
                            <div class="page text-neutral-800 bg-white shadow-[-1px_0_3px_rgba(0,0,0,0.1)]">
                                <div class="h-full w-full relative overflow-hidden">
                                    ${page.hires ? `<div class="w-full h-full bg-no-repeat" style="${bgStyle} background-size: 200% 100%; background-position: right center;"></div>` : defaultHtml.replace(page.number, page.number+'R')}
                                </div>
                            </div>`;
                        } else {
                            html += `
                            <div class="page text-neutral-800 bg-white">
                                <div class="h-full w-full relative">
                                    ${page.hires ? `<div class="w-full h-full bg-no-repeat bg-contain bg-center" style="${bgStyle}"></div>` : defaultHtml}
                                </div>
                            </div>`;
                        }
                    }
                });
                
                el.innerHTML = html;

                this.flipBook = new St.PageFlip(el, {
                    width: parseInt(this.width),
                    height: parseInt(this.height),
                    size: "stretch",
                    minWidth: 200,
                    maxWidth: parseInt(this.width),
                    minHeight: 200,
                    maxHeight: parseInt(this.height),
                    maxShadowOpacity: 0.5,
                    showCover: true,
                    mobileScrollSupport: false,
                    usePortrait: true
                });
                
                this.flipBook.loadFromHTML(el.querySelectorAll('.page'));
                
                // Set initial transform
                this.updateAlignment();
                
                this.flipBook.on('flip', () => {
                    setTimeout(() => this.updateAlignment(), 10);
                });
                
                this.flipBook.on('changeState', () => {
                    setTimeout(() => this.updateAlignment(), 10);
                });
            },
            
            updateAlignment() {
                if (!this.flipBook) return;
                const container = document.getElementById('preview-container');
                const pageCount = this.flipBook.getPageCount();
                const current = this.flipBook.getCurrentPageIndex();
                const state = this.flipBook.getState();
                
                if (state === 'read') {
                    container.style.transform = 'translateX(0)';
                } else if (current === 0) {
                    // Cover page is on the right half, shift container left by 25% to center it
                    container.style.transform = 'translateX(-25%)';
                } else if (current === pageCount - 1 && pageCount % 2 === 0) {
                    // Back cover is on the left half, shift container right by 25% to center it
                    container.style.transform = 'translateX(25%)';
                } else {
                    container.style.transform = 'translateX(0)';
                }
            }
        }
    }
</script>
@endsection
