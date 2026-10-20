# Product Requirement Document (PRD)
## E-Paper DPRD Kota Tangerang

---

## 1. Tujuan, Pengguna, dan Ruang Lingkup Versi Pertama

### 1.1 Tujuan Proyek
Membangun platform e-paper digital resmi berbasis web untuk DPRD Kota Tangerang. Aplikasi ini memungkinkan masyarakat membaca majalah, warta, dan dokumen publikasi DPRD secara digital dalam bentuk **flipbook interaktif** (pengalaman membaca seperti buku fisik) di perangkat desktop maupun smartphone. Pengelola instansi (Admin/Humas) dapat mengunggah file PDF majalah dan sistem akan mengolahnya secara otomatis menjadi e-book yang siap dipublikasikan.

### 1.2 Pengguna Target
1. **Masyarakat Umum / Pembaca Publik**: Membaca majalah digital, mencari informasi/edisi tertentu, mencari kata kunci dalam edisi, mengunduh file PDF (jika diizinkan), dan membagikan edisi ke media sosial.
2. **Tim Humas / Admin DPRD Kota Tangerang**: Mengunggah PDF majalah, mengelola metadata edisi, memantau statistik pembaca, dan mengelola keamanan sistem.

### 1.3 Ruang Lingkup Versi Pertama (In-Scope)
- Publikasi majalah digital format flipbook responsif (StPageFlip).
- Pemrosesan PDF otomatis via background queue (render WebP 3 ukuran + ekstraksi teks + OCR fallback).
- Pencarian teks internal (in-book search) dan pencarian global antar-edisi (MySQL FULLTEXT).
- Katalog edisi, filter kategori, pencarian edisi, edisi unggulan, dan arsip tahunan.
- Panel admin terproteksi tinggi (URL rahasia, Wajib 2FA TOTP, tanpa register publik, audit log).
- Statistik pembacaan edisi, unduhan, halaman populer, dan kata kunci pencarian.
- Optimasi SEO, Open Graph (WhatsApp sharing), dan performa cepat.

### 1.4 Di Luar Ruang Lingkup Versi Pertama (Out-of-Scope)
- ❌ Fitur komentar publik atau diskusi pada edisi majalah.
- ❌ Login atau registrasi akun untuk pembaca publik (akses publik 100% bebas tanpa login).
- ❌ Fitur transaksi, berlangganan, atau pembayaran.
- ❌ CMS artikel berita umum (aplikasi ini fokus khusus pada e-paper/majalah PDF).

---

## 2. Daftar Halaman dan Alamat URL

| Halaman | Alamat URL | Akses | Keterangan |
| :--- | :--- | :--- | :--- |
| **Beranda** | `/` | Publik | Hero edisi terbaru, rak edisi (grid), edisi populer, pencarian, footer. |
| **Katalog Majalah** | `/majalah` | Publik | Daftar seluruh edisi dengan filter kategori, tahun, dan pengurutan. |
| **Arsip Edisi** | `/arsip` | Publik | Pengelompokan edisi majalah berdasarkan tahun dan periode terbit. |
| **Flipbook Viewer** | `/baca/{slug}?hal=1` | Publik | Antarmuka membaca flipbook interaktif dengan parameter nomor halaman. |
| **Login Admin Rahasia** | `/{ADMIN_PATH}` | Admin | Halaman login admin dengan URL prefix acak dari `.env` (contoh: `/panel-k7x9q2m4`). |
| **Dashboard Admin** | `/{ADMIN_PATH}/dashboard` | Admin | Ringkasan statistik, edisi terbaru, status pemrosesan PDF, dan log aktivitas. |
| **Kelola Edisi** | `/{ADMIN_PATH}/edisi` | Admin | Manajemen edisi majalah (Upload PDF drag & drop, Edit, Publish, Draft, Hapus). |
| **Kelola Kategori** | `/{ADMIN_PATH}/kategori` | Admin | Tambah, edit, dan hapus kategori majalah. |
| **Audit Log** | `/{ADMIN_PATH}/audit-log` | Admin | Catatan riwayat seluruh aktivitas dan perubahan data yang dilakukan oleh admin. |
| **Statistik Detail** | `/{ADMIN_PATH}/statistik` | Admin | Grafik & laporan detail pembaca, halaman terpopuler, unduhan, dan keyword search. |
| **Pengaturan Sistem** | `/{ADMIN_PATH}/pengaturan` | Admin | Pengaturan teks beranda, banner hero, edisi unggulan, dan konfigurasi umum. |

> **Catatan Keamanan Route**: Alamat baku seperti `/admin`, `/login`, `/wp-admin`, `/administrator` akan mengembalikan respons `404 Not Found` biasa.

---

## 3. Fitur Publik

