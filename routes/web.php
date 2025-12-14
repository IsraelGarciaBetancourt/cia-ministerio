<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PrivateFileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileGroupController;
use App\Http\Controllers\FileCategoryController;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\FinanceEntryController;
use App\Http\Controllers\Admin\BrotherController;
use App\Http\Controllers\Admin\FinanceTypeController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

// Redirigir home → blog
Route::get('/', fn() => redirect()->route('landing.inicio'));

// LANDING
Route::get('/inicio', [LandingController::class, 'index'])->name('landing.inicio');
Route::get('/sobre-nosotros', [LandingController::class, 'about'])->name('landing.about');

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
    Route::prefix('admin')->middleware('role:admin|apostol')->group(function () {

        // CRUD de posts
        Route::resource('posts', PostController::class)->except(['show'])->names([
            'index' => 'posts.index',
            'create' => 'posts.create',
            'store' => 'posts.store',
            'edit' => 'posts.edit',
            'update' => 'posts.update',
            'destroy' => 'posts.destroy',
        ]);

        // NUEVA RUTA: Subida individual por AJAX
        Route::post('posts/{post}/media/single', [MediaController::class, 'storeSingle'])
            ->name('media.storeSingle');

        // Subida y eliminación de media
        Route::post('posts/{post}/media', [MediaController::class, 'store'])->name('media.store');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | ARCHIVOS PRIVADOS (Sistema de Grupos, Categorías y Archivos)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/files')->name('files.')->middleware('role:admin|apostol')->group(function () {

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

    Route::prefix('admin/finanzas')
    ->name('finances.')
    ->middleware(['auth', 'role:admin|apostol'])
    ->group(function () {

        // Cabeceras de finanzas
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/crear', [FinanceController::class, 'create'])->name('create');
        Route::post('/', [FinanceController::class, 'store'])->name('store');
        Route::get('/{finance}', [FinanceController::class, 'show'])->name('show');

        Route::patch('/{finance}/cerrar', [FinanceController::class, 'close'])->name('close');
        Route::patch('/{finance}/abrir', [FinanceController::class, 'reopen'])->name('reopen');

        // Movimientos financieros
        Route::post('/{finance}/movimientos', [FinanceEntryController::class, 'store'])
            ->name('entries.store');

        Route::delete('/movimientos/{entry}', [FinanceEntryController::class, 'destroy'])
            ->name('entries.destroy');
    });

    Route::prefix('admin/tipos-finanza')
        ->name('finance-types.')
        ->middleware(['auth', 'role:admin|apostol'])
        ->group(function () {

            Route::get('/', [FinanceTypeController::class, 'index'])->name('index');
            Route::get('/crear', [FinanceTypeController::class, 'create'])->name('create');
            Route::post('/', [FinanceTypeController::class, 'store'])->name('store');

            Route::get('/{type}/editar', [FinanceTypeController::class, 'edit'])->name('edit');
            Route::put('/{type}', [FinanceTypeController::class, 'update'])->name('update');

            Route::patch('/{type}/toggle', [FinanceTypeController::class, 'toggle'])->name('toggle');

            Route::delete('/{type}', [FinanceTypeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/hermanos')
    ->name('brothers.')
    ->middleware(['auth', 'role:admin|apostol'])
    ->group(function () {

        Route::get('/', [BrotherController::class, 'index'])->name('index');
        Route::get('/crear', [BrotherController::class, 'create'])->name('create');
        Route::post('/', [BrotherController::class, 'store'])->name('store');

        Route::get('/{brother}/editar', [BrotherController::class, 'edit'])->name('edit');
        Route::put('/{brother}', [BrotherController::class, 'update'])->name('update');

        Route::patch('/{brother}/toggle', [BrotherController::class, 'toggle'])->name('toggle');

        Route::delete('/{brother}', [BrotherController::class, 'destroy'])->name('destroy');
    });

});
