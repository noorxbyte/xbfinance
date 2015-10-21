<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // category's transactions
    public function transactions()
    {
    	return $this->hasMany('App\Transaction');
    }
}
