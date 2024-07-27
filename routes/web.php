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
    return view('home');
});
Route::post('/login', [LoginController::class,'login']);

Route::get('/about', function () {
    return view('about-us');
});
Route::get('/feedback', function () {
    return view('feedback');
});
Route::get('/new-center', function () {
    return view('new-center');
});
Route::get('/registeration', function () {
    return view('registration');
});
