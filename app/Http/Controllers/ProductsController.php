<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Whoops\Handler\PrettyPageHandler;

class ProductsController extends Controller
{
    public function add(Request $request){
        return Product::create([
            'name' => $request->get('name'),
            'image' => $request->file('image')->store('product_image'),
            'code' => $request->get('code'),
            'category_id' => $request->get('category'),
            'number_in_box' => $request->get('number_in_box'),
            'price' => $request->get('price'),
            'box_price' => $request->get('box_price'),
            'inventory' => $request->get('inventory'),
            'reservation_inventory' => $request->get('reservation_inventory'),
        ]);
    }
    public function get($id = -1){
        if ($id == -1)
            return json_encode(Product::all());
        else
            return json_encode(Product::findOrFail($id));
    }
    public function get_image($id){
        return response()->file(public_path('storage/'.Product::findOfFail($id)['image']));
    }

    public function update($id, Request $request){
        $product = Product::findOrFail($id);
        $product->name = $request->get('name');
        $product->image = $request->file('image')->store('product_image');
        $product->code = $request->get('code');
        $product->category = $request->get('category');
        $product->number_in_box = $request->get('number_in_box');
        $product->price = $request->get('price');
        $product->box_price = $request->get('box_price');
        $product->inventory = $request->get('inventory');
        $product->reservation_inventory = $request->get('reservation_inventory');

        $product->save();

        return json_encode(true);
    }

    public function delete($id){
        Product::destroy($id);

        return json_encode(true);
    }

    public function edit_inventory($id){

    }
    public function edit_reservation_inventory($id){}
}
