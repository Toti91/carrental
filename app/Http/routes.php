<?php

use Intervention\Image\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'SiteController@landingPage');

Route::auth();

Route::get('/totalvalue', 'HomeController@totalCompanyValue');

Route::get('/home', 'HomeController@index');
Route::get('/makeAdmin/{id}', 'HomeController@makeAdmin');
Route::post('/rentalinfo/{id}', 'HomeController@postRentalInfo');

Route::get('/dealership', 'HomeController@getDealership');
Route::post('/getcarinfo/{id}/{rent_id?}', 'HomeController@postCarInfo');
Route::get('/buycar/{id}/{amount}', 'HomeController@buyCar');

Route::get('/garage', 'HomeController@getGarage');
Route::post('/rentcar/{id}', 'HomeController@rentCar');
Route::get('/sellcar/{id}', 'HomeController@sellCar');
Route::post('/maintenance/{id}', 'HomeController@maintainCar');
Route::post('/fixcar/{id}', 'HomeController@fixCar');

//Follow system
Route::post('/follow/{id}', 'HomeController@follow');
Route::post('/unfollow/{id}', 'HomeController@unfollow');

Route::post('/updateMoney', function(){
	$money = Auth::user()->rental;
	return '<small>$</small>'.number_format($money->money, 0, ',', '.');
});
Route::post('/updateParkedCars', function(){
	return '<small><i class="fa fa-plug"></i></small>'.number_format(\App\userCar::countParked(), 0, ',', '.');
});
Route::post('/updateStock', function(){
	$money = Auth::user()->rental;
	return '<small>$</small>'.number_format($money->stock, 2, ',', '.');
});

//TEMPORARY
Route::get('/returncar/{id}', function($id){
	$car = \App\userCar::find($id);
	$car->end = time() + 10;
	$car->save();

	return redirect('/garage');
});

// NEW CAR RENTAL HANDLING
Route::get('/create', function(){
	if(Auth::user()->rental){
		return redirect('/home');
	}
	return view('create');
});
Route::post('/create', function(){
	if($_POST['name']){
		$starting_money = \App\Setting::where('setting_name', '=', 'starting_money')->first();
		$rental = new \App\Rental;
		$rental->user_id = Auth::user()->id;
		$rental->name = $_POST['name'];
		$rental->stock = 5.0;
		$rental->money = $starting_money->setting;
		//Image upload
    	if($_FILES['image']['size'] > 1000000){
    		
    		throw new RuntimeException('Exceeded filesize limit');
    	}
    	else{

    		$image = $_FILES['image']['tmp_name'];
    		if($image){
	    		$filename = time() . '_'.$_FILES['image']['name'];
	   			$path = public_path('useruploads/rentals/' . $filename);
				Image::make(Input::file('image')->getRealPath())->fit(400, 400)->save($path);
	    		$rental->icon = $filename;
	    	} else {
	    		$rental->icon = 'placeholder.png';
	    	}
    	}
    	$rental->tutorial = 1;
    	$rental->save();

    	return redirect('/home');
	}
});
/////////////////////////

//Socialite reidrects
Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');

//Admin routes
Route::group(['middleware' => 'admin'], function () {
	//Admin Dashboard
	Route::get('/admin', 'AdminController@getIndex');
	//Admin cars
	Route::get('/admin/cars', 'AdminController@getCars');
		//Admin new category
		Route::post('/admin/cars/newcategory', 'AdminController@createCategory');
		//Admin new car
		Route::post('/admin/cars/new', 'AdminController@createCar');

	//Admin tickets
	Route::get('/admin/tickets/{id?}', 'AdminController@getTickets');
		//Admin new ticket
		Route::post('/admin/tickets/new', 'AdminController@createTicket');
		Route::get('/admin/tickets/edit/{id}', 'AdminController@getEditTicket');
		Route::get('/admin/tickets/remove/{id}', 'AdminController@getRemoveTicket');
		Route::post('/admin/tickets/edit', 'AdminController@editTicket');
		Route::post('/admin/tickets/remove', 'AdminController@removeTicket');
		Route::get('/admin/tickets/assign/{id}', 'AdminController@getUserToAssign');
		Route::get('/admin/tickets/assigned/{ticketId}/{userId}', 'AdminController@assignUser');
		Route::get('/admin/tickets/setstatus/{ticketId}/{status}', 'AdminController@changeTicketStatus');
		//Admin get single ticket w. AJAX
		Route::post('/admin/ticket/{id}', 'AdminController@getTicket');
		Route::post('/admin/ticket/newcomment/{id}', 'AdminController@addComment');

	Route::get('/admin/users', 'AdminController@getUsers');

	//Admin notifications
	Route::post('admin/notifications/get', 'AdminController@getNotifications');
	Route::post('admin/notifications/unseen', 'AdminController@getUnseen');
	Route::post('admin/notifications/update', 'AdminController@updateNotifications');

	//Admin Settings
	Route::get('admin/settings', 'AdminController@getSettings');
	Route::post('admin/settings/change', 'AdminController@changeSetting');

	//Admin malfunctions
	Route::get('admin/malfunctions', 'AdminController@getMalfunctions');
	Route::post('admin/malfunction/new', 'AdminController@newMalfunction');
});