### 3.1 Beranda (`/`)
- **Hero Section**: Menampilkan edisi majalah terbaru secara eksklusif dengan gambar cover resolusi tinggi, nomor edisi, tanggal terbit, deskripsi singkat, tombol *"Baca Edisi Ini"* (langsung membuka viewer), dan tombol *"Unduh PDF"* (bila diizinkan).
- **Bar Pencarian Global**: Fitur pencarian cepat di bagian atas beranda untuk mencari edisi majalah atau konten teks di dalam dokumen.
- **Rak Edisi (Grid Covers)**: Tampilan visual rak buku digital berupa grid cover majalah modern yang dilengkapi:
  - Filter Kategori (misal: *Parlementaria*, *Buletin*, *Laporan Khusus*).
  - Filter Tahun Terbit.
  - Sorting (Terbaru, Terlama, Paling Popular).
- **Section Edisi Unggulan & Populer**: Highlight majalah yang ditandai sebagai edisi pilihan utama atau yang paling banyak dibaca.
- **Footer Resmi**: Informasi kontak DPRD Kota Tangerang, tautan navigasi, hak cipta, dan identitas instansi.

### 3.2 Flipbook Viewer (`/baca/{slug}`)
- **Pengalaman Membaca Buku (StPageFlip Engine)**:
  - **Desktop / Tablet**: Tampilan 2 halaman berdampingan (*two-page spread*) yang membuka seperti majalah fisik, dimulai dengan cover halaman tunggal.
  - **Mobile / Smartphone**: Tampilan 1 halaman (*single page*) otomatis dengan gesture *swipe* yang responsif.
- **Navigasi & Kontrol Baca**:
  - Klik tepi halaman, drag sudut halaman, atau tombol navigasi *Previous/Next*.
  - Dukungan navigasi keyboard: Panah Kiri/Kanan, *Home* (halaman 1), *End* (halaman akhir).
  - Input nomor halaman langsung untuk lompat cepat (*Jump to Page*).
  - Fitur **Zoom hingga 300%** dengan dukungan *pan & drag* halus saat di-zoom.
  - Mode Layar Penuh (*Fullscreen Mode*).
- **Panel Pembantu**:
  - **Drawer Thumbnail**: Baris pratinjau thumbnail seluruh halaman di bagian bawah/samping untuk navigasi visual cepat.
  - **Daftar Isi (Table of Contents / TOC)**: Menu navigasi daftar isi interaktif yang langsung melompat ke halaman tujuan saat diklik.
- **Fitur Berbagi & Akses**:
  - **Modal Bagikan (Share)**: Tautan langsung ke WhatsApp, Facebook, X (Twitter), Salin Tautan, dan pembuat QR Code otomatis.
  - **Deep Link Per Halaman**: URL memperbarui query string secara otomatis (`/baca/{slug}?hal=12`) saat halaman dibalik. Jika URL ini dibuka/dibagikan, pembaca langsung diarahkan ke halaman 12.
  - **Ingat Halaman Terakhir (Remember Last Page)**: Menyimpan progres membaca di `localStorage` browser pembaca. Jika majalah dibuka kembali, muncul penawaran untuk melanjutkan dari halaman terakhir.
  - **Unduh PDF**: Tombol unduh file PDF asli yang dapat diaktifkan atau dinonaktifkan per edisi oleh admin.

### 3.3 Engine Pencarian Teks
- **In-Book Search (Pencarian Dalam Edisi)**: Panel pencarian pada viewer untuk mencari kata/frasa dalam edisi yang sedang dibuka. Menampilkan daftar nomor halaman tempat kata tersebut ditemukan lengkap dengan *highlight* posisi teks.
- **Global Search (Pencarian Lintas Edisi)**: Fitur pencarian dari beranda/katalog yang mencari ke seluruh database teks majalah (MySQL FULLTEXT Index), menampilkan hasil berupa daftar edisi, nomor halaman, dan cuplikan kalimat (*snippet*).

### 3.4 SEO & Open Graph (WhatsApp Optimization)
- **Tag Meta SEO**: Meta title, meta description, canonical URL, dan Schema.org Structured Data (`PublicationIssue` / `Book`).
- **Open Graph / Twitter Cards**: Saat URL majalah atau URL halaman spesifik dibagikan di WhatsApp/Media Sosial, tampilan thumbnail cover majalah akan muncul secara rapi (dimensi teroptimasi 1200x630px) beserta judul edisi dan deskripsi resmi.

---

## 4. Fitur Admin dan Keamanannya

### 4.1 Aksesibilitas & Pengerasan URL Admin
- **Wajib Login**: Seluruh halaman panel admin terproteksi penuh middleware autentikasi. Tidak ada registrasi publik (*No Public Registration*).
- **Pembuatan Akun Admin**: Akun awal hanya bisa dibuat oleh pengelola server melalui perintah CLI Laravel Artisan (`php artisan admin:create`).
- **URL Admin Rahasia (`ADMIN_PATH`)**:
  - Alamat panel admin disesuaikan via variabel environment `.env` (contoh: `ADMIN_PATH=panel-k7x9q2m4`).
  - Alamat login standar seperti `/admin`, `/login`, `/wp-admin`, `/administrator` mengembalikan respons `404 Not Found`.
  - Tidak ada link atau petunjuk menuju halaman admin di bagian publik web.
  - Seluruh respons halaman admin dilengkapi header HTTP `X-Robots-Tag: noindex, nofollow` dan tag meta `<meta name="robots" content="noindex, nofollow">`.

