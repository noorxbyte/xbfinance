<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    // account's transactions
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    // account's transfers
    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }

    // account's user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
