<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function() {
Route::get('my-account', 'HomeController@myAccount');
});

Route::middleware('auth')->group(function() {
    Route::get('/widgets-stats', 'StatisticsController@widgets');
    Route::get('/data-tables', 'StatisticsController@datatable');
    Route::get('/markets', 'MarketsController@index');
    Route::get('/assets', 'AssetsController@index');
    Route::get('/sides', 'SidesController@index');
    Route::get('/ownUser', 'HomeController@getAuthUser');
    Route::resource('strategies', 'StrategiesController');
    Route::resource('trades', 'TradesController');
    Route::resource('users', 'UserController');
});