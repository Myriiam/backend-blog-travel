<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!!!!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
//Route::post('/refresh', [AuthController::class, 'refresh']);
// Route::get('/me', [AuthController::class, 'me']);
//Article
Route::post('/add-article', [App\Http\Controllers\ArticleController::class, 'addArticle'])->name('add_article');
Route::get('/show-all', [App\Http\Controllers\ArticleController::class, 'showAll'])->name('show_all');
Route::get('/show-article/{id}', [App\Http\Controllers\ArticleController::class, 'showArticle'])->name('show_article');
Route::patch('/{id}/edit-article', [App\Http\Controllers\ArticleController::class, 'editArticle'])->name('edit_article');
Route::delete('/{id}/delete-article', [App\Http\Controllers\ArticleController::class, 'deleteArticle'])->name('delete_article');

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

