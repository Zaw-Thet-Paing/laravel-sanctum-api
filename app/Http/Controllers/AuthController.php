<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register()
    {
        try{
            $fields = Validator::make(request()->all(), [
                'name'=> 'required|string',
                'email'=> 'required|string|unique:users,email',
                'password'=> 'required|string|confirmed'
            ]);

            if($fields->fails()){
                return response()->json([
                    'errors'=> $fields->errors(),
                    'status'=> 400
                ], 400);
            }

            $user = new User();
            $user->name = request('name');
            $user->email = request('email');
            $user->password = bcrypt(request('password'));
            $user->save();

            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user'=> $user,
                'token'=> $token
            ];

            return response()->json($response, 201);

        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ],500);
        }

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message'=> 'Logged out'
        ]);
    }

    public function login()
    {
        try{
            $fields = Validator::make(request()->all(), [
                'email'=> 'required|string',
                'password'=> 'required|string'
            ]);

            if($fields->fails()){
                return response()->json([
                    'errors'=> $fields->errors(),
                    'status'=> 400
                ], 400);
            }

            //check email
            $user = User::where('email', request('email'))->first();

            //check password
            if(!$user || !Hash::check(request('password'), $user->password)){
                return response()->json([
                    'message'=> 'Bad credentials'
                ], 401);
            }

            $user->email = request('email');
            $user->password = bcrypt(request('password'));
            $user->save();

            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user'=> $user,
                'token'=> $token
            ];

            return response()->json($response, 201);

        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ],500);
        }

    }
}