### 4.2 Evaluasi Paket Autentikasi: Laravel Fortify vs Breeze
- **Rekomendasi Utama: Laravel Fortify**
- **Alasan Pemilihan**:
  1. Fortify adalah *headless authentication backend* resmi dari Laravel yang menangani seluruh logika autentikasi (Login, 2FA TOTP, Session Throttling, Password Confirmation, Password Resets) tanpa memaksakan struktur route bawaan atau template UI.
  2. Memudahkan pemetaan kustom URL rahasia (`ADMIN_PATH`) dan penyesuaian tampilan login Blade Tailwind sesuai pedoman desain Google Stitch.
  3. Menyediakan dukungan *Two-Factor Authentication (2FA)* berbasis TOTP bawaan yang sangat kuat dan aman out-of-the-box.

### 4.3 Standar Autentikasi & Keamanan Sesi
- **Aturan Kata Sandi**: Minimal 12 karakter, wajib mengandung kombinasi huruf besar, huruf kecil, angka, dan karakter khusus.
- **Wajib 2FA (TOTP)**: Seluruh akun admin WAJIB mengaktifkan Two-Factor Authentication (menggunakan Google Authenticator, Authy, atau aplikasi TOTP sejenis). Dilengkapi 8 kode pemulihan (*recovery codes*) yang wajib disimpan saat aktivasi.
- **Manajemen Sesi & Cookie**:
  - Sesi berakhir otomatis jika tidak ada aktivitas selama 30 menit (*session idle timeout*).
  - Atribut Cookie: `Secure` (hanya HTTPS), `HttpOnly` (tidak bisa diakses JavaScript), `SameSite=Strict` (proteksi CSRF).
- **Rate Limiting Login**: Maksimal 5 kali percobaan login gagal per 15 menit per kombinasi IP address dan nama pengguna.
- **Cloudflare Turnstile**: Integrasi CAPTCHA hemat privasi pada halaman login admin.
- **Pembatasan IP (Optional IP Whitelisting)**: Opsi pembatasan akses admin berdasarkan daftar IP resmi melalui variabel `.env` (`ADMIN_ALLOWED_IPS="180.252.x.x,36.72.x.x"`; jika dikosongkan, tidak ada pembatasan IP).

### 4.4 Manajemen Peran (Roles) & Audit Log
- **Peran Akses**:
  1. **Super Admin**: Akses penuh (kelola akun admin, konfigurasi sistem, kelola kategori, kelola edisi, hapus permanen, lihat audit log).
  2. **Editor**: Akses operasional (mengunggah PDF, mengedit metadata edisi, mengubah status publish/draft, melihat statistik).
- **Audit Log Terintegrasi**: Mencatat seluruh tindakan sensitif ke dalam database `audit_logs` (Informasi: Pelaku, Aksi/Tindakan, Target Data, Timestamp, IP Address, dan User Agent).

### 4.5 Fungsi Pengelolaan Admin
- **Unggah PDF Drag & Drop**: Area pengunggahan file PDF dengan *progress bar* realtime.
- **Form Metadata Edisi**: Judul majalah, nomor edisi, tahun, tanggal terbit, kategori, deskripsi/abstrak.
- **Indikator Pemrosesan PDF**: Menampilkan status pengolahan PDF di background queue (*Uploading*, *Rendering Pages*, *Extracting Text*, *Running OCR*, *Completed*, *Failed*).
- **Pratinjau & Terbitkan**: Admin dapat memeriksa hasil render sebelum mengubah status edisi menjadi *Published*.
- **Manajemen Edisi**: Edit info edisi, ganti gambar cover, ganti file PDF (proses ulang), atur urutan tampilan, tandai sebagai edisi unggulan (*featured*), aktif/nonaktifkan unduhan PDF publik, ubah status ke *Draft*, *Published*, atau *Archived*.
- **Sistem Trash / Soft Delete**: Penghapusan edisi masuk ke folder arsip sampah (*Soft Delete*) terlebih dahulu dan dapat dipulihkan (*Restore*) sebelum dihapus permanen oleh Super Admin.
- **Dashboard Statistik**: Memantau total pembaca per edisi, total unduhan PDF, daftar halaman paling sering dilihat, serta kata kunci pencarian populer.

---

## 5. Pemrosesan PDF Otomatis via Antrean (Queue)

### 5.1 Alur Pemrosesan Berurutan
Saat PDF diunggah oleh admin, sistem menjalankan tugas latar belakang (*background job*) menggunakan antrean Laravel Queue:

