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

    public function update($id, Request $request){
        $pc = ProductCategory::findOrFail($id);

        $pc->name = $request->get('name');
        $pc->save();

        return json_encode($pc);
    }

    public function delete($id){
        ProductCategory::destroy($id);

        return json_encode(true);
    }
}
