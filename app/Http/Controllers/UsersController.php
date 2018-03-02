<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

//use Maatwebsite\Excel\Excel;

//class UserListExport extends \Maatwebsite\Excel\Files\NewExcelFile {
//
//    public function getFilename()
//    {
//        return 'filename';
//    }
//}
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
            'name' => $request->get('name'),
            'password' => $request->get('password')
        ])){
            Auth::loginUsingId(User::where('name', '=', $request->get('name'))->first()['id'],true);
            return json_encode([
                'code' => Auth::user()['code'],
                'role' => Role::findOrFail(Auth::user()['role'])->role,
            ]);
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

    /**
     * @param Request $request
     * @return string
     */
//    public function load(Request $request){
//
//    }
}