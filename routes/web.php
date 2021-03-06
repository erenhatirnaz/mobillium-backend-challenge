<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');

Route::name('writer.')->middleware(['role:writer'])->group(function () {
    Route::get('/writer', function () {
        return response("501 Not Implemented!", 501);
    })->name('dashboard');
});

Route::name('admin.')->middleware(['role:admin'])->group(function () {
    Route::get('/admin', function () {
        return response("501 Not Implemented!", 501);
    })->name('dashboard');
});

Route::name('moderator.')->middleware(['role:moderator'])->group(function () {
    Route::get('/moderator', function () {
        return response("501 Not Implemented!", 501);
    })->name('dashboard');
});
