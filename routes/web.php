<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProdukDigital;
use App\Http\Controllers\ProdukDigitalController;

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
});

Route::prefix('event')->name('event.')->group(function()
{
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{slug}', [EventController::class, 'show'])->name('show');
});

Route::get('/symlink', function()
{
    $target = storage_path('app/public');
    $link = $_SERVER['DOCUMENT_ROOT'] . '/storage';
    symlink($target, $link);

    echo 'ok';
});