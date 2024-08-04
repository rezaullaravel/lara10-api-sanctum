<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\UserAuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function(){

    //get api for show user list
    Route::get('/users/{id?}',[UserApiController::class,'showUserList']);
    //update single user
    Route::put('/update-user/{id}',[UserApiController::class,'updateUser']);
    //delete single user
    Route::delete('/delete-user/{id}',[UserApiController::class,'deleteUser']);
    //delete single user with json
    Route::delete('/delete-user-json',[UserApiController::class,'deleteUserJson']);
    //delete multiple user
    Route::delete('/delete-multiple-user/{ids}',[UserApiController::class,'deleteMultipleUser']);
    //delete multiple user with json
    Route::delete('/delete-multiple-user-json',[UserApiController::class,'deleteMultipleUserJson']);

});




//user registration
Route::post('/register',[UserAuthApiController::class,'register']);

//multiple user registration with json
Route::post('/multiple-register',[UserAuthApiController::class,'multipleRegister']);

//user login
Route::post('/login',[UserAuthApiController::class,'login'])->name('login');

//usesr logout
Route::post('/logout',[UserAuthApiController::class,'logout'])->middleware('auth:sanctum')->name('logout');
