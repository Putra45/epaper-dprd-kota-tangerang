<?php

namespace Database\Seeders;

use App\Models\Edition;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $editionsData = [
            ['title' => 'Parlementaria Edisi 82', 'category' => 'majalah', 'date' => '2026-10-01'],
            ['title' => 'Parlementaria Edisi 81', 'category' => 'majalah', 'date' => '2026-08-01'],
            ['title' => 'Parlementaria Edisi 80', 'category' => 'majalah', 'date' => '2026-05-01'],
            ['title' => 'Parlementaria Edisi 79', 'category' => 'majalah', 'date' => '2026-02-01'],
            ['title' => 'Parlementaria Edisi 78', 'category' => 'majalah', 'date' => '2025-11-01'],
            ['title' => 'Buletin Kinerja Q3', 'category' => 'buletin', 'date' => '2026-09-01'],
            ['title' => 'Buletin Kinerja Q2', 'category' => 'buletin', 'date' => '2026-06-01'],
        ];

        foreach ($editionsData as $data) {
            $edition = Edition::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'description' => 'Ini adalah deksripsi singkat dari ' . $data['title'] . '. Membahas berbagai capaian dan kegiatan DPRD Kota Tangerang.',
                'category' => $data['category'],
                'release_date' => $data['date'],
                'allow_download' => true,
                'status' => 'published',
                'total_pages' => 16,
            ]);

            for ($i = 1; $i <= 16; $i++) {
                Page::create([
                    'edition_id' => $edition->id,
                    'page_number' => $i,
                    'thumb_path' => null,
                    'normal_path' => null,
                    'hires_path' => null,
                ]);
            }
        }
    }
}
