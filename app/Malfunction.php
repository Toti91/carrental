<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Malfunction extends Model
{
    protected $table = 'malfunctions';

    public function cars()
    {
	    return $this->belongsToMany('App\userCar', 'malfunction_car', 'malfunction_id', 'rent_id');
	}
}
