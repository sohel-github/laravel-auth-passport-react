<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request){
        
        $token = $request->token;
        $email = $request->email;
        $password = Hash::make($request->password);

        $pinCheck = DB::table('password_resets')->where('token', $token)->first();
        $emailCheck = DB::table('password_resets')->where('email', $email)->first();
        
        if(!$pinCheck){
            return response([
                'message' => 'Pin is not correct'
            ], 401);
        }
        if(!$emailCheck){
            return response([
                'message' => 'Email is not correct'
            ]);
        }

        DB::table('users')->where('password', $password)->update(['password' => $password]);
        DB::table('password_resets')->where('email', $email)->delete();

        return response([
            'message' => 'Password has been changed'
        ]);

    }
}
