<?php

use App\Http\Controllers\Public\ArchiveController;
use App\Http\Controllers\Public\EditionController;
use App\Http\Controllers\Public\FlipbookController;
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/majalah', [EditionController::class, 'index'])->name('catalog');
Route::get('/arsip', [ArchiveController::class, 'index'])->name('archive');
Route::get('/baca/{slug}', [FlipbookController::class, 'show'])->name('reader');
Route::get('/unduh/{slug}', [FlipbookController::class, 'download'])->name('download');

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LoginController;

$adminPath = env('ADMIN_PATH', 'panel-k7x9q2m4');

Route::prefix($adminPath)->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/editions', [AdminController::class, 'store'])->name('editions.store');
        Route::get('/editions/{edition}/review', [AdminController::class, 'review'])->name('editions.review');
        Route::put('/editions/{edition}/publish', [AdminController::class, 'publish'])->name('editions.publish');
        Route::delete('/editions/{edition}', [AdminController::class, 'destroy'])->name('editions.destroy');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/logout', function () { return redirect()->route('admin.login'); });
    });
});
