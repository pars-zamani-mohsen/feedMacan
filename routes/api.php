<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('login', 'API\UserController@login');

Route::middleware('auth:api')->resource('crm', 'API\CRMController');
Route::group(['prefix' => 'crm', 'middleware' => 'auth:api'], function(){
    Route::get('/fetch-contact-fields', 'API\CRMController@getFields');
    Route::get('/execute/{id}', 'API\CRMController@execute');
});