```
[Upload PDF] 
     │
     ▼
[1. Validasi File & MIME Type]
     │
     ▼
[2. Render Gambar WebP per Halaman (pdftoppm)]
     ├──> Thumbnail (300px width)
     ├──> Normal    (1200px width)
     └──> Hi-Res    (2400px width)
     │
     ▼
[3. Ekstraksi Teks Asli (pdftotext)]
     │
     ├── Teks Ditemukan? ──> [Simpan ke Database FULLTEXT]
     │
     └── Teks Kosong/Scan? ──> [4. Fallback OCR Tesseract (Bahasa Indonesia)]
                                      │
                                      ▼
[5. Simpan Indeks Teks & Metadata Edisi]
     │
     ▼
[Status: Ready / Published]
```

1. **Validasi File**: Memastikan file benar-benar berformat PDF via Magic Bytes Checking (`application/pdf`), batas ukuran maks 100 MB.
2. **Render Gambar WebP (Poppler `pdftoppm`)**: Setiap halaman PDF dikonversi menjadi gambar berformat WebP dalam 3 preset ukuran:
   - *Thumbnail* (lebar ~300px) untuk rak majalah dan baris pratinjau.
   - *Normal* (lebar ~1200px) untuk tampilan flipbook standar.
   - *High-Resolution* (lebar ~2400px) untuk mendukung fitur zoom hingga 300%.
3. **Ekstraksi Teks Asli (Poppler `pdftotext`)**: Membaca karakter teks asli beserta posisi koordinatnya pada setiap halaman PDF.
4. **Fallback OCR Bahasa Indonesia (Tesseract OCR)**: Jika halaman PDF merupakan hasil *scan* gambar (tidak memiliki layer teks asli), sistem otomatis menjalankan Tesseract OCR dengan pustaka bahasa Indonesia (`tesseract -l ind`).
5. **Pengindeksan Pencarian**: Menyimpan teks halaman ke kolom FULLTEXT MySQL (`pages.content_text`).
6. **Ekstraksi Cover & Metadata**: Halaman pertama otomatis dijadikan cover standar (dapat diganti manual oleh admin) dan membaca jumlah total halaman PDF.

### 5.2 Penanganan Kegagalan & Retry (Resilience)
- Job pemrosesan dibagi menjadi sub-job modular (`ProcessPdfJob` -> `RenderPageJob` -> `ExtractTextJob`).
- Pengaturan percobaan ulang (`tries = 3`) dengan jeda bertahap (*exponential backoff*).
- Jika job gagal setelah 3x percobaan, status edisi diubah menjadi `Failed` dan detail log kesalahan dicatat di database. Admin menerima notifikasi pada dashboard serta dapat menekan tombol **"Retry Processing"** tanpa perlu mengunggah ulang file.

---

## 6. Skema Database MySQL

### 6.1 Tabel `users`
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key, Auto Increment | ID Pengguna |
| `name` | VARCHAR(255) | Not Null | Nama lengkap admin |
| `email` | VARCHAR(255) | Unique, Not Null | Alamat email (Username login) |
| `password` | VARCHAR(255) | Not Null | Hash password (Bcrypt/Argon2) |
| `role` | ENUM | 'super_admin', 'editor' | Peran hak akses |
| `two_factor_secret` | TEXT | Nullable | Encrypted secret key TOTP |
| `two_factor_recovery_codes` | TEXT | Nullable | Encrypted 8 kode pemulihan |
| `two_factor_confirmed_at` | TIMESTAMP | Nullable | Tanggal aktivasi 2FA |
| `remember_token` | VARCHAR(100) | Nullable | Token sesi |
| `last_login_at` | TIMESTAMP | Nullable | Waktu login terakhir |
| `last_login_ip` | VARCHAR(45) | Nullable | IP Address login terakhir |
| `timestamps` | TIMESTAMP | `created_at`, `updated_at` | Waktu buat & ubah |

### 6.2 Tabel `categories`
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key | ID Kategori |
| `name` | VARCHAR(100) | Not Null | Nama kategori (misal: Parlementaria) |
| `slug` | VARCHAR(120) | Unique, Not Null | URL slug kategori |
| `description` | TEXT | Nullable | Deskripsi singkat kategori |
| `sort_order` | INT | Default 0, Index | Urutan posisi tampilan |
| `timestamps` | TIMESTAMP | `created_at`, `updated_at` | Waktu buat & ubah |

