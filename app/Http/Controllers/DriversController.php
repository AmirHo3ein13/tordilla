<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    public function add(Request $request){
        return Driver::create([
            'name' => $request->get('name'),
            'car' => $request->get('car'),
            'tag' => $request->get('tag'),
            'status' => $request->get('status'),
            'capacity' => $request->get('capacity'),
            'phone_number' => $request->get('phone_number'),
            'even' => $request->get('even'),
        ]);
    }
    public function get($id = -1){
        if ($id == -1)
            return json_encode(Driver::all());
        else
            return json_encode(Driver::findOrFail($id));
    }

    public function delete($id){
        Driver::destroy($id);
        return json_encode(true);
    }

    public function update($id, Request $request){
        $driver = Driver::findOrFail($id);
        $driver->name = $request->get('name');
        $driver->car = $request->get('car');
        $driver->tag = $request->get('tag');
        $driver->status = $request->get('status');
        $driver->capacity = $request->get('capacity');
        $driver->phone_number = $request->get('phone_number');
        $driver->even = $request->get('even');
        $driver->save();
        return json_encode($driver);
    }
}
