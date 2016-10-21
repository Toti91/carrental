<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SiteController extends Controller
{
	public function __construct()
    {
        $this->middleware('landing');
    }

    public function landingPage(){
    	return view('index');
    }
}
