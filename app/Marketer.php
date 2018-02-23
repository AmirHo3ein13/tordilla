<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property User $user
 */
class Marketer extends Model
{
    protected $table = 'marketers';

    protected $fillable = [
        'phone', 'code', 'status', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
