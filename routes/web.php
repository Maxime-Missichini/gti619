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

/**
 * Route pour la page d'acceuil
 */
Route::get('/', function () {
    return view('home');
});

/**
 * Route pour la page d'acceuil
 */
Route::get('/home', function () {
    return view('home');
});

/**
 * Route pour changer son mot de passe, réservé aux utilisateurs authentifiés
 */
Route::get('/password/change', function (){
    return view('auth.passwords.change');
})->middleware('auth');

/**
 * Route pour changer son mot de passe, réservé aux utilisateurs authentifiés
 */
Route::post('/password/change', 'App\Http\Controllers\Auth\ResetPasswordController@updateLogged')
    ->middleware('auth');

/**
 * Route pour modifier les options de sécurité, réservé aux roles ayant l'abilité associée
 */
Route::post('/config', PostController::class)->middleware('can:see-admin');

/**
 * Route pour le panel affaire, réservé aux roles ayant l'abilité associée
 */
Route::get('/affaire', function () {
    return view('client.affaire');
})->middleware('can:see-affaire');

/**
 * Route pour le panel résidentiel, réservé aux roles ayant l'abilité associée
 */
Route::get('/residentiel', function () {
    return view('client.residentiel');
})->middleware('can:see-residentiel');

/**
 * Route pour le panel administrateur, réservé aux roles ayant l'abilité associée
 */
Route::get('/admin', function () {
    return view('admin');
})->middleware('can:see-admin');

/**
 * Route pour Caddy afin d'activer HTTPS
 */
Route::get('/caddy-check', 'App\Http\Controllers\CaddyController@check');

/**
 * Routes du module Auth (par défaut)
 */
Auth::routes();
