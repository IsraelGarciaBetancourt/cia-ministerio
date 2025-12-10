<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PrivateFileController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

// Redirigir home → blog
Route::get('/', function () {
    return redirect()->route('blog.index');
});

// Blog público
Route::get('/blog', [PostController::class, 'publicIndex'])->name('blog.index');
Route::get('/blog/{post}', [PostController::class, 'show'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Login minimalista (sin Breeze)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Panel Administrativo (requiere autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // CRUD de posts
    Route::resource('admin/posts', PostController::class)->except(['show'])->names([
        'index' => 'posts.index',
        'create' => 'posts.create',
        'store' => 'posts.store',
        'edit' => 'posts.edit',
        'update' => 'posts.update',
        'destroy' => 'posts.destroy'
    ]);

    // Subida de media
    Route::post('admin/posts/{post}/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('admin/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Archivos privados (zona solo admin)
    Route::get('admin/private-files', [PrivateFileController::class, 'index'])->name('private-files.index');
    Route::post('admin/private-files', [PrivateFileController::class, 'store'])->name('private-files.store');
    Route::get('admin/private-files/{privateFile}/download', [PrivateFileController::class, 'download'])->name('private-files.download');
    Route::delete('admin/private-files/{privateFile}', [PrivateFileController::class, 'destroy'])->name('private-files.destroy');
});
