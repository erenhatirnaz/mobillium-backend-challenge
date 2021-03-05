<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Article::published()->orderBy('created_at', 'desc')->get();

        return view('home', [
            'posts' => $posts
        ]);
    }
}
