<?php

namespace App\Http\Controllers;

use App\Accountant;
use Illuminate\Http\Request;

class AccountantsController extends Controller
{
    public function add(Request $request){
        return Accountant::create([
            'image' => $request->file('image')->store('accountant_image'),
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

    public function update($id, Request $request){
        $accountant = Accountant::findOrFail($id);

        $accountant->image = $request->file('image')->store('accountant_image');
        $accountant->user_id = $request->get('user_id');
        $accountant->code = $request->get('code');
        $accountant->phone = $request->get('phone');
        $accountant->status = $request->get('status');

        $accountant->save();

        return json_encode($accountant);
    }

    public function delete($id){
        Accountant::destroy($id);

        return json_encode(true);
    }
}
