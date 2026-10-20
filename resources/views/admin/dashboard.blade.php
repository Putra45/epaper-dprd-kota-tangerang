@extends('layouts.admin')

@section('title', 'Dashboard Admin - E-Paper Parlementaria')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow space-y-8">
    
    <!-- DropzoneHero -->
    <section aria-label="Area Pengunggahan File PDF" class="w-full">
        <form action="{{ route('admin.editions.store') }}" method="POST" enctype="multipart/form-data" id="upload-form" class="w-full">
            @csrf
            <div class="dropzone-border bg-white rounded-xl p-8 sm:p-12 flex flex-col items-center justify-center text-center cursor-pointer transition hover:bg-[#FDF2F2]/30 group shadow-xs border-2 border-dashed border-gray-300 hover:border-[#B3131B]" id="pdf-dropzone" onclick="document.getElementById('pdf-input').click()">
                <div class="w-16 h-16 mb-4 rounded-full bg-[#FDF2F2] flex items-center justify-center text-[#B3131B] group-hover:scale-105 transition-transform duration-200 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 13h6m-6 4h4"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">Mulai Buat Edisi Baru</h2>
                <p class="text-base text-gray-500 max-w-lg mb-6">Tarik file PDF majalah ke sini atau klik untuk memilih file dari komputer. <br>(Maks. 100 MB)</p>
                <input type="file" id="pdf-input" name="pdf_file" accept=".pdf" class="hidden" required>
                <button type="button" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-[#B3131B] hover:bg-[#981017] active:scale-95 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Pilih File PDF
                </button>
            </div>
            
            <div id="loading-indicator" class="hidden mt-6 flex flex-col items-center justify-center py-8 bg-white rounded-xl shadow-xs border border-gray-200">
                <svg class="w-10 h-10 text-brand-crimson animate-spin mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900">Memproses PDF...</h3>
                <p class="text-sm text-gray-500 text-center mt-2 max-w-md">Sistem sedang membelah dan mengekstrak halaman PDF. Proses ini mungkin memakan waktu beberapa detik. Mohon jangan tutup halaman ini.</p>
            </div>
        </form>
    </section>

    <!-- History / Drafts -->
    <section aria-label="Riwayat Edisi" class="w-full bg-white rounded-xl border border-gray-200 p-6 sm:p-7 shadow-2xs">
        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Status & Riwayat Edisi
        </h3>
        
        <div class="space-y-3">
            @if($editions->isEmpty())
                <div class="text-center py-6 text-gray-500 text-sm border border-dashed border-gray-200 rounded-lg bg-gray-50">
                    Belum ada data edisi.
                </div>
            @else
                @foreach($editions as $ed)
                <div class="p-4 bg-white border border-gray-200 hover:border-brand-crimson/30 hover:shadow-md rounded-xl flex flex-col sm:flex-row sm:items-center justify-between transition-all duration-300 gap-5">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <!-- Thumbnail Cover -->
                        <!-- Thumbnail Cover -->
                        <div class="w-14 sm:w-16 aspect-[1/1.34] bg-gray-100 rounded-lg shadow-xs border border-gray-200 overflow-hidden flex-shrink-0 relative group/thumb">
                            @if($ed->cover_url)
                                <img src="{{ $ed->cover_url }}" alt="Cover {{ $ed->title }}" class="w-full h-full object-fill"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display: none;" class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg>
                                </div>
                                {{-- Spine Shadow --}}
                                <div class="absolute inset-y-0 left-0 w-2.5 bg-gradient-to-r from-black/30 to-transparent pointer-events-none"></div>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Info Teks -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-base sm:text-lg font-bold text-gray-900 truncate tracking-tight">{{ $ed->title }}</h4>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-crimson bg-red-50 border border-red-100 px-2 py-0.5 rounded-full">{{ $ed->category }}</span>
                                <p class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">{{ $ed->created_at->translatedFormat('d F Y') }}</p>
                                <span class="text-gray-300 text-xs">&bull;</span>
                                <p class="text-xs text-gray-500 font-medium">{{ $ed->total_pages }} Halaman</p>
                            </div>
                            @if($ed->description)
                                <p class="text-xs text-gray-500 line-clamp-1 mt-1.5 font-normal">{{ $ed->description }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2.5">
                        @if($ed->status === 'processing' || str_starts_with($ed->title, 'Draft '))
                            <a href="{{ route('admin.editions.review', $ed->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold tracking-wide rounded-lg bg-yellow-50 text-yellow-700 border border-yellow-200 hover:bg-yellow-100 transition shadow-sm">
                                Lanjutkan Review &rarr;
                            </a>
                        @elseif($ed->status === 'review')
                            <a href="{{ route('admin.editions.review', $ed->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold tracking-wide rounded-lg bg-brand-crimson text-white hover:bg-[#981017] transition shadow-sm">
                                Edit Layout
                            </a>
                        @elseif($ed->status === 'published')
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold tracking-wide rounded-full bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></div>
                                Aktif Publik
                            </span>
                            <a href="{{ route('admin.editions.review', $ed->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold tracking-wide rounded-lg bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:text-brand-crimson transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Data
                            </a>
                        @endif
                        
                        <div class="h-6 w-px bg-gray-200 mx-1 hidden sm:block"></div>
                        
                        <form action="{{ route('admin.editions.destroy', $ed->id) }}" method="POST" onsubmit="return confirm('Hapus permanen edisi ini?');" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-white hover:bg-red-500 rounded-lg transition-colors shadow-sm border border-transparent hover:border-red-600" title="Hapus Permanen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
                <div class="pt-4">
                    {{ $editions->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </section>
</div>

<script>
    const dropzone = document.getElementById('pdf-dropzone');
    const pdfInput = document.getElementById('pdf-input');
    const uploadForm = document.getElementById('upload-form');
    const loadingIndicator = document.getElementById('loading-indicator');

    if (dropzone && pdfInput) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('bg-[#FDF2F2]', 'border-[#B3131B]');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('bg-[#FDF2F2]', 'border-[#B3131B]');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            let dt = e.dataTransfer;
            let files = dt.files;
            
            if(files.length > 0 && files[0].type === "application/pdf") {
                pdfInput.files = files;
                submitForm();
            } else {
                alert('Harap unggah file PDF.');
            }
        });

        pdfInput.addEventListener('change', (e) => {
            if(e.target.files.length > 0) {
                submitForm();
            }
        });

        function submitForm() {
            dropzone.classList.add('hidden');
            loadingIndicator.classList.remove('hidden');
            
            const formData = new FormData(uploadForm);
            
            fetch(uploadForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                
                if (!response.ok) {
                    if (response.status === 413) {
                        throw new Error("File PDF terlalu besar. Maksimal 100 MB.");
                    }
                    if (response.status === 419) {
                        throw new Error("Sesi berakhir, silakan refresh halaman.");
                    }
                    if (response.status === 504) {
                        // Jika server Nginx timeout tapi proses jalan di belakang
                        window.location.reload();
                        return;
                    }
                    return response.json().then(err => { throw err; });
                }
                
                return response.json();
            })
            .then(data => {
                if (data && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Jika sukses tapi tidak ada json redirect, coba reload (fallback)
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error("Upload error:", error);
                alert(error.message || "Terjadi kesalahan saat mengunggah PDF. Mungkin file terlalu besar atau server sibuk.");
                dropzone.classList.remove('hidden');
                loadingIndicator.classList.add('hidden');
                pdfInput.value = ''; // reset
            });
        }
    }
</script>
@endsection
