<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'Notifications';

    public function user(){
    	$this->belongsTo('\App\User');
    }
}
