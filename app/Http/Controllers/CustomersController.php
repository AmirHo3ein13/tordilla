<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CustomersController extends Controller
{
    public function add(Request $request){
        return Customer::create([
            'latitude' => $request->has('latitude') ? $request->get('latitude') : null,
            'longitude' => $request->has('longitude') ? $request->get('longitude') : null,
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
        $customer['latitude'] = $request->get('latitude');
        $customer['longitude'] = $request->get('longitude');
        return json_encode(true);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function load(Request $request){
        $csv = Reader::createFromPath(public_path('test-Moshtariyan.csv'));
        $arr = array();
        foreach ($csv as $item) {
            array_push($arr, $item);
        }
        foreach (array_slice($arr, 3) as $row){
            Customer::create([
                'code' => $row[0],
                'store_name' => $row[1],
                'address' => $row[2],
                'phone' => $row[3]
            ]);
        }
        return json_encode(true);
    }

    public function update($id, Request $request){
        $customer = Customer::findOrFail($id);

        $customer->latitude = $request->has('latitude') ? $request->get('latitude') : null;
        $customer->longitude = $request->has('longitude') ? $request->get('longitude') : null;
        $customer->code = $request->get('code');
        $customer->store_name = $request->get('store_name');
        $customer->city = $request->get('city');
        $customer->area = $request->get('area');
        $customer->address = $request->get('address');
        $customer->phone = $request->get('phone');

        $customer->save();

        return json_encode($customer);
    }

    public function delete($id){
        Customer::destroy($id);

        return json_encode(true);
    }

    public function search(Request $request){
        $customers = DB::table('customers');
        if ($request->has('store_name')){
            $customers = $customers->where('store_name' , 'like', '%'.$request->get('store_name').'%');
        }
        if ($request->has('code')){
            $customers = $customers->where('code' , 'like', '%'.$request->get('code').'%');
        }
        if ($request->has('phone')){
            $customers = $customers->where('phone' , 'like', '%'.$request->get('phone').'%');
        }
        if ($request->has('city')){
            $customers = $customers->where('city' , 'like', '%'.$request->get('city').'%');
        }
        if ($request->has('longitude')){
            $customers = $customers->whereBetween('longitude' , [$request->get('longitude') - 0.002, $request->get('longitude') + 0.002]);
        }
        if ($request->has('latitude')){
            $customers = $customers->whereBetween('latitude' , [$request->get('latitude') - 0.002, $request->get('latitude') + 0.002]);
        }
        if ($request->has('index_from') and $request->has('index_to')){
            $customers = $customers->offset($request->get('index_from'))
                ->limit($request->get('index_to') - $request->get('index_from'));
        }
        return json_encode($customers->get());
    }
}
