<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    public static function getStartMoney(){
    	$money = \App\Setting::where('setting_name', '=', 'starting_money')->first();
    	return $money->setting;
    }
}
