<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $image
 * @property int $user_id
 * @property int $code
 * @property string $phone
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property User $user
 */
class Accountant extends Model
{
    protected $fillable = [
        'image', 'user_id', 'code', 'phone', 'status'
    ];

    protected $table = 'accountants';

    public function user(){
        return $this->hasOne(User::class);
    }
}