### 6.3 Tabel `editions`
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key | ID Edisi |
| `category_id` | BIGINT | Unsigned, Foreign Key -> `categories.id` | Id Kategori |
| `title` | VARCHAR(255) | Not Null, Index | Judul edisi majalah |
| `slug` | VARCHAR(255) | Unique, Not Null | URL slug edisi |
| `edition_number` | VARCHAR(50) | Nullable | Nomor/Edisi terbit (misal: Ed. 81) |
| `publish_date` | DATE | Not Null, Index | Tanggal resmi terbit |
| `year` | YEAR | Not Null, Index | Tahun terbit majalah |
| `description` | TEXT | Nullable | Ringkasan isi majalah |
| `pdf_filename` | VARCHAR(255) | Not Null | Nama file PDF asli di server |
| `pdf_filesize` | BIGINT | Unsigned, Not Null | Ukuran file PDF (bytes) |
| `cover_image` | VARCHAR(255) | Nullable | Path gambar cover |
| `total_pages` | INT | Default 0 | Total halaman PDF |
| `status` | ENUM | 'draft','processing','failed','published','archived' | Status terbit |
| `allow_download` | BOOLEAN | Default true | Opsi izin unduh PDF |
| `is_featured` | BOOLEAN | Default false, Index | Tandai sebagai edisi unggulan |
| `views_count` | BIGINT | Default 0, Index | Total pembaca |
| `downloads_count` | BIGINT | Default 0 | Total unduhan PDF |
| `sort_order` | INT | Default 0, Index | Urutan posisi |
| `timestamps` | TIMESTAMP | `created_at`, `updated_at` | Waktu buat & ubah |
| `deleted_at` | TIMESTAMP | Nullable, Index | Timestamp Soft Delete |

### 6.4 Tabel `pages`
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key | ID Halaman |
| `edition_id` | BIGINT | Unsigned, Foreign Key -> `editions.id` (Cascade Delete) | ID Edisi |
| `page_number` | INT | Not Null, Index `(edition_id, page_number)` | Nomor halaman |
| `image_thumb` | VARCHAR(255) | Not Null | Path gambar WebP thumbnail |
| `image_normal` | VARCHAR(255) | Not Null | Path gambar WebP normal |
| `image_hires` | VARCHAR(255) | Not Null | Path gambar WebP resolusi tinggi |
| `content_text` | LONGTEXT | Nullable, **FULLTEXT Index** | Teks hasil ekstraksi/OCR |
| `has_ocr` | BOOLEAN | Default false | Flag apakah memakai Tesseract |
| `timestamps` | TIMESTAMP | `created_at`, `updated_at` | Waktu buat & ubah |

### 6.5 Tabel `toc` (Table of Contents)
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key | ID Daftar Isi |
| `edition_id` | BIGINT | Unsigned, Foreign Key -> `editions.id` | ID Edisi |
| `title` | VARCHAR(255) | Not Null | Judul bab/artikel |
| `page_number` | INT | Not Null | Nomor halaman tujuan |
| `parent_id` | BIGINT | Nullable, Foreign Key -> `toc.id` | Induk bab (untuk sub-menu) |
| `sort_order` | INT | Default 0 | Urutan bab |
| `timestamps` | TIMESTAMP | `created_at`, `updated_at` | Waktu buat & ubah |

### 6.6 Tabel `settings`
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key | ID Pengaturan |
| `key` | VARCHAR(100) | Unique, Not Null | Kunci pengaturan (misal: `hero_text`) |
| `value` | LONGTEXT | Nullable | Nilai pengaturan (String/JSON) |
| `timestamps` | TIMESTAMP | `created_at`, `updated_at` | Waktu buat & ubah |

### 6.7 Tabel `analytics`
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key | ID Analitik |
| `edition_id` | BIGINT | Nullable, Foreign Key -> `editions.id` | ID Edisi (jika ada) |
| `page_number` | INT | Nullable | Nomor halaman yang dibuka |
| `event_type` | ENUM | 'view_edition', 'view_page', 'download_pdf', 'search' | Jenis interaksi |
| `search_keyword` | VARCHAR(255) | Nullable, Index | Kata kunci pencarian |
| `ip_address` | VARCHAR(45) | Nullable | IP pembaca |
| `user_agent` | TEXT | Nullable | Perangkat pembaca |
| `created_at` | TIMESTAMP | Index | Waktu kejadian |

### 6.8 Tabel `audit_logs`
| Kolom | Tipe Data | Atribut / Indeks | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Unsigned, Primary Key | ID Audit Log |
| `user_id` | BIGINT | Unsigned, Foreign Key -> `users.id` | ID Admin eksekutor |
| `action` | VARCHAR(100) | Not Null, Index | Tindakan (misal: `create_edition`) |
| `target_type` | VARCHAR(100) | Nullable | Tipe data target (misal: `Edition`) |
| `target_id` | BIGINT | Nullable | ID data target |
| `payload` | JSON | Nullable | Detail perubahan data |
| `ip_address` | VARCHAR(45) | Not Null | IP Address admin |
| `user_agent` | TEXT | Nullable | Browser/perangkat |
| `created_at` | TIMESTAMP | Index | Tanggal & waktu |

### 6.9 Tabel `jobs` & `failed_jobs`
Tabel standar antrean Laravel untuk menangani pemrosesan PDF di background queue.

