<?php

namespace App\Support;

/**
 * Data contoh sementara untuk halaman publik.
 * Diganti dengan query database pada Tahap 4.
 */
class DemoEditions
{
    public static function categories(): array
    {
        return [
            ['name' => 'Majalah', 'slug' => 'majalah'],
            ['name' => 'Buletin', 'slug' => 'buletin'],
        ];
    }

    public static function all(): array
    {
        $months = [
            81 => ['Agustus 2026', 2026, 64, '18.4 MB'],
            80 => ['Juli 2026', 2026, 52, '15.1 MB'],
            79 => ['Juni 2026', 2026, 44, '12.8 MB'],
            78 => ['Mei 2026', 2026, 48, '13.9 MB'],
            77 => ['April 2026', 2026, 40, '11.2 MB'],
            76 => ['Desember 2025', 2025, 56, '16.0 MB'],
            75 => ['Oktober 2025', 2025, 48, '14.3 MB'],
            74 => ['Agustus 2025', 2025, 44, '12.5 MB'],
        ];

        $titles = [
            81 => 'Indonesia Berdaulat, Adil, dan Makmur',
            80 => 'Sinergi Penanganan Banjir dan Drainase Terpadu',
            79 => 'Rapat Paripurna LKPJ Wali Kota Akhir Tahun Anggaran',
            78 => 'Pengawasan Raperda Perlindungan UMKM',
            77 => 'Reses Masa Sidang I: Suara dari Kecamatan',
            76 => 'Catatan Akhir Tahun DPRD Kota Tangerang',
            75 => 'Evaluasi Layanan Kesehatan Masyarakat',
            74 => 'Dirgahayu RI ke-80',
        ];

        $editions = [];
        foreach ($months as $no => [$period, $year, $pages, $size]) {
            $editions[] = [
                'number' => $no,
                'title' => $titles[$no],
                'slug' => "parlementaria-edisi-{$no}",
                'series' => 'Parlementaria',
                'category' => 'majalah',
                'period' => $period,
                'year' => $year,
                'pages' => $pages,
                'size' => $size,
                'allow_download' => $no !== 79,
                'cover_url' => null,
            ];
        }

        $editions[] = [
            'number' => 14,
            'title' => 'Buletin DPRD: Laporan Pengawasan Raperda Pendidikan',
            'slug' => 'buletin-dprd-edisi-14',
            'series' => 'Buletin DPRD',
            'category' => 'buletin',
            'period' => 'Juli 2026',
            'year' => 2026,
            'pages' => 16,
            'size' => '4.2 MB',
            'allow_download' => true,
            'cover_url' => null,
        ];

        return $editions;
    }

    public static function latest(): array
    {
        return self::all()[0];
    }

    public static function find(string $slug): ?array
    {
        foreach (self::all() as $edition) {
            if ($edition['slug'] === $slug) {
                return $edition;
            }
        }

        return null;
    }

    public static function filter(?string $category, ?int $year, ?string $q, string $sort = 'terbaru'): array
    {
        $items = array_filter(self::all(), function ($e) use ($category, $year, $q) {
            if ($category && $e['category'] !== $category) {
                return false;
            }
            if ($year && $e['year'] !== $year) {
                return false;
            }
            if ($q && stripos($e['title'] . ' ' . $e['series'] . ' ' . $e['number'], $q) === false) {
                return false;
            }

            return true;
        });

        $items = array_values($items);

        if ($sort === 'terlama') {
            $items = array_reverse($items);
        }

        return $items;
    }

    public static function years(): array
    {
        $years = array_unique(array_column(self::all(), 'year'));
        rsort($years);

        return $years;
    }

    public static function toc(): array
    {
        return [
            ['title' => 'Redaksi', 'desc' => 'Dari Kota Tangerang, Menguatkan Indonesia', 'page' => 2],
            ['title' => 'Laporan Utama', 'desc' => 'Kolaborasi Lintas Fraksi Kawal APBD 2026', 'page' => 4],
            ['title' => 'Fokus Legislasi', 'desc' => 'Pengesahan Raperda Perlindungan UMKM', 'page' => 6],
            ['title' => 'Suara Warga', 'desc' => 'Rangkuman Reses: Drainase dan Penerangan Jalan', 'page' => 10],
            ['title' => 'Galeri Parlemen', 'desc' => 'Kunjungan Kerja Komisi dan Dialog Publik', 'page' => 12],
            ['title' => 'Profil Komisi IV', 'desc' => 'Pengawasan Infrastruktur Berkelanjutan', 'page' => 14],
        ];
    }
}
