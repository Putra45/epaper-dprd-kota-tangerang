<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edition;
use Illuminate\Support\Str;
use App\Jobs\ProcessPdfUpload;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $editions = Edition::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.dashboard', compact('editions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:102400',
        ]);

        $tempTitle = 'Draft ' . now()->format('Y-m-d H:i:s');
        $slug = Str::slug($tempTitle);
        
        $originalSlug = $slug;
        $counter = 1;
        while (Edition::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $edition = Edition::create([
            'title' => $tempTitle,
            'slug' => $slug,
            'category' => 'majalah',
            'release_date' => now()->format('Y-m-d'),
            'status' => 'processing',
        ]);

        if ($request->hasFile('pdf_file')) {
            // Beri waktu proses ekstra agar tidak timeout (5 menit)
            set_time_limit(300);
            
            $pdfPath = $request->file('pdf_file')->storeAs('editions', $slug . '.pdf', 'public');
            $edition->update(['pdf_path' => $pdfPath]);
            
            // Dispatch synchronously so it's ready for review immediately
            ProcessPdfUpload::dispatchSync($edition);
        }

        return redirect()->route('admin.editions.review', $edition->id)->with('success', 'PDF berhasil diproses, silakan atur layout dan lengkapi data.');
    }



    public function review(Edition $edition)
    {
        $edition->load('pages');
        return view('admin.review', compact('edition'));
    }

    public function publish(Request $request, Edition $edition)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'release_date' => 'required|date',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'flipbook_width' => 'required|integer|min:300|max:2000',
            'flipbook_height' => 'required|integer|min:300|max:2000',
            'is_spread' => 'boolean',
            'excluded_pages' => 'array',
        ]);

        $coverPath = $edition->cover_path;
        if ($request->hasFile('cover_image')) {
            // Hanya hapus file lama jika merupakan file sampul kustom
            if ($coverPath && str_starts_with($coverPath, 'editions/covers/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('editions/covers', 'public');
        }

        // Jika cover belum ditentukan, otomatis gunakan gambar halaman 1 PDF
        if (!$coverPath) {
            $firstPage = $edition->pages()->where('page_number', 1)->first();
            if ($firstPage) {
                $coverPath = $firstPage->normal_path ?: $firstPage->thumb_path ?: $firstPage->hires_path;
            }
        }

        // Only update slug if title changed significantly from draft, or just leave it. Let's update slug based on title if it was a draft
        $slug = $edition->slug;
        if (str_starts_with($edition->title, 'Draft ')) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $counter = 1;
            while (Edition::where('slug', $slug)->where('id', '!=', $edition->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $edition->update([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'release_date' => $request->release_date,
            'description' => $request->description,
            'allow_download' => $request->has('allow_download'),
            'cover_path' => $coverPath,
            'flipbook_width' => $request->flipbook_width,
            'flipbook_height' => $request->flipbook_height,
            'is_spread' => $request->has('is_spread'),
            'excluded_pages' => $request->excluded_pages ?? [],
            'status' => 'published',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Edisi berhasil diterbitkan ke publik!');
    }

    public function destroy(Edition $edition)
    {
        // 1. Delete physical pages directory
        $pagesDirectory = 'editions/' . $edition->slug;
        if (Storage::disk('public')->exists($pagesDirectory)) {
            Storage::disk('public')->deleteDirectory($pagesDirectory);
        }

        // 2. Delete original PDF file
        if ($edition->pdf_path && Storage::disk('public')->exists($edition->pdf_path)) {
            Storage::disk('public')->delete($edition->pdf_path);
        }

        // 3. Delete cover image if it exists and is outside the pages directory
        if ($edition->cover_path && str_starts_with($edition->cover_path, 'editions/covers/') && Storage::disk('public')->exists($edition->cover_path)) {
            Storage::disk('public')->delete($edition->cover_path);
        }

        // 4. Delete page records from DB
        $edition->pages()->delete();

        // 5. Delete edition record from DB
        $edition->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Edisi beserta seluruh filenya berhasil dihapus secara permanen.');
    }
}
