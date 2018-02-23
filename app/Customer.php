<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Customer extends Model
{
    protected $fillable = [
        'phone', 'code', 'address', 'area', 'city', 'store_name', 'location'
    ];

    protected $table = 'customers';

}
