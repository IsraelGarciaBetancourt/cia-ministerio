<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PrivateFileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileGroupController;
use App\Http\Controllers\FileCategoryController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

// Redirigir home → blog
Route::get('/', fn() => redirect()->route('blog.index'));

// Blog público
Route::get('/blog', [PostController::class, 'publicIndex'])->name('blog.index');
Route::get('/blog/{post}', [PostController::class, 'show'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Login minimalista
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Panel Administrativo
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | POSTS (Blog backend)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {

        // CRUD de posts
        Route::resource('posts', PostController::class)->except(['show'])->names([
            'index' => 'posts.index',
            'create' => 'posts.create',
            'store' => 'posts.store',
            'edit' => 'posts.edit',
            'update' => 'posts.update',
            'destroy' => 'posts.destroy',
        ]);

        // Subida y eliminación de media
        Route::post('posts/{post}/media', [MediaController::class, 'store'])->name('media.store');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | ARCHIVOS PRIVADOS (Sistema de Grupos, Categorías y Archivos)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/files')->name('files.')->group(function () {

        // GRUPOS
        Route::resource('groups', FileGroupController::class);

        // CATEGORÍAS
        Route::resource('groups.categories', FileCategoryController::class);

        // ARCHIVOS
        Route::resource('groups.categories.files', PrivateFileController::class);

        // PREVIEW DE PDF
        Route::get('attachments/{attachment}/preview', 
            [PrivateFileController::class, 'preview']
        )->name('attachments.preview');

        // VER ARCHIVO (IMAGEN, PDF, DOCX, ETC)
        Route::get('attachments/{attachment}/view',
            [PrivateFileController::class, 'viewAttachment']
        )->name('attachments.view');

        // DESCARGAR
        Route::get('attachments/{attachment}/download',
            [PrivateFileController::class, 'downloadAttachment']
        )->name('attachments.download');

        // ELIMINAR
        Route::delete('attachments/{attachment}',
            [PrivateFileController::class, 'destroyAttachment']
        )->name('attachments.destroy');
    });


});
