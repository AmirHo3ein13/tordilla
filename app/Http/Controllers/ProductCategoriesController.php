<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
    public function add(Request $request){
        return ProductCategory::create([
            'name' => $request->get('name'),
        ]);
    }
    public function get($id = -1){
        if ($id == -1)
            return json_encode(ProductCategory::all());
        else
            return json_encode(ProductCategory::findOrFail($id));
    }
}
