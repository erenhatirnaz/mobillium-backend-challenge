<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

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
}
