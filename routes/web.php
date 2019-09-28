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

Route::get('/threads/', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}/', 'ThreadsController@show');
Route::get('/threads/create/', 'ThreadsController@create');
Route::post('/threads/', 'ThreadsController@store');
Route::post('/threads/{channelId}/{thread}/replies/', 'RepliesController@store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
