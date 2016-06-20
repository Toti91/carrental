<?php

namespace App\Http\Controllers;


use Intervention\Image\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Auth;

use App\Http\Requests;

class AdminController extends Controller
{
	// Admin frontpage
    public function getIndex(){
    	$users = \App\User::get();
    	$cars = \App\Car::get();
    	return view('admin/index')->with('users', $users)->with('cars', $cars);
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
    public function getTickets($id = 0){
    	$tickets = \App\Ticket::orderBy('status')->get();

        if($id){
            $loadTicket = $id;
        }
        else {
            if($tickets){
                $loadTicket = \App\Ticket::orderBy('status')->get()->first()->id;
            } else {
                $loadTicket = 0;
            }
        }

    	return view('admin/tickets')->with('tickets', $tickets)->with('loadTicket', $loadTicket);
    }

    public function getTicket($id){
    	$ticket = \App\Ticket::find($id);
        if($ticket){
            $comments = $ticket->comments;
            $assigned = null;
        	if($ticket->user){
        		$assigned = $ticket->user;
        	}

        	return view('admin.pages.ticket')->with('ticket', $ticket)->with('comments', $comments)->with('assigned', $assigned);
        }

        return '<div class="ticket-error">Ticket not found!</div>';
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

    public function getEditTicket($id){
        $ticket = \App\Ticket::find($id);

        $admins = \App\User::where('access', '=', 1)->orderBy('name')->get();

        if($ticket){
           return view('admin.edit.ticket')->with('ticket', $ticket)->with('admins', $admins);
        }

        session()->flash('flash_error', 'Ticket not found!');
        return redirect('/admin/tickets');
    }

    public function getRemoveTicket($id){
        $ticket = \App\Ticket::find($id);

        $comments = $ticket->comments;

        if($ticket){
           return view('admin.remove.ticket')->with('ticket', $ticket)->with('comments', $comments);
        }

        session()->flash('flash_error', 'Ticket not found!');
        return redirect('/admin/tickets');
    }

    public function editTicket(){
        $ticket = \App\Ticket::find($_POST['ticket-id']);

        if($ticket){
            $user_id = $ticket->assigned_user_id;
            if($user_id != $_POST['assigned-user']){
                ( new \App\NotificationService(
                    '@user_'.Auth::user()->id.' unassigned you from @ticket_'.$_POST['ticket-id'].' ticket.', 
                    $user_id,
                    'fa-chain-broken',
                    'hex-pink'
                ))->newNotification();

                $ticket->assigned_user_id = $_POST['assigned-user'];

                ( new \App\NotificationService(
                    '@user_'.Auth::user()->id.' assigned you to  @ticket_'.$_POST['ticket-id'].' ticket.', 
                    $_POST['assigned-user'],
                    'fa-link',
                    'hex-pink'
                ))->newNotification();
            }

            $ticket->email = $_POST['email'];
            $ticket->subject = $_POST['subject'];
            $ticket->description = $_POST['description'];
            $ticket->save();

            session()->flash('flash_success', 'Ticket edited!');
            
            return redirect('/admin/tickets/'.$_POST['ticket-id']);
        }

        session()->flash('flash_error', 'Ticket not found!');
        return redirect('/admin/tickets');
    }

    public function removeTicket(){
        $ticket = \App\Ticket::find($_POST['ticket-id']);

        $comments = $ticket->comments;

        if($ticket){
            foreach($comments as $comment){
                $comment->delete();
            }

            $ticket->delete();

            session()->flash('flash_success', 'Ticket deleted!');
            return redirect('/admin/tickets');
        }

        session()->flash('flash_error', 'Ticket not found!');
        return redirect('/admin/tickets');

    }

    public function addComment($id){
    	if($_POST['message']){
    		$comment = new \App\TicketComment;
    		$comment->ticket_id = $id;
    		$comment->user_id = $_POST['userId'];
    		$comment->comment = $_POST['message'];
    		$comment->save();

            $assigned = \App\Ticket::find($id);
            if($assigned){
                ( new \App\NotificationService(
                    '@user_'.$_POST['userId'].' added a comment to your @ticket_'.$id.' ticket. "<i>'.$comment->comment.'</i>"', 
                    $assigned->assigned_user_id,
                    'fa-comment-o',
                    'hex-blue'
                ))->newNotification();
            }    

    		return view('admin.pages.singlecomment')->with('comment', $comment);
    	}
    	return false;
    }


    public function getNotifications(){
        $notifications = Auth::user()->notifications;

        return view('admin.pages.notifications')->with('notifications', $notifications);
    }

    public function getUnseen(){
        return Auth::user()->unseenNotifications->count();
    }

    public function updateNotifications(){
        $notifications = Auth::user()->unseenNotifications;

        foreach($notifications as $notification){
            $notification->seen = 1;
            $notification->save();
        }

        return 'success';
    }

    public function getUserToAssign($id){
        $admins = \App\User::where('access', '=', 1)->orderBy('name')->get();

        return view('admin.pages.usersToAssign')->with('admins', $admins)->with('ticketId', $id);
    }

    public function assignUser($ticketId, $userId){
        $ticket = \App\Ticket::find($ticketId);

        $actionUser = Auth::user()->id;

        $ticket->assigned_user_id = $userId;
        $ticket->save();

        ( new \App\NotificationService(
            '@user_'.$actionUser.' assigned you to  @ticket_'.$ticketId.' ticket.', 
            $userId,
            'fa-link',
            'hex-pink'
        ))->newNotification();

        session()->flash('flash_success', 'User assigned to ticket!');
        return redirect('/admin/tickets/'.$ticketId);
    }

    public function changeTicketStatus($ticketId, $status) {
        $ticket = \App\Ticket::find($ticketId);
        $ticket->status = $status;
        $ticket->save();

        session()->flash('flash_success', 'Ticket status changed!');
        return redirect('/admin/tickets/'.$ticketId);
    }
}