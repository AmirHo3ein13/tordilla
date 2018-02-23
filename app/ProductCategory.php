<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Product $products
 */
class ProductCategory extends Model
{
    protected $fillable = ['name'];

    protected $table = 'product_categories';

    public function products(){
        return $this->hasMany(Product::class);
    }
}
