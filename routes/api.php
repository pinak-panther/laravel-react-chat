<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\UserController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('/user',function(){
   return User::find(1);
});
Route::group(['middleware'=>'auth:api'],function(){
//    Route::get('/get-all-user', [UserController::class,'getAllUser']);
//    Route::post('/send-message', [MessageController::class,'store']);
});
Route::get('/get-all-user', [UserController::class,'getAllUser']);
Route::post('/send-message', [MessageController::class,'store']);
