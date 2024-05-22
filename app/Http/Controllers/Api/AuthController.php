<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use JWTAuth\JWT;

use App\Models\User;


class AuthController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(Request $request)
    {
        //var_dump('ok register');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
    
            return response()->json([
                'user' => $user,
                'message' => 'User created successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error when register : ' . $e->getMessage()], 500);
        }  
    }



    public function login(Request $request)
    {
       // var_dump('ok login');
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|max:255',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $credentials = $request->only('email', 'password');
            $token = JWTAuth::attempt($credentials);
            //If valisation fails
            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized/Invalid credentials !'
                ], 401);
            }
    
            //If validation is successfull then token is generated
            $user = Auth::user();
    
            return response()->json([
                'user' => $user,
                'message' => 'Login success',
               // 'token' => $this->refresh($token),  //or 'refresh_token' => $refreshToken,
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl')
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error when trying to login : ' . $e->getMessage()], 500);
        }  
    }



    public function logout()
    {   
        try {
            Auth::logout();
            return response()->json([
                'message' => 'Successfully logged out',
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error when trying to logout : ' . $e->getMessage()], 500);
        }  
    }


    
  /*   public function refresh($token)
    {   
       // $newToken = Auth::setToken($request->refresh_token)->refresh();

       /*  return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,    //time to live" (TTL) for the token in seconds
            ]
        ]); 

         $response = [
            'user' => Auth::user(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];

        // Store the token in localStorage
        return response()->json($response)->cookie('token', $token, auth()->factory()->getTTL() * 60); 
        

        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    } 
    */

/*     public function refresh(Request $request)
    {   
        $refreshToken = $request->bearerToken();

        if (!$refreshToken) {
            return response()->json(['error' => 'Refresh token not provided'], 401);
        }

        try {
            $decodedToken = Auth::payload($refreshToken);   //parsing the jwt and extracting all the info (the user id, the expiration time ...)
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }
        $user = User::find($decodedToken->get('sub'));  //to find the corresponding user record in the database. sub means subject (it is a function in the payload)
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Generate a new access token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60 // Assuming I have set the token lifetime in your config
        ]);
    }    */
}
