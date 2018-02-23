<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property ProductCategory $category
 */
class Product extends Model
{
    protected $fillable = [
        'name', 'image', 'code', 'product_category', 'number_in_box', 'price', 'box_price'
    ];

    protected $table = 'products';

    public function category(){
        return $this->belongsTo(ProductCategory::class);
    }
}
