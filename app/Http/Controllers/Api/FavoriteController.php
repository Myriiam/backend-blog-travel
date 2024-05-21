<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Article;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function getMyFavoritesArticles()
    {   
        $authUser = Auth::user();
        
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized !'], 403);
        }

        $user_id = Auth::user()->id;
      
        try {
            $user = User::findOrFail($user_id);
            $myFavoriteArticles = $user->favorites()->with('article')->get();

            return response()->json([
               // 'user' => $user,
                'myLikes' => $myFavoriteArticles,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error when retrieving your favorites articles : ' . $e->getMessage(),
            ], 500);
        }
    }


    public function likeArticle($id)  //article's id
    {   
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized !'], 403);
        }

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $article = Article::findOrFail($id);
        $article_id = $article->id;

        if ($user_id == $article->user_id) {
            return response()->json(['message' => 'You cannot like your own article !'], 403);
        }
    
        try {
            Favorite::create([
                'user_id' => $user_id, 
                'article_id' => $article_id,
           ]);

           return response()->json([
            'message' => 'Article added as favorite',
            'article' => $article,
            'userAuth' => $user,
        ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in adding this article as favorite : ' . $e->getMessage(),
            ], 500);
        }
    }


    public function dislikeArticle($id)
    {   
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized !'], 403);
        }

        $user_id = Auth::user()->id;
        
        try {
            // Find the favorite entry for the given article and user
            $favorite = Favorite::where('article_id', $id)->where('user_id', $user_id)->first();

            //Check if the auth user is the owner of the like
            if (!$favorite) {
                return response()->json(['message' => 'This is not one of your likes, so you cannot dislike this article!'], 403);
            }

            $favorite->delete();

            return response()->json([
                'message' => 'Article removed from your favorites',
                'favorite' => $favorite,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in deleting this article from your favorites : ' . $e->getMessage(),
            ], 500);
        }
       

        return response()->json([
            'message' => 'dislike or not ?',
            'favorite' => $favorite,
          // 'article'=> $article,
        ]);
        /* if ($user_id != $favorite->user_id) {
            return response()->json(['message' => 'This is not one of your like, so you cannot dislike this article !'], 403);
        } */
    }
}
