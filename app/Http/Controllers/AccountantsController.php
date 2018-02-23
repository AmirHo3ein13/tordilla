<?php

namespace App\Http\Controllers;

use App\Accountant;
use Illuminate\Http\Request;

class AccountantsController extends Controller
{
    public function add(Request $request){
        return Accountant::create([
            'image' => $request->get('image'),
            'user_id' => $request->get('user_id'),
            'code' => $request->get('code'),
            'phone' => $request->get('phone'),
            'status' => $request->get('status'),
        ]);
    }
    public function get($id = -1){
        if ($id == -1)
            return json_encode(Accountant::all());
        else
            return json_encode(Accountant::findOrFail($id));
    }
}
