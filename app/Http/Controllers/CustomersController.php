<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function add(Request $request){
        $location = $request->has('location') ? $request->get('location') : null;
        return Customer::create([
            'location' => $location,
            'code' => $request->get('code'),
            'store_name' => $request->get('store_name'),
            'city' => $request->get('city'),
            'area' => $request->get('area'),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
        ]);
    }
    public function get($id = -1){
        if ($id == -1)
            return json_encode(Customer::all());
        else
            return json_encode(Customer::findOrFail($id));
    }
    public function add_location(Request $request){
        $customer = Customer::findOrFail($request->get('id'));
        $customer['location'] = $request->get('location');
        return json_encode(true);
    }
}
