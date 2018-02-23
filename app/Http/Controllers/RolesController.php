<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * add role
     *
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request){
        return Role::create(['role' => $request->get('name')]);
    }

    public function get($id = -1){
        if ($id == -1)
            return json_encode(Role::all());
        else
            return json_encode(Role::findOrFail($id));
    }
}
