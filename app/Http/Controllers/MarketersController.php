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

    public function update($id, Request $request){
        $marketer = Marketer::findOrFail($id);

        $marketer->user_id = $request->get('user_id');
        $marketer->code = $request->get('code');
        $marketer->status = $request->get('status');
        $marketer->phone = $request->get('phone');

        $marketer->save();

        return json_encode($marketer);
    }

    public function delete($id){
        Marketer::destroy($id);

        return json_encode(true);
    }
}
