<?PHP 
	$active = 'users'; 
?>

@extends('layouts.admin')

@section('overlay-nav')
	<h2>Users</h2>
@stop

@section('overlay-content')
	
@stop

@section('title-bar')
	<div class="content-4 title-bar-content">
		<h3>Users</h3>
	</div>
@stop

@section('container')
	<div class="content content-6">
		@foreach($users as $user)
				<div class="single-item">
				<div class="si-icon"> <img src="{{ Auth::user()->avatar }}"> </div>
				<div class="si-top">
					<div class="si-name"> {{ $user->name }} </div> 
				</div>
				<div class="si-bottom">
					<div class="si-email"> {{ $user->email }} </div>
					<div class="si-actions">
						<a href="#">edit</a>
						<a href="#">remove</a>
					</div>
				</div>
			</div>
		@endforeach
	</div>
@stop