<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Carbon\Carbon $created_at
 * @property int $id
 * @property \Carbon\Carbon $updated_at
 * @property mixed $orders
 */
class Driver extends Model
{
    protected $table = 'drivers';

    protected $fillable = [
        'name', 'car', 'tag', 'status', 'capacity', 'phone_number'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
