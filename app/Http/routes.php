<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/makeAdmin/{id}', 'HomeController@makeAdmin');

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
});