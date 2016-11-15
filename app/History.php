<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'rent_history';

    public function Car()
	{
		return $this->belongsTo('App\user_car');
	}
}
