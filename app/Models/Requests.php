<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    protected $table = 'requests';
    use HasFactory;
    protected $fillable = [
        'sender_user_id',
        'receiver_user_id'
    ];

    /**
     * For getting received request users
     */
    public function receivers()
    {
        return $this->hasOne('App\Models\User','id','receiver_user_id');
    }

    /**
     * For getting sent request users
     */
    public function senders()
    {
        return $this->hasOne('App\Models\User','id','sender_user_id');
    }
}
