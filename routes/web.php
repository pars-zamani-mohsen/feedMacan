<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

//Auth::routes();
//Route::redirect('/', '/login')->name('start');
//Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', 'HomeController@index');

/* Telegram bot */
Route::post('/_telegram', 'TelegramController@handleRequest')->name('telegram.HandleRequest');

/* Clear laravel cache */
Route::get('/cc', 'HomeController@clear');
