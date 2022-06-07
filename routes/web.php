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

Route::get('/', function () {
    return view('welcome');
});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::group(['prefix'=>'suggestion'], function(){
//     Route::get('/users', [App\Http\Controllers\HomeController::class, 'suggestedUser']);
//     Route::get('/users/more/{number}', [App\Http\Controllers\HomeController::class, 'suggestedMoreUser']);
// });