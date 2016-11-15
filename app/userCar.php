<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userCar extends Model
{
    protected $table = 'car_user';

    public function User()
	{
		return $this->belongsTo('App\User');
	}
	public function Car()
	{
		return $this->belongsTo('App\Car');
	}
	public function Histories()
	{
		return $this->hasMany('App\History', 'rent_id');
	}

	public static function getUserTotalCars($id){
		$total = \App\userCar::where('user_id', '=', $id)->count();

		return number_format($total, 0, ',', '.');
	}

	public static function getUserTotalHours($id){
		$total = \App\userCar::where('user_id', '=', $id)->sum('km_count');

		return number_format($total, 0, ',', '.');
	}

	public static function countTotal(){
		$total = \App\userCar::where('user_id', '=', \Auth::user()->id)->count();

		return $total;
	}

	public static function countParked(){
		$time = time() + 10;
		$total = \App\userCar::where('user_id', '=', \Auth::user()->id)->where('end', '<', $time)->count();

		return $total;
	}

	public static function resetCar($id){
		$car = \App\userCar::find($id);
		$car->rented = 0;
		$car->save();

		return true;
	}

	public function malfunctions()
	{
	    return $this->belongsToMany('App\Malfunction', 'malfunction_car', 'rent_id', 'malfunction_id');
	}
}
