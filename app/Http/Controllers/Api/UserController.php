<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
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

    public function getAllUserInfo() {
        $user = Auth::user();
      //  var_dump($user);

         return response()->json([
            'message' => 'success',
            'user'  => $user,
        ]); 
    }

    public function editProfile(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'avatar' => 'image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
    }

    public function deleteAccount($id) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

       /*  $article = Article::findOrFail($id);
        $article->delete();
        $article->categories()->detach(); */
    }
}
