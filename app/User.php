<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tickets() {
        return $this->hasMany('\App\Ticket', 'assigned_user_id', 'id');
    }

    public function ticketComments(){
        return $this->hasMany('\App\TicketComment');
    }

    public function notifications(){
        return $this->hasMany('\App\Notification')->orderBy('seen')->orderBy('id', 'DESC')->limit(10);
    }

    public function unseenNotifications(){
        return $this->hasMany('\App\Notification')->where('seen','=', 0);
    }

    public function rental(){
        return $this->hasOne('\App\Rental', 'user_id');
    }

    public function Cars()
    {
        return $this->hasMany('App\userCar', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany('App\User','follow', 'user_id', 'user_follow_id');
    }
}
