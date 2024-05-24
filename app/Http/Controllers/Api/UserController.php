<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Article;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        var_dump('ok register');
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|unique:users',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
        return response()->json($validator->errors());
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data'          => $user,
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        var_dump('ok login');
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|max:255',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
        return response()->json($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        //If valisation fails
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'User not found/Invalid credentials !'
            ], 401);
        }

        //If validation is successfull then token is generated
      //  $user   = User::where('email', $request->email)->firstOrFail();
        $user = Auth()::user();
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Login success',
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }

    public function editProfile(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if ($user_id != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes',
            'avatar' => 'sometimes|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            if ($request->has('name')) {
                $user->name = $request->input('name');
            }
            if ($request->has('description')) {
                $user->description = $request->input('description');
            }
    
            if ($request->hasFile('avatar')) {
                $cloudinaryImage = $request->file('avatar')->storeOnCloudinary('user-profile');
                $user->avatar = $cloudinaryImage->getSecurePath();
               // $article->avatar_public_id = $cloudinaryImage->getPublicId();
            }
    
            // Save the article
            $user->save();

            return response()->json([
                'message' => 'Your profile has been successfully updated !',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error in updating your profile : ' . $e->getMessage()], 500);
        }
    }

    public function deleteAccount() {
        $user_id = Auth::user()->id;

        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::find($user_id);

        try {
            if ($user->articles()->exists()) {
                // Retrieve user's articles
                $articles = $user->articles;
    
                foreach ($articles as $article) {
                    // Delete associated images and categories
                    $article->categories()->detach();
                    $article->images()->delete();
                    // Delete article
                    $article->delete();
                }
            }
    
            // Delete user account
            $user->delete();
    
            return response()->json([
                'message' => 'Your account has been successfully deleted !',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in deleting your account: ' . $e->getMessage(),
            ], 500);
        }
    }
}
