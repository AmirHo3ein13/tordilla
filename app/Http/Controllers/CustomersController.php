<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Rap2hpoutre\FastExcel\FastExcel;

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
            'zone' => $request->get('zone'),
            'phone' => $request->get('phone'),
            'traffic_role' => $request->has('traffic_role') ? $request->get('traffic_role') : null,
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
        $csv = Reader::createFromPath(public_path('list-moshtariyan.csv'));
        $arr = array();
        foreach ($csv as $item) {
            array_push($arr, $item);
        }
        foreach (array_slice($arr, 1) as $row){
            Customer::create([
                'code' => $row[0],
                'store_name' => $row[1],
                'address' => $row[3],
                'phone' => $row[2]
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
        $customer->zone = $request->get('zone');
        $customer->phone = $request->get('phone');
        $customer->phone = $request->has('traffic_role') ? $request->get('traffic_role') : null;

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

    public function search_on_map(Request $request){
        $customers = DB::table('customers');
        $radius_from = $request->has('radius_from') ? $request->get('radius_from') : 0;
        if ($request->has('longitude')){
            if ($radius_from == 0)
                $customers = $customers->whereBetween('longitude' , [$request->get('longitude') - $request->get('radius'), $request->get('longitude') + $request->get('radius')]);
            else
                $customers = $customers
                    ->where(function ($query) use ($request, $radius_from) {
                       $query->whereBetween('longitude',
                            [$request->get('longitude') + $radius_from, $request->get('longitude') + $request->get('radius')])
                            ->orWhereBetween('longitude',
                                [$request->get('longitude') - $request->get('radius'), $request->get('longitude') - $radius_from]);
                    });
        }
        if ($request->has('latitude')){
            if ($radius_from == 0)
                $customers = $customers->whereBetween('latitude' , [$request->get('latitude') - $request->get('radius'), $request->get('latitude') + $request->get('radius')]);
            else
                $customers = $customers->where(function ($query) use ($request, $radius_from){
                        $query->whereBetween('latitude' ,
                            [$request->get('latitude') + $radius_from, $request->get('latitude') + $request->get('radius')])
                            ->orWhereBetween('latitude' ,
                                [$request->get('latitude') - $request->get('radius'), $request->get('latitude') - $radius_from]);
                    });

        }
        if ($request->has('index_from') and $request->has('index_to')){
            $customers = $customers->offset($request->get('index_from'))
                ->limit($request->get('index_to') - $request->get('index_from'));
        }
        return json_encode($customers->get());
    }

    public function search_by_phone(Request $request){
        return json_encode(Customer::where('phone', 'like', '%'.$request->get('phone').'%')->get());
    }

    public function import(Request $request) {
        $file_name = $request->file('file')->store('','public');

        try {
            $collection = (new FastExcel)->import('storage/' . $file_name);
        } catch (\Exception $e) {
            return response()->json('File not Found');
        }
        foreach ($collection as $item) {
            if ($item['عنوان']) {
                Customer::create([
                    'store_name' => $item['عنوان'],
                    'address' => $item['آدرس'],
                    'code' => $item['کد'],
                    'phone' => $item['تلفن'],
                ]);
            }
        }

        return response()->json(true);
    }

    public function getTrafficRoles() {
        return json_encode(DB::table('traffic_role')->get());
    }

    public function addTrafficRole(Request $request) {
        $tr = DB::table('traffic_role')->insert([
            'name' => $request->get('name'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return json_encode($tr);
    }
}
