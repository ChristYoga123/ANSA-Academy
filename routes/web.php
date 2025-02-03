<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukDigital;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KarirController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelasAnsaController;
use App\Http\Controllers\MentoringController;
use App\Http\Controllers\ProofreadingController;
use App\Http\Controllers\ProdukDigitalController;
use Illuminate\Support\Facades\Artisan;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [BerandaController::class, 'index'])->name('index');

// Blog
Route::prefix('blog')->name('blog.')->group(function()
{
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::post('/search', [BlogController::class, 'search'])->name('search');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

Route::prefix('lomba')->name('lomba.')->group(function()
{
    Route::get('/', [LombaController::class, 'index'])->name('index');
    Route::get('/{slug}', [LombaController::class, 'show'])->name('show');
});

Route::prefix('produk-digital')->name('produk-digital.')->group(function()
{
    Route::get('/', [ProdukDigitalController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProdukDigitalController::class, 'show'])->name('show');
    Route::post('/search', [ProdukDigitalController::class, 'search'])->name('search');
    Route::post('/beli/{slug}', [ProdukDigitalController::class, 'beli'])->name('beli');
});

Route::prefix('event')->name('event.')->group(function()
{
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{slug}', [EventController::class, 'show'])->name('show');
    Route::post('/beli/{slug}', [EventController::class, 'beli'])->name('beli');
});

Route::prefix('karir')->name('karir.')->group(function()
{
    Route::get('/', [KarirController::class, 'index'])->name('index');
    Route::get('/{id}', [KarirController::class, 'show'])->name('show');
    Route::post('/{id}', [KarirController::class, 'store'])->name('store');
});

Route::prefix('program')->group(function()
{
    Route::prefix('mentoring')->name('mentoring.')->group(function()
    {
        Route::get('/', [MentoringController::class, 'index'])->name('index');
        Route::get('/{slug}', [MentoringController::class, 'show'])->name('show');
        Route::post('/search', [MentoringController::class, 'search'])->name('search');
        Route::post('/beli/{slug}', [MentoringController::class, 'beli'])->name('beli');
    });

    Route::prefix('proofreading')->name('proofreading.')->group(function()
    {
        Route::get('/', [ProofreadingController::class, 'index'])->name('index');
        Route::post('/search', [ProofreadingController::class, 'search'])->name('search');
        Route::get('/{slug}', [ProofreadingController::class, 'show'])->name('show');
        Route::post('/beli/{slug}', [ProofreadingController::class, 'beli'])->name('beli');
    });

    Route::prefix('kelas-ansa')->name('kelas-ansa.')->group(function()
    {
        Route::get('/', [KelasAnsaController::class, 'index'])->name('index');
        Route::post('/search', [KelasAnsaController::class, 'search'])->name('search');
        Route::get('/{slug}', [KelasAnsaController::class, 'show'])->name('show');
        Route::post('/beli/{slug}', [KelasAnsaController::class, 'beli'])->name('beli');
    });
});

Route::get('/symlink', function()
{
    Artisan::call('storage:link');

    echo 'ok';
});