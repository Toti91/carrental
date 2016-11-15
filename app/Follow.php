<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follow';

    public static function ifFollower($user_id, $fallower_id){
    	$relationship = \App\Follow::where('user_id', '=', $user_id)->where('user_follow_id', '=', $fallower_id)->first();
    	if($relationship){
    		return true;
    	} else {
    		return false;
    	}
    }	

}
