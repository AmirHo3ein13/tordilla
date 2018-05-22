<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Customer $customer
 * @property Marketer $marketer
 */
class Order extends Model
{
    protected $fillable = [
        'customer_id', 'user_id', 'order_details',
        'amount', 'discount', 'submit_datetime', 'latitude', 'longitude',
        'step', 'driver', 'factor_number', 'voice', 'image', 'description',
    ];

    protected $table = 'orders';

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function driver(){
        return $this->hasOne(Driver::class);
    }

}
