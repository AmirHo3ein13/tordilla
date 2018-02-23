<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    use AuthenticatesUsers;
    /**
     * sign up user
     *
     * @param  Request $request
     * @return boolean
     */
    public function sign_in(Request $request){
        if (Auth::attempt([
            'id' => $request->get('id'),
            'password' => $request->get('password')
        ])){
            Auth::loginUsingId($request->get('id'),true);
            return json_encode(true);
        }
        else{
            return json_encode(false);
        }
    }

    /**
     * register user
     *
     * @param Request $request
     * @return string
     */
    public function register(Request $request){
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role'),
        ]);
        return json_encode(true);
    }

    public function sign_out(){
        Auth::logout();
        return 'logged out';
    }
}
