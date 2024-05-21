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
use App\Models\Comment;


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
            'main_picture' => 'required|image|max:10240',
            'images.*' => 'required|image|max:10240',
            'categories' => 'required|exists:categories,id',
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

            //var_dump($request->file('main_picture'));

            //Upload main picture for the article
            $cloudinaryImage = $request->file('main_picture')->storeOnCloudinary('main-picture');
            $url = $cloudinaryImage->getSecurePath();
            $publicId = $cloudinaryImage->getPublicId();

            //Save article's data in the database
            $article->user_id = $user_id;
            $article->title = $title;
            $article->content = $content;
            $article->continent = $continent;
            $article->country = $country;
            $article->image_url = $url;
            $article->image_public_id = $publicId;

            $article->save();
            //To link one or more than one categories to the article
            $article->categories()->attach($request->categories);

             //Upload other images for the article
             $filesImg = $request->file('images');
            //var_dump($filesImg);
            foreach($filesImg as $fileImg) {
                $fileCloudy = $fileImg->storeOnCloudinary('article-images');
                $urlImg = $fileCloudy->getSecurePath();
                $publicIdImg = $fileCloudy->getPublicId(); 
                $article->images()->create([
                        'image_url' => $urlImg,
                        'image_public_id' => $publicIdImg,
                    ]);
            } 
            
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
            $articles = Article::with(['categories', 'user', 'images', 'comments'])
            ->get();

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
        $articles = Article::with(['categories', 'user', 'images', 'comments'])
            ->where('user_id', '=', $user->id)
            ->get();
        if (!$articles->isEmpty()) {
            return response()->json([
                'message' => 'Here my articles, SUCCESS !',
                'articles' => $articles,
            ]);
        } else {
            return response()->json([
                'message' => 'You have not added any articles yet !',
                'user' => $user,
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
            $images = $article->images;
            $comments = $article->comments;
           
            return response()->json([
                'message' => 'this is the article you have clicked on !',
                'article' => $article,
               //'categories' => $categories,
               // 'images' => $images,
                'author' => $author,
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error in retrieving this article: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateArticle(Request $request, $id)
{
    // Get the authenticated user's ID
    $user_id = Auth::user()->id;
    $article = Article::find($id);
    
    // Check if the article exists
    if (!$article) {
        return response()->json(['message' => 'Article not found'], 404);
    }
    
    // Check if the authenticated user is the owner of the article
    if ($user_id != $article->user_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
    // Validate the request
    $validator = Validator::make($request->all(), [
        'title' => 'sometimes|string|max:255',
        'content' => 'sometimes|string',
        'continent' => 'sometimes|string',
        'country' => 'sometimes|string',
        'main_picture' => 'sometimes|image|max:10240', // Max 10MB size
        'categories' => 'sometimes|exists:categories,id',
        'images.*' => 'sometimes|image|max:10240' // Max 10MB size per image
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    try {
        // Update the article's fields if they exist in the request
        if ($request->input('title')) {
            $article->title = $request->input('title');
        }
        if ($request->has('content')) {
            $article->content = $request->input('content');
        }
        if ($request->has('continent')) {
            $article->continent = $request->input('continent');
        }
        if ($request->has('country')) {
            $article->country = $request->input('country');
        }

        // Handle main picture upload if present
        if ($request->hasFile('main_picture')) {
            $cloudinaryImage = $request->file('main_picture')->storeOnCloudinary('main-picture');
            $article->image_url = $cloudinaryImage->getSecurePath();
            $article->image_public_id = $cloudinaryImage->getPublicId();
        }

        // Save the article
        $article->save();

        // Sync categories if present
        if ($request->has('categories')) {
            $article->categories()->sync($request->input('categories'));
        }

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $filesImg = $request->file('images');
            foreach ($filesImg as $fileImg) {
                $fileCloudy = $fileImg->storeOnCloudinary('article-images');
                $article->images()->create([
                    'image_url' => $fileCloudy->getSecurePath(),
                    'image_public_id' => $fileCloudy->getPublicId()
                ]);
            }
            return response()->json(['message' => 'ok for image'], 200);

        }

        return response()->json([
            'message' => 'Article updated successfully',
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error in updating the article: ' . $e->getMessage()], 500);
    }
}
    
    public function deleteArticle($id)
    {   
        //TODO : only owner of the article can delete the message
        $article = Article::findOrFail($id);
        try {
            $article->delete();
            $article->categories()->detach();
            $article->images()->delete();
            $article->comments()->delete();
            return response()->json([
                'message' => 'Article deleted successfully',
                'article' => $article,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error in deleting the article: ' . $e->getMessage()], 500);
        }
    }

}
