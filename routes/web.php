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

Route::get('mail', 'MailController@send');

Route::get('carbon', 'CarbonController@index');

Route::get('hello', function(){
    if(Request::has('lang')){
        App::setLocale(Request::get('lang'));
    }
    return view('hello');
});
