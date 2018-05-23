<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use League\Csv\Reader;

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
                'role_id' => Auth::user()['role'],
                'id' => Auth::user()['id'],
                'name' => Auth::user()['name'],
                'email' => Auth::user()['email'],
                'first_last_name' => Auth::user()['first_last_name'],
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
            'phone' => $request->get('phone'),
            'status' => $request->has('status') ? $request->get('status') : true,
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
    public function load(Request $request){
        $csv = Reader::createFromPath(public_path('test-list-foroosh.csv'));
        $arr = array();
        foreach ($csv as $item) {
            array_push($arr, array_slice($item, 0, 6));
        }
        $roles = [
            'مدیر فروش' => 2,
            'سرپرست فروش' => 3,
            'بازاریاب' => 4,
            'مدیر سیستم' => 1,
        ];
        foreach (array_slice($arr, 3) as $row){
            User::create([
                'code' => $row[0],
                'first_last_name' => $row[1],
                'password' => bcrypt($row[0]),
                'name' => $row[3],
                'role' => $roles[$row[2]],
            ]);
        }
        return json_encode(true);
    }

    public function get($id = -1){
        if ($id == -1){
            return User::all();
        }
        else
            return User::findOrFail($id);
    }

    public function remove($id){
        User::destroy($id);

        return true;
    }

    public function update($id = -1, Request $request){
        $user = ($id != -1) ? User::findOrFail($id) : Auth::user();

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        if ($request->has('password'))
            $user->password = bcrypt($request->get('password'));
        $user->role = $request->get('role');
        $user->status = $request->get('status');
        $user->status = $request->get('phone');
        $user->code = $request->get('code');
        $user->first_last_name = $request->get('first_last_name');

        $user->save();
    }
}