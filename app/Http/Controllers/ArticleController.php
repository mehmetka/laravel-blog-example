<?php

namespace App\Http\Controllers;

use App\Article;
use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function articlesView(Request $request): \Illuminate\Contracts\Support\Renderable
    {
        $user = $request->user();

        if ($user['role'] == 3) {
            $articles = Article::all();
        } else {
            $articles = Article::where('user_id', $user->id)->get();
        }

        return view('articles/index', compact('articles'));
    }

    /**
     * Display an Article by ID
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function articleView(Request $request, $id): \Illuminate\Contracts\Support\Renderable
    {
        $article = Article::join('users', 'articles.user_id', '=', 'users.id')->find($id);
        $article->increment('view_count');

        $previousID = Article::where('id', '<', $article->id)->max('id');
        $article->previous = $previousID ? $previousID : Article::first()->id;
        $nextID = Article::where('id', '>', $article->id)->min('id');
        $article->next = $nextID ? $nextID : $id;

        $currentUserRate = Rate::where([
            ['user_id', '=', Auth::id()],
            ['article_id', '=', $id]
        ])->first();

        if ($currentUserRate) {
            $article->showRating = false;
            $article->yourRate = $currentUserRate->rate;

        } else {
            $article->showRating = true;
        }

        $rates = Rate::where('article_id', '=', $id)->orderByDesc('id')->get()->toArray();
        $article->rateAverage = calculateRateAverage($rates);

        return view('articles/show', compact('article'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request): \Illuminate\Http\Response
    {
        $user = $request->user();

        if ($user['role'] == 3) {
            $articles = Article::all();
        } else {
            $articles = Article::where('user_id', $user->id)->get();
        }

        $data = ['data' => $articles, 'count' => count($articles), 'responseCode' => 200];
        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createAPI(Request $request): \Illuminate\Http\Response
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish' => 'required',

        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = $request->user();
        $request['user_id'] = $user['id'];

        Article::create($request->toArray());

        $response = ['message' => 'success'];
        return response($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteAPI(Request $request, $id): \Illuminate\Http\Response
    {
        $user = $request->user();

        if ($user['role'] != 3) {
            $article = Article::where('id', $id)->get();

            if ($article['user_id'] == $user['id']) {
                Article::find($id)->delete();
                Rate::find($id)->delete();
            }

        } else {
            Article::find($id)->delete();
            Rate::find($id)->delete();
        }

        $response = ['message' => 'Deleted successfully'];
        return response($response, 200);
    }

    /**
     * @param int $articleID
     * @param string $publishStatus
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function publishAPI(int $articleID, string $publishStatus)
    {
        if ($publishStatus == 'publish') {
            $publish = 1;
            $message = 'published';
        } else {
            $publish = 0;
            $message = 'unpublished';
        }

        Article::where('id', $articleID)->update(['publish' => $publish]);

        $response = ['message' => "Article $message successfully"];
        return response($response, 200);
    }

    /**
     * @param Request $request
     * @param int $articleID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateAPI(Request $request, int $articleID)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        Article::where('id', $articleID)->update(['title' => $request['title'], 'content' => $request['content']]);

        $response = ['message' => "Article updated successfully"];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'publish' => 'required',

        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = $request->user();
        $request['user_id'] = $user['id'];
        $request['publish'] = $request['publish'] === 'on' ? 1 : 0;

        Article::create($request->toArray());

        return redirect()->route('articles.index')->with('success', 'Article created successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     */
    public function createView(Request $request)
    {
        return view('articles/create');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteView(int $id): \Illuminate\Http\RedirectResponse
    {
        Article::find($id)->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish(int $articleID, string $publishStatus): \Illuminate\Http\RedirectResponse
    {
        if ($publishStatus == 'publish') {
            $publish = 1;
            $message = 'published';
        } else {
            $publish = 0;
            $message = 'unpublished';
        }

        Article::where('id', $articleID)->update(['publish' => $publish]);

        return redirect()->route('articles.index')->with('success', "Article $message successfully");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editView(Request $request, int $id): \Illuminate\Contracts\Support\Renderable
    {
        $article = Article::find($id);

        return view('articles/edit', compact('article'));
    }

    public function update(Request $request, int $articleID): \Illuminate\Http\RedirectResponse
    {
        Article::where('id', $articleID)->update(['title' => $request['title'], 'content' => $request['content']]);

        return redirect()->route('articles.index')->with('success', "Article updated successfully");
    }

    public function rate(Request $request, int $articleID): \Illuminate\Http\RedirectResponse
    {
        Rate::create(['rate' => $request->rate, 'user_id' => Auth::id(), 'article_id' => $articleID]);

        return redirect()->route('articles.show', $articleID)->with('success', "Article rated successfully");
    }
}
