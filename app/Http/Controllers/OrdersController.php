<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request){
        $order_details = $request->get('order_details');
        $this->order_detail($order_details);
        return json_encode(
            Order::create([
                'customer_id' => $request->get('customer_id'),
                'marketer_id' => $request->get('marketer_id'),
                'order_details' => $order_details,
                'amount' => $request->get('amount'),
                'discount' => $request->get('discount'),
                'latitude' => $request->has('latitude') ? $request->get('latitude') : null,
                'longitude' => $request->has('longitude') ? $request->get('longitude') : null,
                'image' => $request->has('image') ?
                    $request->file('image')->store('order_image') : null,
                'voice' => $request->has('voice') ?
                    $request->file('voice')->store('voice') : null,
                'description' => $request->get('description'),
            ])
        );
    }

    private function order_detail($detail){
        $orders = array_slice(explode('|', $detail), 0, -1);
        foreach ($orders as $order){
            $id_number = explode(':', $order);
            $id = $id_number[0];
            $box_pack = explode(',',$id_number[1]);
            $pack = $box_pack[1];
            if (strpos($pack, 'e') != false)
                $pack = substr($pack, 1);
            $product = Product::where('code', $id)->first();
            if (!$product)
                abort(500);
            $pack += $box_pack[0] * $product['number_in_box'];
            if ($product['inventory'] >= $pack){
                $product['inventory'] -= $pack;
                $product->save();
            }
            else{
                $product['reservation_inventory'] -= $pack;
                $product->save();
            }
        }
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
        foreach ($orders as $order){
            $order->customer;
            $order->marketer;
        }
        return json_encode(
            $orders
                ->sortByDesc('created_at')
                ->slice($request->get('index_from'))
                ->take($request->get('index_to') - $request->get('index_from'))
                ->values()
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
        elseif ($order->step == 2){
            $order->step++;
            $order->save();
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
        $order->latitude = $request->has('latitude') ? $request->get('latitude') : null;
        $order->longitude = $request->has('longitude') ? $request->get('longitude') : null;
        $order->image = $request->has('image') ?
            $request->file('image')->store('order_image') : null;
                $order->voice = $request->has('voice') ?
            $request->file('voice')->store('voice') : null;
                $order->description = $request->get('description');

        $order->save();

        return json_encode($order);
    }
}
