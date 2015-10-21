<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    // transfer's from account
    public function from()
    {
        return $this->belongsTo('App\Account', 'account_from');
    }

    // transfer's to account
    public function to()
    {
        return $this->belongsTo('App\Account', 'account_to');
    }
}
