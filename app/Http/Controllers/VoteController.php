<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article-id' => 'required|exists:articles,id',
            'rating' => 'required|integer|between:1,5',
        ]);
        if ($validator->fails()) {
            return response("400 Bad Request!", 400);
        }

        $vote = new Vote();
        $vote->article_id = $request->input('article-id');
        $vote->user_id = $request->user()->id;
        $vote->rating = $request->input('rating');
        $vote->save();

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vote_id' => 'required|exists:votes,id',
        ]);
        if ($validator->fails()) {
            return response("400 Bad Request!", 400);
        }

        $vote = Vote::find($request->input('vote_id'))->delete();

        return redirect()->back();
    }
}
