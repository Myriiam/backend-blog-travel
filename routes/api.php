<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;
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
Route::post('/add-article', [ArticleController::class, 'addArticle']);
Route::get('/all-categories', [ArticleController::class, 'getCategories']);
Route::get('/show-all', [ArticleController::class, 'showAll']);
Route::get('/my-articles', [ArticleController::class, 'showMyArticles']);
Route::get('/show-article/{id}', [ArticleController::class, 'showArticle'])->name('show_article');
Route::patch('/{id}/update-article', [ArticleController::class, 'updateArticle'])->name('update_article');
Route::delete('/{id}/delete-article', [ArticleController::class, 'deleteArticle'])->name('delete_article');

//Users
Route::patch('/edit-profile', [UserController::class, 'editProfile']);
Route::delete('/delete-account', [UserController::class, 'deleteAccount']);

//Comments
Route::post('/{id}/add-comment', [CommentController::class, 'addComment']);
Route::patch('/{id}/edit-comment', [CommentController::class, 'editComment']);
Route::delete('/{id}/delete-comment', [CommentController::class, 'deleteComment']);

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

