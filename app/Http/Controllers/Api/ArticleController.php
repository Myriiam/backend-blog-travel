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
            'main_picture' => 'required',
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
          /*   $filesImg = $request->file('images');
            //var_dump($filesImg);
            foreach($filesImg as $fileImg) {
                $fileCloudy = $fileImg->storeOnCloudinary('article-images');
                $urlImg = $fileCloudy->getSecurePath();
                $publicIdImg = $fileCloudy->getPublicId(); 
                $article->images()->create([
                        'image_url' => $urlImg,
                        'image_public_id' => $publicIdImg,
                    ]);
            } */
            
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
            $articles = Article::with(['categories', 'user', 'images'])
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
        $articles = Article::with(['categories', 'user', 'images'])
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
