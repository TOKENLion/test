<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'exchange-rate', 'as' => 'exchangeRate'], function () {
    Route::get('/', 'ExchangeRate@getData');
    Route::post('/', 'ExchangeRate@store')->name('.store');
});

Route::group(['prefix' => 'exchange-entity', 'as' => 'exchangeEntity'], function () {
    Route::get('/', 'ExchangeEntity@index');
    Route::post('/', 'ExchangeEntity@store')->name('.store');
});