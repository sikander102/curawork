<?php

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
Route::prefix("suggestion")->group(function(){
    Route::get('/users', [App\Http\Controllers\SuggestionController::class, 'index']);
    Route::get('/users/more/{number}', [App\Http\Controllers\SuggestionController::class, 'getMoreSuggestions']);
    Route::get('/sendRequest', [App\Http\Controllers\SuggestionController::class, 'create']);
});
Route::prefix("request")->group(function(){
    Route::get('/users', [App\Http\Controllers\SentRequestController::class, 'index']);
    Route::get('/users/more/{number}', [App\Http\Controllers\SentRequestController::class, 'getMoreRequests']);
    Route::get('/deleteRequest', [App\Http\Controllers\SentRequestController::class, 'destroy']);
    //received request routes
    Route::get('/receive/users', [App\Http\Controllers\ReceiveRequestController::class, 'index']);
    Route::get('/acceptRequest', [App\Http\Controllers\ReceiveRequestController::class, 'create']);
});
Route::prefix("connection")->group(function(){
    Route::get('/users', [App\Http\Controllers\ConnectionController::class, 'index']);
    Route::get('/removeConnection', [App\Http\Controllers\ConnectionController::class, 'destroy']);
    Route::get('/inCommon', [App\Http\Controllers\ConnectionController::class, 'getConnectionInCommon']);
    Route::get('/users/more/{number}', [App\Http\Controllers\ConnectionController::class, 'getMoreConnection']);
});