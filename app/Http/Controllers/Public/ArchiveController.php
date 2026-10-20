<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Edition;

class ArchiveController extends Controller
{
    public function index()
    {
        $editions = Edition::where('status', 'published')->orderBy('release_date', 'desc')->get();
        
        $archives = [];
        foreach ($editions as $edition) {
            $year = $edition->release_date->format('Y');
            $archives[$year][] = $edition;
        }

        return view('public.archive', compact('archives'));
    }
}
