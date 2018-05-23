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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('print', 'PrintZPLController@index');
Route::get('PrintZPLController', 'PrintZPLController@printCommands');
Route::any('WebClientPrintController', 'WebClientPrintController@processRequest');

Route::post('validate-sm/{code}', 'FixtureController@validateSM');
Route::post('validate-fm/{code}', 'FixtureController@validateFM');
Route::post('validate-rm/{code}', 'FixtureController@validateRM');



