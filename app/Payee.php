<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
    // payee's transactions
    public function transactions()
    {
    	return $this->hasMany('App\Transaction');
    }
}
