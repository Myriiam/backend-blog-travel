<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;


class ArticleController extends Controller
{   
    public function getCategories() {
        $categories = Category::all();
        return response()->json([
            'categories' =>  $categories,
        ]);
    }

    public function addArticle(Request $request)
    {
        //var_dump(Auth::user()->id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'continent' => 'required|string',
            'country' => 'required|string',
            'main_picture' => 'required|image',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
    
        try {
            $user_id = Auth::user()->id;

            $article = new Article();
            $title = $request->input('title');
            $content = $request->input('content');
            $continent = $request->input('continent');
            $country = $request->input('country');

        if ($request->hasFile('main_picture')) {
                
                $path = $request->file('main_picture')->store('pictures');
                $article->main_picture = $path;
        }
            //Save article's data in the database
            $article->user_id = $user_id;
            $article->title = $title;
            $article->content = $content;
            $article->continent = $continent;
            $article->country = $country;
            $article->save();
            //To link one or more tan one categories to the article
            $article->categories()->attach($request->categories);
            
            return response()->json([
                'message' => 'Article created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in adding article: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showAll()
    {   
        try {
             //$articles = Article::all();
            $articles = Article::with(['categories', 'user'])
            ->get(['id', 'title', 'content', 'user_id', 'continent', 'country', 'main_picture']);

            return response()->json([
                'message' => 'getting all articles, success !',
                'articles' => $articles,
                //'categories' => $categories,
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error in retrieving all articles !! : ' . $e->getMessage(),
            ], 500);
        }
       
    }

    public function showMyArticles()
    {
        $user = Auth::user();
        //var_dump($user->id);
        $articles = Article::with(['categories', 'user'])
            ->where('user_id', '=', $user->id)
            ->get(['id', 'title', 'content', 'user_id', 'continent', 'country', 'main_picture']);
        if (!$articles->isEmpty()) {
            return response()->json([
                'message' => 'Here my articles, SUCCESS !',
                'articles' => $articles,
            ]);
        } else {
            return response()->json([
                'message' => 'You have not added any articles yet !',
            ]);
        }
    }

    public function showArticle($id)
    {
        try {
            $article = Article::findOrFail($id);
            //var_dump($article->title);
            $categories = $article->categories;
            $user_id = $article->user_id;
            $author = User::find($user_id); //author of the article

            return response()->json([
                'message' => 'this is the article you have clicked on !',
                'article' => $article,
                //'categories' => $categories,
                'author' => $author,
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error in retrieving this article: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function editArticle($id)
    {
      //
  
    }
    
    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        $article->categories()->detach();
        //delete also related images and comments

       /*  $images = $article->images;
        // Iterate through each image and delete the file from storage
        foreach ($images as $image) {
            // Assuming the image 'path' field contains the file path
            Storage::delete($image->path);
        }
        // Delete all images related to the article from the database
        $article->images()->delete(); */
    }

}
