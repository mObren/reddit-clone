<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'redirect'])->name('login');
Route::group(['middleware' => ['auth']], function() {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('/auth/callback', [AuthController::class, 'callback']);