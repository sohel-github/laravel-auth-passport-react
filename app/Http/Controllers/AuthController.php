<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        try{
            if(Auth::attempt($request->only('email', 'password'))){
                $user = Auth::user();
                $token = $user->createToken('app')->accessToken;

                return response([
                    'message' => 'Successfully Login',
                    'token' => $token,
                    'user' => $user
                ], 200);
            }
        }catch(Exception $ex){
            return response([
                'message' => $ex->getMessage()
            ], 400);
        }

        return response([
            'message' => 'Invalid email or password'
        ], 401);
    }

    public function register(Request $request){

        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:users,email|min:5|max:55',
            'password' => 'required|min:5|confirmed'
        ]);

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('app')->accessToken;

            return response([
                'message' => 'Registration successfully done',
                'token' => $token,
                'user' => $user
            ], 200);

        }catch(Exception $ex){
            return response([
                'message' => $ex->getMessage()
            ], 400);
        }

        return response([
            'message' => 'Test'
        ], 401);
    }
}
