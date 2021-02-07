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

Route::middleware(['cors', 'json.response', 'auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors', 'json.response']], function () {

    Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','Auth\ApiAuthController@register')->name('register.api');
    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/articles', 'ArticleController@export')->middleware('api.admin')->name('articles.api.get');
    Route::post('/articles', 'ArticleController@createAPI')->middleware('api.admin')->name('articles.api.create');

    Route::delete('/articles/{id}', 'ArticleController@deleteAPI')->middleware('api.admin')->name('articles.api.delete');
    Route::put('/articles/{id}/{publish}', 'ArticleController@publishAPI')->middleware('api.admin')->name('articles.api.delete');
    Route::put('/articles/{id}', 'ArticleController@updateAPI')->middleware('api.admin')->name('articles.api.update');
});

