<?php

namespace App\Http\Controllers;

use App\Marketer;
use Illuminate\Http\Request;

class MarketersController extends Controller
{
    public function add(Request $request){
        return Marketer::create([
            'user_id' => $request->get('user_id'),
            'code' => $request->get('code'),
            'status' => $request->get('status'),
            'phone' => $request->get('phone'),
        ]);
    }
    public function get($id = -1){
        if ($id == -1)
            return json_encode(Marketer::all());
        else
            return json_encode(Marketer::findOrFail($id));
    }
}
