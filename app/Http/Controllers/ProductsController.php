<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function add(Request $request){
        return Product::create([
            'name' => $request->get('name'),
            'image' => $request->file('image')->store('product_image'),
            'code' => $request->get('code'),
            'category' => $request->get('category'),
            'number_in_box' => $request->get('number_in_box'),
            'price' => $request->get('price'),
            'box_price' => $request->get('box_price'),
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
}
