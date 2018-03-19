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
        'customer_id', 'marketer_id', 'order_details',
        'amount', 'discount', 'submit_datetime', 'latitude', 'longitude'
    ];

    protected $table = 'orders';

    public function customer(){
        return $this->hasOne(Customer::class);
    }

    public function marketer(){
        return $this->hasOne(Marketer::class);
    }

}
