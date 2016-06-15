<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
	// Admin frontpage
    public function getIndex(){
    	return view('admin/index');
    }
}
