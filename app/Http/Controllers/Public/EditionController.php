<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Edition;
use Illuminate\Http\Request;

class EditionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', '')) ?: null;
        $sort = $request->query('urut') === 'terlama' ? 'asc' : 'desc';
        $category = $request->query('kategori');
        $year = $request->filled('tahun') ? (int) $request->query('tahun') : null;

        $query = Edition::where('status', 'published');

        if ($q) {
            $query->where('title', 'like', "%{$q}%");
        }

        if ($category && $category !== 'semua') {
            $query->where('category', $category);
        }

        if ($year) {
            $query->whereYear('release_date', $year);
        }

        $editions = $query->orderBy('release_date', $sort)->get();

        return view('public.catalog', compact('editions', 'q', 'sort'));
    }
}
