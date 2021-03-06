<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // In order for articles to be cached, they had to be made into a single query in this way.
        $posts = Article::select(
            'users.full_name as author_full_name',
            'articles.id',
            'articles.slug',
            'articles.title',
            'articles.content',
            'articles.published_at',
        )->join('users', 'articles.user_id', '=', 'users.id')
         ->published()
         ->get();

        return view('home', [
            'posts' => $posts
        ]);
    }
}
