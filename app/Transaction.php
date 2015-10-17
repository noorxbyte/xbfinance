<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	// transaction's account
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    // transaction's payee
    public function payee()
    {
        return $this->belongsTo('App\Payee');
    }

    // transaction's category
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    // transaction's user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
