<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Models\User;
use App\Models\Comment;


class CommentController extends Controller
{
    public function addComment(Request $request, $id)
    {   
        $user_id = Auth::user()->id;
        $article = Article::find($id);
        $article_id = $article->id; //Id of the article
        $authorInfo = User::find($user_id); //author of the article

        // Check if the article exists
        if (!$article) {
            return response()->json(['message' => 'Article not found, impossible to comment'], 404);
        }

        // Check if the authenticated user is connected, if not he cannot send a comment
        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            Comment::create([
                'content' => $request->input('content'),
                 'user_id' => $user_id, 
                 'article_id' => $article_id,
            ]);

            return response()->json([
                'message' => 'You have successfully sent your comment !',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in adding comment : ' . $e->getMessage(),
            ], 500);
        }
    }
}
