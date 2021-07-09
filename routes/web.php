<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/message', [HomeController::class, 'message'])->name('message');
Route::get('/chat', [HomeController::class, 'chat'])->name('chat');

Route::post('/send-message', [MessageController::class,'store']);
Route::get('/all-messages/to_user/{to_user}', [MessageController::class,'allMessages']);
Route::get('/all-unread-messages/to_user/{to_user}', [MessageController::class,'getUnreadMessage']);
Route::put('/update-message-status/{message}', [MessageController::class,'updateMessageStatus']);
Route::put('/change-message-status-all/{user_id}', [MessageController::class,'changeMessageStatusForUser']);

Route::get('/get-all-user', [UserController::class,'getAllUser']);
Route::get('/user', [UserController::class,'getAuthUer']);
