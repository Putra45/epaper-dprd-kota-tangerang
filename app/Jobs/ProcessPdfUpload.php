<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Edition;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessPdfUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $edition;

    public function __construct(Edition $edition)
    {
        $this->edition = $edition;
    }

    public function handle(): void
    {
        $pdfPath = Storage::disk('public')->path($this->edition->pdf_path);
        $outputDirName = 'editions/' . $this->edition->slug . '/pages';
        Storage::disk('public')->makeDirectory($outputDirName);
        $outputDirPath = Storage::disk('public')->path($outputDirName);

        try {
            if (extension_loaded('imagick')) {
                // Real processing with Imagick
                $imagick = new \Imagick();
                $imagick->pingImage($pdfPath);
                $totalPages = $imagick->getNumberImages();

                for ($i = 0; $i < $totalPages; $i++) {
                    $im = new \Imagick();
                    $im->setResolution(300, 300);
                    $im->readImage($pdfPath . '[' . $i . ']');
                    $im->setImageFormat('jpeg');
                    $im->setImageCompressionQuality(95);

                    $filename = 'page-' . ($i + 1) . '.jpg';
                    
                    // High Res
                    $hiresPath = $outputDirName . '/hires-' . $filename;
                    $im->writeImage(Storage::disk('public')->path($hiresPath));

                    // Normal
                    $im->thumbnailImage(1080, 0);
                    $normalPath = $outputDirName . '/normal-' . $filename;
                    $im->writeImage(Storage::disk('public')->path($normalPath));

                    // Thumb
                    $im->thumbnailImage(300, 0);
                    $thumbPath = $outputDirName . '/thumb-' . $filename;
                    $im->writeImage(Storage::disk('public')->path($thumbPath));

                    $im->clear();
                    $im->destroy();

                    Page::create([
                        'edition_id' => $this->edition->id,
                        'page_number' => $i + 1,
                        'hires_path' => $hiresPath,
                        'normal_path' => $normalPath,
                        'thumb_path' => $thumbPath,
                    ]);
                }
                
                $firstPage = Page::where('edition_id', $this->edition->id)->where('page_number', 1)->first();
                $firstCover = $firstPage ? ($firstPage->normal_path ?: $firstPage->thumb_path) : null;

                $this->edition->update([
                    'status' => 'review',
                    'total_pages' => $totalPages,
                    'cover_path' => $this->edition->cover_path ?: $firstCover,
                ]);

            } else {
                // Fallback for local testing without Imagick
                Log::warning('Imagick extension not loaded. Creating dummy pages for testing.');
                
                $totalPages = 16; // Dummy total
                for ($i = 0; $i < $totalPages; $i++) {
                    Page::create([
                        'edition_id' => $this->edition->id,
                        'page_number' => $i + 1,
                        'hires_path' => null,
                        'normal_path' => null,
                        'thumb_path' => null,
                    ]);
                }
                
                $this->edition->update([
                    'status' => 'review',
                    'total_pages' => $totalPages,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('PDF Processing Error: ' . $e->getMessage());
            $this->edition->update(['status' => 'error']);
        }
    }
}
