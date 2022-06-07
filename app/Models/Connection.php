<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'connected_user_id'
    ];

    /**
     * For getting connections users
     */
    public function users()
    {
        return $this->hasOne('App\Models\User','id','connected_user_id');
    }

    /**
     * For getting connections common
     */
    public function commons()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
