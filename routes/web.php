<?php

use App\Article;
use App\Rate;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ArticleController;

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
    $articles = Article::join('users', 'articles.user_id', '=', 'users.id')->where('articles.publish', 1)->get();

    foreach ($articles as $article) {
        $article->content = substr($article->content, 0, 100);
        $article->content = $article->content . '...';
        $rates = Rate::where('article_id', '=', $article->id)->orderByDesc('id')->get()->toArray();
        $article->rateAverage = calculateRateAverage($rates);
    }

    return view('welcome', compact('articles'));
})->name('main');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/articles', [ArticleController::class, 'articlesView'])->name('articles.index');
    Route::delete('/articles/{id}', [ArticleController::class, 'deleteView'])->name('articles.delete');

    Route::get('/articles/create', [ArticleController::class, 'createView'])->name('articles.create');
    Route::post('/articles/create', [ArticleController::class, 'insert'])->name('articles.insert');

    Route::put('/articles/{id}/rate', [ArticleController::class, 'rate'])->name('articles.rate');

    Route::get('/articles/edit/{id}', [ArticleController::class, 'editView'])->name('articles.edit');
    Route::put('/articles/edit/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::put('/articles/{id}/{publish}', [ArticleController::class, 'publish'])->name('articles.publish');

});

Route::get('/articles/{id}', [ArticleController::class, 'articleView'])->name('articles.show');