---

## 7. Struktur Folder Proyek dan Komponen Blade

### 7.1 Pohon Direktori Utama
```
paper/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── CreateAdminUser.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── EditionController.php
│   │   │   │   ├── FlipbookController.php
│   │   │   │   └── SearchController.php
│   │   │   └── Admin/
│   │   │       ├── Auth/
│   │   │       │   ├── TwoFactorController.php
│   │   │       │   └── AdminLoginController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── EditionAdminController.php
│   │   │       ├── CategoryAdminController.php
│   │   │       ├── AuditLogController.php
│   │   │       └── AnalyticsController.php
│   │   └── Middleware/
│   │       ├── EnsureAdminPath.php
│   │       ├── AdminIpWhitelist.php
│   │       └── EnsureTwoFactorConfirmed.php
│   ├── Jobs/
│   │   ├── ProcessPdfJob.php
│   │   ├── RenderPageJob.php
│   │   └── ExtractTextJob.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Edition.php
│   │   ├── Page.php
│   │   ├── Toc.php
│   │   ├── Setting.php
│   │   ├── Analytic.php
│   │   └── AuditLog.php
│   └── Services/
│       └── PdfProcessorService.php
├── config/
│   ├── admin.php
│   └── fortify.php
├── docs/
│   └── PRD.md
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── build/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── flipbook.js
│   └── views/
│       ├── components/
│       │   ├── navbar.blade.php
│       │   ├── footer.blade.php
│       │   ├── edition-card.blade.php
│       │   ├── search-modal.blade.php
│       │   ├── share-modal.blade.php
│       │   └── flipbook-viewer.blade.php
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── admin.blade.php
│       ├── public/
│       │   ├── home.blade.php
│       │   ├── catalog.blade.php
│       │   ├── archive.blade.php
│       │   └── reader.blade.php
│       └── admin/
│           ├── auth/
│           │   ├── login.blade.php
│           │   └── two-factor.blade.php
│           ├── dashboard.blade.php
│           ├── editions/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           ├── audit-log/
│           │   └── index.blade.php
│           └── statistics/
│               └── index.blade.php
├── storage/
│   └── app/
│       ├── private/
│       │   └── raw_pdfs/
│       └── public/
│           └── pages/
└── routes/
    ├── web.php
    └── admin.php
```

### 7.2 Component Blade Utama
1. `<x-navbar>`: Navigasi publik dengan pencarian cepat, filter kategori, dan identitas DPRD Kota Tangerang.
2. `<x-footer>`: Footer publik dengan informasi instansi dan hak cipta.
3. `<x-edition-card>`: Kartu tampilan cover majalah pada grid/rak edisi (lengkap dengan badge edisi, tanggal, & tombol aksi).
4. `<x-flipbook-viewer>`: Komponen pembaca majalah interaktif berbasis StPageFlip.
5. `<x-search-modal>`: Modal dialog pencarian teks interaktif.
6. `<x-share-modal>`: Modal bagikan edisi ke WhatsApp, sosial media, & QR code.
7. `<x-admin.sidebar>`: Navigasi bilah samping panel admin.
8. `<x-admin.header>`: Header panel admin dengan profil pengguna & tombol logout.
9. `<x-admin.pdf-uploader>`: Area drag-and-drop unggah file PDF dengan *progress indicator*.
10. `<x-admin.stat-card>`: Kartu ringkasan angka statistik pada dashboard admin.

---

## 8. Kebutuhan Server (Spesifikasi VPS)

### 8.1 Spesifikasi Server & Perangkat Lunak
- **Sistem Operasi**: Ubuntu 24.04 LTS (Noble Numbat).
- **Web Server**: Nginx 1.26+ (konfigurasi HTTP/2 Enabled, Gzip/Brotli compression).
- **PHP Runtime**: PHP 8.3 / 8.4 FPM.
  - *Ekstensi Wajib*: `pdo_mysql`, `mbstring`, `exif`, `bcmath`, `xml`, `curl`, `zip`, `gd`, `imagick`, `intl`.
- **Database Server**: MySQL 8.0+.
- **Dependency PDF & OCR (System Packages)**:
  - `poppler-utils` (`pdftoppm`, `pdftotext`, `pdfinfo`).
  - `tesseract-ocr` dan paket data bahasa Indonesia (`tesseract-ocr-ind`).
  - `ghostscript` (bila diperlukan optimasi PDF).
