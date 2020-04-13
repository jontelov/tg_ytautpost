<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
/**
 * User  Auth routes
 */
Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::any('logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/updated-activity', 'TelegramBotController@updatedActivity');
Route::get('/run', 'TelegramBotController@runCommands');
Route::get('/run-webhook', 'TelegramBotController@runWebhook');
Route::post(env('TELEGRAM_BOT_TOKEN') . '/webhook', 'TelegramBotController@tgWebhook');

Route::get('channel-details', 'YoutubeApiController@getChannelById');
Route::get('channel-playlists', 'YoutubeApiController@getPlaylistByChannelId');

Route::get('my-playlists/{auth}', 'GoogleApiClientController@getPlaylists');

Route::any('my-auth/{id}', 'GoogleApiClientController@getAuthGoogleApi');

