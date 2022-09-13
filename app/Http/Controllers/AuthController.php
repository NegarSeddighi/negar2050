<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        auth()->login($user);
        // dd(session()->all(), auth()->user());

        $token = auth()->user()->createToken('authToken')->plainTextToken;

        return response()->json([
             'access_token' => $token,
             'token_type' => 'Bearer',
             'user' => UserResource::make(auth()->user())
            ], Response::HTTP_OK);
             
    }

    public function login(LoginRequest $request) {

        $credentials = $request->validated();
        // dd($credentials);

        if (auth()->attempt($credentials)) {
            
            $token = auth()->user()->createToken('authToken')->plainTextToken;
     
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => UserResource::make(auth()->user())
            ], Response::HTTP_OK);
                           
        }
        return response()->json([
            'message' => 'The provided credentials do not match our records.'
          ], Response::HTTP_UNAUTHORIZED);

        

    }

}
