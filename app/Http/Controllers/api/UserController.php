<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    

    public function login(){

        $remember = (Input::has('remember')) ? true : false;
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')],$remember)){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function checkToken() {
        echo 232432;
        die;
    }

}
