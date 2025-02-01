<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BerandaController;

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

Route::get('/symlink', function()
{
    $target = storage_path('app/public');
    $link = $_SERVER['DOCUMENT_ROOT'] . '/storage';
    symlink($target, $link);

    echo 'ok';
});