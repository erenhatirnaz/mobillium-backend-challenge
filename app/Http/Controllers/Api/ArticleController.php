<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Enums\ArticleStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function all(Request $request)
    {
        if (!$request->user()->can('viewAny', Article::class)) {
            return response()->json([
                'code' => 403,
                'message' => "This action is unauthorized! If you want export your articles, try '/article/export' endpoint.",
            ])->setStatusCode(403);
        }

        return response()->json(Article::all()->toArray())->setStatusCode(200);
    }

    public function export(Request $request)
    {
        return response()->json($request->user()->articles()->get()->toArray());
    }

    public function create(Request $request)
    {
        if (!$request->user()->can('create', Article::class)) {
            return response()->json([
                'code' => 403,
                'message' => "This action is unauthorized!"
            ])->setStatusCode(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            $errorOneLine = join(" ", $validator->errors()->all());
            return response()->json([
                'code' => 422,
                'message' => $errorOneLine
            ])->setStatusCode(422);
        }

        $publishedAt = Carbon::now();
        if ($request->input('published_at')) {
            $publishedAt = Carbon::createFromFormat('Y-m-d H:i', $request->input('published_at'));
        }

        $article = new Article();
        $article->user()->associate($request->user());
        $article->slug = Str::slug($request->input('title')) . "-" . rand(10000, 99999);
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->published_at = $publishedAt;
        $article->status = ""; // for trigger the Article models' status setter
        $article->save();

        return response()->json($article->toArray())->setStatusCode(201);
    }

    public function update(int $id, Request $request)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json([
                'code' => 404,
                'message' => "Not found!"
            ])->setStatusCode(404);
        }

        if (!$request->user()->can('update', $article)) {
            return response()->json([
                'code' => 403,
                'message' => "This action is unauthorized!"
            ])->setStatusCode(403);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            $errorOneLine = join(" ", $validator->errors()->all());
            return response()->json([
                'code' => 422,
                'message' => $errorOneLine
            ])->setStatusCode(422);
        }

        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->save();

        return response()->json($article->toArray())->setStatusCode(200);
    }

    public function publish(int $id, Request $request)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json([
                'code' => 404,
                'message' => "Not found!"
            ])->setStatusCode(404);
        }
        if (!$request->user()->can('update', $article)) {
            return response()->json([
                'code' => 403,
                'message' => "This action is unauthorized!"
            ])->setStatusCode(403);
        }

        if ($article->status == ArticleStatus::PUBLISHED) {
            return response()->json([
                'code' => 422,
                'message' => "This article is already published!",
            ])->setStatusCode(422);
        }

        $article->published_at = Carbon::now();
        $article->status = ""; // for trigger the Article models' status setter
        $article->save();

        return response()->json($article->toArray())->setStatusCode(200);
    }

    public function unpublish(int $id, Request $request)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json([
                'code' => 404,
                'message' => "Not found!"
            ])->setStatusCode(404);
        }
        if (!$request->user()->can('update', $article)) {
            return response()->json([
                'code' => 403,
                'message' => "This action is unauthorized!"
            ])->setStatusCode(403);
        }

        if ($article->status !== ArticleStatus::PUBLISHED) {
            return response()->json([
                'code' => 422,
                'message' => "This article is already unpublished!",
            ])->setStatusCode(422);
        }

        $article->published_at = null;
        $article->status = ""; // for trigger the Article models' status setter
        $article->save();

        return response()->json($article->toArray())->setStatusCode(200);
    }

    public function delete(int $id, Request $request)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json([
                'code' => 404,
                'message' => "Not found!"
            ])->setStatusCode(404);
        }
        if (!$request->user()->can('delete', $article)) {
            return response()->json([
                'code' => 403,
                'message' => "This action is unauthorized!"
            ])->setStatusCode(403);
        }

        $article->delete();
        return response("", 204);
    }
}
