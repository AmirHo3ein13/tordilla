<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request){
        return json_encode(
            Order::create([
                'customer_id' => $request->get('customer_id'),
                'marketer_id' => $request->get('marketer_id'),
                'order_details' => $request->get('order_details'),
                'amount' => $request->get('amount'),
                'discount' => $request->get('discount'),
                'submit_date' => $request->get('submit_date'),
                'latitude' => $request->has('latitude') ? $request->get('latitude') : null,
                'longitude' => $request->has('longitude') ? $request->get('longitude') : null,
            ])
        );
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
        $order['latitude'] = $request->get('latitude');
        $order['longitude'] = $request->get('longitude');
        return json_encode(true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function filter(Request $request){
        $orders = Order::all();
        if ($request->has('marketer')){
            $orders = $orders->where('marketer_id', '=', $request->get('marketer'));
        }
        if ($request->has('start_datetime')){
            $orders = $orders->where('created_at', '>=', $request->get('start_datetime'));
        }
        if ($request->has('start_datetime')){
            $orders = $orders->where('created_at', '>=', $request->get('start_datetime'));
        }
        return json_encode(
            $orders
                ->slice($request->get('index_from'))
                ->sortByDesc('created_at')
                ->take($request->get('index_to') - $request->get('index_from'))
                ->all()
        );
    }

    public function change_step($id, Request $request){
        $order = Order::findOrFail($id);
        if ($order->step == 0){
            if ($request->exists('driver')){
                $order->driver = $request->get('driver');
                $order->step++;
                $order->save();
            }
            else
                abort(500);
        }
        elseif ($order->step == 1){
            if ($request->exists('factor')){
                $order->factor_number = $request->get('factor');
                $order->step++;
                $order->save();
            }
            else
                abort(500);
        }
        return json_encode(true);
    }

    public function delete($id){
        Order::destroy($id);
        return json_encode(true);
    }

    public function update($id, Request $request){
        $order = Order::findOrFail($id);
        $order->customer_id = $request->get('customer_id');
        $order->marketer_id = $request->get('marketer_id');
        $order->order_details = $request->get('order_details');
        $order->amount = $request->get('amount');
        $order->discount = $request->get('discount');
        $order->submit_date = $request->get('submit_date');
        $order->latitude = $request->has('latitude') ? $request->get('latitude') : null;
        $order->longitude = $request->has('longitude') ? $request->get('longitude') : null;

        $order->save();

        return json_encode($order);
    }
}
