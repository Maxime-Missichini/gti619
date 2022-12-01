<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
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

Route::post('/config', PostController::class)->middleware('can:see-admin');

Route::get('/affaire', function () {
    return view('client.affaire');
})->middleware('can:see-affaire');

Route::get('/residentiel', function () {
    return view('client.residentiel');
})->middleware('can:see-residentiel');

Route::get('/admin', function () {
    return view('admin');
})->middleware('can:see-admin');

Route::get('/caddy-check', 'App\Http\Controllers\CaddyController@check');

Auth::routes();
