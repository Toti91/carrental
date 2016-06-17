<?php

namespace App\Http\Controllers;


use Intervention\Image\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;

use App\Http\Requests;

class AdminController extends Controller
{
	// Admin frontpage
    public function getIndex(){
    	return view('admin/index');
    }

    //Admin cars page
    public function getCars(){
    	$cars = \App\Car::get();
    	$categories = \App\Category::get();
    	return view('admin/cars')->with('cars', $cars)->with('categories', $categories);
    }

    public function getUsers(){
    	$users = \App\User::get();
    	return view('admin/users')->with('users', $users);
    }

    public function createCategory(){
    	if($_POST['name'] && $_POST['price-min'] && $_POST['price-max']){
	    	$cat = new \App\Category;
	    	$cat->category = $_POST['category'];
	    	$cat->name = $_POST['name'];
	    	$cat->price_min = $_POST['price-min'];
	    	$cat->price_max = $_POST['price-max'];
	    	$cat->save();

	    	session()->flash('flash_success', $cat->name . ' created!');
    		return redirect('/admin/cars');
	    }

    	session()->flash('flash_error', 'Form filled out incorrectly!');
    	return redirect('/admin/cars');
    }

    public function createCar(){
    	if($_POST['name'] && $_POST['category-id'] && $_POST['price']){
	    	$car = new \App\Car;
	    	$car->category_id = $_POST['category-id'];
	    	$car->name = $_POST['name'];
	    	$car->price = $_POST['price'];

	    	//Image upload
	    	if($_FILES['image']['size'] > 1000000){
	    		
	    		throw new RuntimeException('Exceeded filesize limit');
	    	}
	    	else{

	    		$image = $_FILES['image']['tmp_name'];
	    		$filename = time() . '.' . Input::file('image')->getClientOriginalExtension();
	   			$path = public_path('useruploads/' . $filename);
				Image::make(Input::file('image')->getRealPath())->fit(200, 200)->save($path);
	    		$car->image = $filename;
	    	}

	    	$car->status = 'enabled';
	    	$car->save();

	    	session()->flash('flash_success', $car->name . ' created!');
    		return redirect('/admin/cars');
	    }

    	session()->flash('flash_error', 'Form filled out incorrectly!');
    	return redirect('/admin/cars');
    }

    //Tickets
    public function getTickets(){
    	$tickets = \App\Ticket::orderBy('status')->get();

    	return view('admin/tickets')->with('tickets', $tickets);
    }

    public function getTicket($id){
    	$ticket = \App\Ticket::find($id);
    	$comments = $ticket->comments;
    	$assigned = null;
    	if($ticket->user){
    		$assigned = $ticket->user;
    	}

    	return view('admin.pages.ticket')->with('ticket', $ticket)->with('comments', $comments)->with('assigned', $assigned);
    }

    public function createTicket(){
    	if($_POST['subject'] && $_POST['description']){
    		$ticket = new \App\Ticket;
    		$ticket->email = $_POST['email'];
    		$ticket->assigned_user_id = 0;
    		$ticket->subject = $_POST['subject'];
    		$ticket->description = $_POST['description'];
    		$ticket->status = 0;
    		$ticket->save();

    		session()->flash('flash_success', 'New ticket created!');
    		return redirect('/admin/tickets');
    	}

    	session()->flash('flash_error', 'Form filled out incorrectly!');
    	return redirect('/admin/tickets');
    }

    public function addComment($id){
    	if($_POST['message']){
    		$comment = new \App\TicketComment;
    		$comment->ticket_id = $id;
    		$comment->user_id = $_POST['userId'];
    		$comment->comment = $_POST['message'];
    		$comment->save();

    		return view('admin.pages.singlecomment')->with('comment', $comment);
    	}
    	return false;
    }
}