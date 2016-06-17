<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $table = 'ticket_comments';

    public function ticket(){
    	return $this->belongsTo('\App\Ticket');
    }

    public function user(){
    	return $this->belongsTo('\App\User');
    }
}
