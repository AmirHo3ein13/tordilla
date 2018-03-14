<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request){
        $location = $request->has('location') ? $request->get('location') : null;
        return Order::create([
            'customer_id' => $request->get('customer_id'),
            'marketer_id' => $request->get('marketer_id'),
            'order_details' => $request->get('order_details'),
            'amount' => $request->get('amount'),
            'discount' => $request->get('discount'),
            'submit_date' => $request->get('submit_date'),
            'location' => $location,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function get($id = -1){
        if ($id == -1)
            return json_encode(Order::all());
        else
            return json_encode(Order::findOrFail($id));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function add_location(Request $request){
        $order = Order::findOrFail($request->get('id'));
        $order['location'] = $request->get('location');
        return json_encode(true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function filter(Request $request){
        if ($request->has('marketer') and $request->has('start_datetime')){
            return json_encode(Order::where([
                ['marketer_id', '=', $request->get('marketer')],
                ['submit_datetime', '>=', $request->get('start_datetime')],
                ['submit_datetime', '<=', $request->get('end_datetime')],
            ])
                ->offset($request->get('index_from'))
                ->limit($request->get('index_to') - $request->get('index_from'))
                ->get());
        }
        else if ($request->has('marketer')) {
            return json_encode(Order::where([
                ['marketer_id', '=', $request->get('marketer')],
            ])
                ->offset($request->get('index_from'))
                ->limit($request->get('index_to') - $request->get('index_from'))
                ->get());
        }
        else if ($request->has('start_datetime')) {
            return json_encode(Order::where([
                ['submit_datetime', '>=', $request->get('start_datetime')],
                ['submit_datetime', '<=', $request->get('end_datetime')],
            ])
                ->offset($request->get('index_from'))
                ->limit($request->get('index_to') - $request->get('index_from'))
                ->get());
        }
        else {
            return json_encode(Order::all());
        }
    }
}
