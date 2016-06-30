<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $table = 'car_rentals';

    public function user(){
    	$this->belongsTo('\App\User');
    }
}
