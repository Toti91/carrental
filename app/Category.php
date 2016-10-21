<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function cars()
    {
        return $this->hasMany('App\Car');
    }
}
