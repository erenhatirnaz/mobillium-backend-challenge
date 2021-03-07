<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Enums\ArticleStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        return view('article.view', compact('article'));
    }

    public function list(Request $request)
    {
        if ($request->user()->can('viewAny', Article::class)) {
            $articles = Article::orderBy('updated_at', 'DESC')->get();
            $panelName = "Admin Panel";
        } else {
            $articles = $request->user()->articles;
            $panelName = "Writer Panel";
        }

        return view('article.list', compact('articles', 'panelName'));
    }

    public function createPage(Request $request)
    {
        $this->authorize('create', Article::class);

        return view('article.create');
    }

    public function create(Request $request)
    {
        $this->authorize('create', Article::class);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return response("400 Bad Request!", 400);
        }

        $publishedAt = null;
        if ($request->input('publish')) {
            $publishedAt = Carbon::now();
        } elseif ($request->input('scheduled-date') && $request->input('scheduled-time')) {
            $scheduledDateTime = "{$request->input('scheduled-date')} {$request->input('scheduled-time')}";
            $publishedAt = Carbon::createFromFormat('Y-m-d H:i', $scheduledDateTime);
        }

        $article = new Article();
        $article->user()->associate($request->user());
        $article->slug = Str::slug($request->input('title')) . "-" . rand(10000, 99999);
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->published_at = $publishedAt;
        $article->status = ""; // for trigger the Article models' status setter
        $article->save();

        return redirect()->route($request->user()->panelDashboardRouteName());
    }

    public function editPage(int $id, Request $request)
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);

        return view('article.create', compact('article'));
    }

    public function edit(int $id, Request $request)
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return response("400 Bad Request!", 400);
        }

        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->save();

        return redirect()->route($request->user()->panelDashboardRouteName());
    }

    public function publish(int $id, Request $request)
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);

        if ($article->status == ArticleStatus::PUBLISHED) {
            return response("400 Bad Request!", 400);
        }

        $article->published_at = Carbon::now();
        $article->status = ""; // for trigger the Article models' status setter
        $article->save();

        return redirect()->route($request->user()->panelDashboardRouteName());
    }

    public function unpublish(int $id, Request $request)
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);

        if ($article->status !== ArticleStatus::PUBLISHED) {
            return response("400 Bad Request!", 400);
        }

        $article->published_at = null;
        $article->status = ""; // for trigger the Article models' status setter
        $article->save();

        return redirect()->route($request->user()->panelDashboardRouteName());
    }

    public function delete(int $id, Request $request)
    {
        $article = Article::findOrFail($id);
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route($request->user()->panelDashboardRouteName());
    }
}
