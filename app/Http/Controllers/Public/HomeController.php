<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Edition;

class HomeController extends Controller
{
    public function index()
    {
        $latest = Edition::where('status', 'published')->orderBy('release_date', 'desc')->first();
        $shelf = Edition::where('status', 'published')->orderBy('release_date', 'desc')->limit(5)->get();

        return view('public.home', compact('latest', 'shelf'));
    }
}