- **Process Manager**: Supervisor (menjalankan worker antrean `php artisan queue:work` secara terus-menerus).
- **SSL Certificate**: Certbot (Let's Encrypt Free Automated SSL).

### 8.2 Pengerasan Keamanan VPS (Server Hardening)
- **Firewall (UFW)**: Hanya membuka port `80` (HTTP), `443` (HTTPS), dan port kustom SSH.
- **Akses SSH**: Hanya diizinkan melalui SSH Public Key Authentication (Password Login Disabled, Root Login Disabled).
- **Fail2ban**: Proteksi otomatis terhadap percobaaan *brute force* SSH dan login admin.
- **Security Headers (Nginx)**:
  - `Content-Security-Policy` (CSP)
  - `Strict-Transport-Security` (HSTS)
  - `X-Frame-Options: SAMEORIGIN`
  - `X-Content-Type-Options: nosniff`
  - `Referrer-Policy: strict-origin-when-cross-origin`
- **Isolasi File Upload**:
  - Konfigurasi batas upload Nginx: `client_max_body_size 120M;`.
  - File PDF asli disimpan di luar direktori web publik (`storage/app/private/raw_pdfs/`), hanya gambar WebP hasil render yang dapat diakses publik via symlink.
- **Sistem Backup Otomatis**: Script Cron harian untuk melakukan backup database MySQL dan file media ke penyimpanan terpisah (*Offsite Backup* / Cloud Storage).
- **Integrasi Cloudflare**: Rekomendasi penggunaan Cloudflare (Free/Pro) untuk DNS Management, WAF, Turnstile, DDoS Protection, dan Caching Static Content.

### 8.3 Perkiraan Spesifikasi Minimum VPS
- **CPU**: 2 vCPU Cores
- **RAM**: 4 GB (Disarankan 8 GB jika sering memproses PDF > 100 halaman secara bersamaan)
- **Penyimpanan**: 50 GB NVMe SSD
- **Bandwidth**: Unlimited / 1 TB per bulan

---

## 9. Pedoman Tampilan & Desain (Mengacu Google Stitch)

### 9.1 Palet Warna Resmi
Sesuai dengan acuan desain dari Google Stitch (`screen1.html`, `screen2.html`, `screen3.html`), aplikasi menggunakan palet warna yang terbatas, resmi, dan elegan:

| Elemen Warna | Kode Warna (HEX) | Penggunaan Utama |
| :--- | :--- | :--- |
| **Primary Red** | `#B3131B` | Warna aksen utama DPRD Kota Tangerang, tombol aksen, badge edisi terbaru. |
| **Deep Charcoal** | `#1B1B1F` | Warna teks utama, header navigasi, footer, dan nuansa gelap yang profesional. |
| **Soft Warm Cream** | `#FAF7F2` | Warna latar belakang utama publik agar nyaman di mata saat membaca majalah. |
| **Neutral Border/Muted**| `#E2DDD5` / `#71717A` | Garis batas komponen halus, teks sekunder, dan background input. |

### 9.2 Prinsip Anti-AI Slop (Kualitas Desain Premium)
- ❌ **Tanpa Gradien Ungu-Biru Neon Generik**: Menggunakan warna solid dan kontras yang tegas sesuai warna resmi instansi.
- ❌ **Tanpa Emoji Sebagai Ikon**: Seluruh ikon menggunakan SVG vektor yang rapi dan konsisten (Heroicons / Lucide icons).
- ❌ **Tanpa Teks Filler / Slogan Klise**: Menggunakan Bahasa Indonesia resmi, baku, dan lugas sesuai standar komunikasi pemerintahan.
- ❌ **Tanpa Kartu Berbayang Seragam (Uniform Shadow Cards)**: Menggunakan border tipis 1px (`#E2DDD5`) dan ruang kosong (*white space*) yang lega untuk memisahkan konten.

### 9.3 Tipografi & Aksesibilitas
- **Font Family**: Google Fonts *Plus Jakarta Sans* atau *Inter* untuk tampilan korporat yang modern, bersih, dan mudah dibaca di berbagai resolusi layar.
- **Standar Aksesibilitas (WCAG 2.1 AA)**:
  - Rasio kontras warna teks terhadap background minimal 4.5:1.
  - Seluruh elemen interaktif dapat diakses menggunakan navigasi keyboard.
  - Memiliki tag ARIA yang jelas untuk pembaca layar (*screen reader*).
  - Responsif sempurna dari ukuran layar HP kecil (360px) hingga layar monitor desktop lebar (2560px).

---

## 10. Risiko dan Cara Mengatasinya

| No | Potensi Risiko | Tingkat | Strategi Mitigasi / Solusi |
| :--- | :--- | :--- | :--- |
| 1 | **Serangan Brute Force pada Login Admin** | Tinggi | Penggunaan URL admin rahasia (`ADMIN_PATH`), Wajib 2FA TOTP, Rate limiting 5x/15 menit, Cloudflare Turnstile, dan Whitelist IP opsional. |
| 2 | **Server Crash / Memory Out (OOM) akibat PDF Besar** | Tinggi | Pemrosesan PDF secara *asynchronous* via Laravel Queue per halaman, alokasi batas memori PHP worker khusus, dan pembatasan render paralel. |
| 3 | **Akurasi OCR Rendah pada PDF Hasil Scan Buruk** | Sedang | Pra-pemrosesan gambar (*contrast enhancement*) sebelum dikirim ke Tesseract OCR, serta fitur edit teks manual di panel admin. |
| 4 | **Kebocoran Dokumen Internal / Draft** | Sedang | Dokumen berstatus `Draft` disimpan di folder terproteksi privat di luar `public/`, serta pemberian header `noindex` pada halaman admin. |
| 5 | **Beban Server Tinggi akibat Traffic Membaca** | Sedang | Gambar halaman dikonversi ke format WebP terkompresi hemat ukuran, penggunaan browser caching, serta rekomendasi CDN Cloudflare. |

---

## 11. Rencana Tahap Pengerjaan (6 Tahap)

### Tahap 1: Setup Proyek & Foundation Desain
- **Hasil**: Inisialisasi Laravel, Tailwind CSS, konfigurasi warna/font sesuai acuan Stitch, komponen layout Blade dasar (`layouts/app.blade.php`).
- **Kriteria Selesai**: Struktur proyek siap, stylesheet Tailwind terkompilasi, dan halaman placeholder dapat dibuka bersih di browser.

### Tahap 2: Halaman Publik Beranda, Katalog & Arsip
- **Hasil**: Halaman Beranda (`/`), Katalog (`/majalah`), Arsip (`/arsip`), filter kategori/tahun, responsive grid rak edisi, dan modal UI dasar.
- **Kriteria Selesai**: Tampilan publik persis seperti acuan `screen1.html`, responsif di HP/Desktop, dan navigasi antar halaman berfungsi lancar.

### Tahap 3: Flipbook Reader Interaktif
- **Hasil**: Integrasi library StPageFlip pada viewer `/baca/{slug}`, tampilan 2 halaman desktop, swipe mobile, zoom 300%, fullscreen, thumbnail drawer, TOC, dan share modal.
- **Kriteria Selesai**: Majalah dapat dibalik dengan animasi halus seperti buku fisik persis acuan `screen2.html`, deep link `?hal=N` berfungsi.

### Tahap 4: Skema Database & Worker Pemrosesan PDF
- **Hasil**: Migration database lengkap, Queue Job konversi PDF ke WebP 3 ukuran, ekstraksi `pdftotext`, fallback OCR Tesseract, dan indikator status.
- **Kriteria Selesai**: Mengunggah PDF via command/test script otomatis memproses halaman menjadi WebP 3 ukuran dan menyimpan teks ke database.

### Tahap 5: Panel Admin dengan 2FA & URL Rahasia
- **Hasil**: URL admin rahasia (`ADMIN_PATH`), autentikasi Fortify, Wajib 2FA TOTP, audit log, CRUD Edisi, dan form upload PDF drag-and-drop.
- **Kriteria Selesai**: Tampilan admin persis acuan `screen3.html`, login hanya via URL rahasia, wajib 2FA Google Authenticator, upload PDF berfungsi dengan *progress bar*.

### Tahap 6: Search Engine, Statistik & Pengerasan Keamanan
- **Hasil**: Fitur pencarian FULLTEXT global & in-book search, dashboard statistik pembaca/keyword, security headers, rate limiting, dan hardening VPS.
- **Kriteria Selesai**: Pencarian kata kunci mengembalikan halaman yang sesuai dengan *highlight*, statistik tercatat, dan seluruh tes keamanan lulus.

---

## 12. Daftar Pertanyaan yang Perlu Dijawab Pengguna

Sebelum melanjutkan ke tahap pembuatan proyek dan penulisan kode, mohon memberikan konfirmasi atas beberapa hal berikut:

1. **Domain Utama**: Apa nama domain / sub-domain resmi yang akan digunakan? (Contoh: `epaper.dprd.tangerangkota.go.id`).
2. **Whitelist IP Admin**: Berapa alamat IP Public kantor/instansi yang akan dimasukkan ke dalam daftar putih (`ADMIN_ALLOWED_IPS`), atau apakah IP admin bersifat dinamis?
3. **Akun Admin Awal**: Siapa saja nama & alamat email awal yang perlu dibuatkan akun Super Admin / Editor?
4. **Daftar Kategori Majalah**: Apa saja nama Kategori resmi majalah/dokumen yang digunakan? (Contoh: *Parlementaria*, *Buletin DPRD*, *Laporan Kinerja*, *Warta Daerah*).
5. **Kebijakan Unduh PDF**: Apakah fitur unduh file PDF asli secara default diizinkan untuk publik, atau nonaktif secara default?
6. **Aset Logo & Warna Resmi**: Apakah ada file logo resmi DPRD Kota Tangerang beresolusi tinggi (SVG/PNG transparan) serta kode warna resmi pemda selain yang terdapat pada acuan desain Stitch?

---
*Dokumen PRD ini dibuat sebagai acuan utama pengembangan sistem E-Paper DPRD Kota Tangerang.*
