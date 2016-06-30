<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rental = Auth::user()->rental;
        return view('home')->with('rental', $rental);
    }

    public function newRental(){
        return view('create');
    }

    public function makeAdmin($id)
    {
        $user = \App\User::find($id);
        $user->access = 1;
        $user->save();

        return redirect('/admin');
    }
}
