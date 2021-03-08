<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('/article', [ArticleController::class, 'all']);
    Route::get('/article/export', [ArticleController::class, 'export']);
    Route::post('/article/create', [ArticleController::class, 'create']);
    Route::post('/article/update/{id}', [ArticleController::class, 'update']);
    Route::put('/article/publish/{id}', [ArticleController::class, 'publish']);
    Route::put('/article/unpublish/{id}', [ArticleController::class, 'unpublish']);
    Route::delete('/article/delete/{id}', [ArticleController::class, 'delete']);
});
