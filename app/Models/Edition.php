<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Edition extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'category', 'release_date',
        'pdf_path', 'allow_download', 'status', 'total_pages', 'cover_path',
        'flipbook_width', 'flipbook_height', 'is_spread', 'excluded_pages'
    ];

    protected $casts = [
        'release_date' => 'date',
        'allow_download' => 'boolean',
        'is_spread' => 'boolean',
        'excluded_pages' => 'array',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('page_number');
    }

    /**
     * Dapatkan URL gambar cover majalah.
     * Jika cover khusus tidak diunggah, otomatis mengambil halaman 1 PDF.
     * Mengembalikan URL root-relative (/storage/...) agar valid diakses dari domain server mana pun
     * tanpa terpengaruh jika APP_URL di .env masih 'http://localhost'.
     */
    public function getCoverUrlAttribute(): ?string
    {
        $path = $this->cover_path;

        // Jika cover_path belum ada atau filenya tidak ada di disk, fallback ke halaman 1
        if (!$path || !Storage::disk('public')->exists($path)) {
            $firstPage = $this->relationLoaded('pages')
                ? $this->pages->firstWhere('page_number', 1)
                : $this->pages()->where('page_number', 1)->first();

            if ($firstPage) {
                $candidates = array_values(array_filter([
                    $firstPage->normal_path,
                    $firstPage->hires_path,
                    $firstPage->thumb_path,
                ]));

                $foundPath = null;
                foreach ($candidates as $cand) {
                    if (Storage::disk('public')->exists($cand)) {
                        $foundPath = $cand;
                        break;
                    }
                }

                $path = $foundPath ?: ($candidates[0] ?? null);
            }
        }

        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return '/storage/' . ltrim($path, '/');
    }
}
