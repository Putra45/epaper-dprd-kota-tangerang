<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Edition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FlipbookController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $edition = Edition::where('slug', $slug)->with('pages')->firstOrFail();

        $total = $edition->total_pages;
        $pages = $edition->pages->map(function ($page) {
            return [
                'id' => $page->id,
                'number' => $page->page_number,
                'thumb' => $page->thumb_path ? Storage::url($page->thumb_path) : null,
                'normal' => $page->normal_path ? Storage::url($page->normal_path) : null,
                'hires' => $page->hires_path ? Storage::url($page->hires_path) : null,
            ];
        })->toArray();

        $toc = []; // Implement DB-based TOC in the future

        $startPage = max(1, min($total ?: 1, (int) $request->query('hal', 1)));

        $back = url()->previous();
        if ($back === url()->current() || ! str_starts_with($back, url('/'))) {
            $back = url('/');
        }

        return view('public.reader', compact('edition', 'pages', 'toc', 'startPage', 'back', 'total'));
    }

    public function download(string $slug)
    {
        $edition = Edition::where('slug', $slug)->firstOrFail();
        abort_if(! $edition->allow_download || !$edition->pdf_path, 404);

        return response()->download(Storage::disk('public')->path($edition->pdf_path), $edition->slug . '.pdf');
    }
}
