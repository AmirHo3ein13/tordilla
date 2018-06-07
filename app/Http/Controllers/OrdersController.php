<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Product;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request){
        $order_details = $request->get('order_details');

        $ret = $this->order_detail($order_details);
        if (sizeof($ret) and !($request->has('reserve') and $request->get('reserve'))){
            return json_encode(['status' => false, 'products' => $ret]);
        }
        return json_encode(
            Order::create([
                'customer_id' => $request->get('customer_id'),
                'user_id' => $request->get('user_id'),
                'driver' => $request->get('driver'),
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
                'reserve' => $request->has('reserve') ? $request->get('reserve') : false
            ])
        );
    }

    /**
     * @param $detail
     * @return array
     */
    private function order_detail($detail){
        $not_enough = array();
        $orders = array_slice(explode('|', $detail), 0, -1);
        $products = array();
        foreach ($orders as $order){
            $id_number = explode(':', $order);
            $id = $id_number[0];
            $box_pack = explode(',',$id_number[1]);
            $pack = $box_pack[1];
            if (strpos($pack, 'e') != false)
                $pack = substr($pack, 1);
            $product = Product::where('code', $id)->first();
            array_push($products, $product);
            if (!$product)
                abort(500);
            $pack += $box_pack[0] * $product['number_in_box'];
            if ($product['inventory'] >= $pack){
                $product['inventory'] -= $pack;
            }
            else{
                array_push($not_enough, $product['id']);
            }
        }
        if (!sizeof($not_enough))
            foreach ($products as $product)
                $product->save();
        return $not_enough;
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
            $orders = $orders->where('user_id', '=', $request->get('marketer'));
        }
        if ($request->has('start_datetime')){
            $orders = $orders->where('created_at', '>=', $request->get('start_datetime').' 23:59:59');
        }
        if ($request->has('end_datetime')){
            $orders = $orders->where('created_at', '<=', $request->get('end_datetime').' 23:59:59');
        }
        foreach ($orders as $order){
            $order->customer;
            $order->user;
        }
        return json_encode(
            $orders
                ->sortByDesc('created_at')
                ->slice($request->get('index_from'))
                ->take($request->get('index_to') - $request->get('index_from'))
                ->values()
        );
    }

//    public function change_step($id, Request $request){
//        $order = Order::findOrFail($id);
//        if ($order->step == 0){
//            if ($request->exists('driver')){
//                $order->driver = $request->get('driver');
//                $order->step++;
//                $order->save();
//            }
//            else
//                abort(500);
//        }
//        elseif ($order->step == 1){
//            if ($request->exists('factor')){
//                $order->factor_number = $request->get('factor');
//                $order->step++;
//                $order->save();
//            }
//            else
//                abort(500);
//        }
//        elseif ($order->step == 2){
//            $order->step++;
//            $order->save();
//        }
//        return json_encode(true);
//    }

    /**
     * @param $id
     * @return string
     */
    public function delete($id){
        Order::destroy($id);
        return json_encode(true);
    }

    /**
     * @param $id
     * @param Request $request
     * @return string
     */
    public function update($id, Request $request){
        $order = Order::findOrFail($id);
        if (Role::find(Auth::user()['role']) == 'sales manager' and
            strtotime($order['created_at'].' + 1 day') >= time()){
            return json_encode('update date expired');
        }
        elseif (Role::find(Auth::user()['role']) == 'sales supervisor' and
            $order['transmission_day'] != null){
            return json_encode('update date expired');
        }
        if ($order->reserve and !$request->get('reserve')){
            $order_details = $request->get('order_details');
            $ret = $this->order_detail($order_details);
            if (sizeof($ret) and !($request->has('reserve') and $request->get('reserve'))){
                return json_encode(['status' => false, 'products' => $ret]);
            }
        }
        $order->customer_id = $request->get('customer_id');
        $order->user_id = $request->get('user_id');
        $order->driver = $request->get('driver');
        $order->order_details = $request->get('order_details');
        $order->amount = $request->get('amount');
        $order->discount = $request->get('discount');
        $order->latitude = $request->has('latitude') ? $request->get('latitude') : null;
        $order->longitude = $request->has('longitude') ? $request->get('longitude') : null;
        $order->reserve = $request->has('reserve') ? $request->get('reserve') : $order->reserve;
        $order->image = $request->has('image') ?
            $request->file('image')->store('order_image') : null;
        $order->voice = $request->has('voice') ?
            $request->file('voice')->store('voice') : null;
        $order->description = $request->get('description');
        if ($request->exists('driver')){
            if (!$order->driver){
                $order->transmission_day = $request->has('transmission_day') ?
                    $request->get('transmission_day') : null;
                $order->step = 1;
            }
            $order->driver = $request->get('driver');
        }
        if ($request->exists('factor')){
            if (!$order->factor_number){
                $order->step = 2;
            }
            $order->factor_number = $request->get('factor');
        }

        $order->save();

        return json_encode($order);
    }
}
