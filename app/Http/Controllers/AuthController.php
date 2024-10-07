<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields= $request->validate(
            [
                'name'=>'required|max:225',
                'email'=>'required|email|unique:users',
                'password'=>'required|confirmed'
            ]
            );
            $user=User::create($fields);
            $token=$user->createToken($request->name);

            return response()->json([
                'user' => $user,  // Retourner la variable $user
                'token' => $token->plainTextToken // Retourner la variable $token
            ], 201);
    }
    public function login(Request $request)
    {
        $request->validate(
        [
            'email'=>'required|email|exists:users',
            'password'=>'required|'
        ]
        );

        $user = User::where('email',$request->email)->first();
        if (!$user||!Hash::check($request->password,$user->password)){
            return [
                'message'=>'Incorret Credentiels'
            ];
        }
        $token=$user->createToken($user->name);

        return response()->json([
            'user' => $user,  // Retourner la variable $user
            'token' => $token->plainTextToken // Retourner la variable $token
        ], 201);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();


        return [
                'message'=>'You are Loged Out'
            ];
    }
}