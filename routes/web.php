<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
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

Route::get('/home', function () {
    return view('home');
});

Route::get('/affaire', function () {
    return view('affaire');
})->middleware('can:see-affaire');

Route::get('/residentiel', function () {
    return view('residentiel');
})->middleware('can:see-residentiel');

Route::get('/admin', function () {
    return view('admin');
})->middleware('can:see-admin');

Auth::routes();
