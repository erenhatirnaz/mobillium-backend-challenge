<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\VoteController;

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

Route::name('article.')->middleware('auth')->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'createPage'])->name('createPage');
    Route::post('/articles/create', [ArticleController::class, 'create'])->name('create');

    Route::get('/articles/{id}/edit', [ArticleController::class, 'editPage'])->name('editPage');
    Route::put('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('edit');

    Route::put('/article/{id}/publish', function () {
        return response("501 Not Implemented!", 501);
    })->name('publish');
    Route::put('/article/{id}/unpublish', function () {
        return response("501 Not Implemented!", 501);
    })->name('unpublish');

    Route::delete('/article/{id}/delete', function () {
        return response("501 Not Implemented!", 501);
    })->name('delete');
});

Route::name('vote.')->middleware(['auth'])->group(function () {
    Route::post('/vote', [VoteController::class, 'add'])->name('add');
    Route::delete('/vote/delete', [VoteController::class, 'delete'])->name('delete');
});

Route::name('writer.')->middleware(['auth','role:writer'])->group(function () {
    Route::get('/writer', [ArticleController::class, 'list'])->name('dashboard');
});

Route::name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin', [ArticleController::class, 'list'])->name('dashboard');
});

Route::name('moderator.')->middleware(['auth','role:moderator'])->group(function () {
    Route::get('/moderator', function () {
        return response("501 Not Implemented!", 501);
    })->name('dashboard');
});
