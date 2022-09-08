<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('threads')->group(function () {
    Route::get('/', [ThreadController::class, 'index']);
    Route::get('/{thread}', [ThreadController::class, 'show'])->middleware('auth');
    Route::post('/create', [ThreadController::class, 'create']);
    Route::post('/edit/{thread}', [ThreadController::class, 'update']);
    Route::post('/delete/{thread}', [ThreadController::class, 'delete']);
    Route::post('/{thread}/add-comment', [CommentController::class, 'store']);
    Route::post('/{thread}/{comment}/reply', [CommentController::class, 'replyStore']);
    Route::get('/{thread}/comments', [CommentController::class, 'showAllCommentsOfAThread']);

});
