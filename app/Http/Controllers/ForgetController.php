<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetRequest;
use App\Mail\ForgetMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgetController extends Controller
{
    public function forgetPassword(ForgetRequest $request){ 

        $email = $request->email;

        if(User::where('email', $email)->doesntExist()){
            return response([
                'message' => 'User not exist'
            ]);
        }

        $token = rand(10, 100000);

        try{

            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::to($email)->send(new ForgetMail($token));

            return response([
                'message' => 'Reaset password mail send to your Email'
            ], 200);

            
        }catch(Exception $ex){
            return response([
                'message' => $ex->getMessage()
            ], 400);
        }
    }
}
