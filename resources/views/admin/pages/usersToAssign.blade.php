<?PHP 
	$active = 'tickets'; 
?>

@extends('layouts.admin')

@section('title-bar')
	<div class="content-10 title-bar-content">
		<h3>Users</h3>
	</div>
@stop

@section('container')
	<div class="content content-10">
		@foreach($admins as $admin)
			<a href="/admin/tickets/assigned/{{$ticketId}}/{{$admin->id}}">
				<div class="single-admin">
					<div class="sa-avatar">
						<img src="{{ $admin->avatar }}">
					</div>
					<div class="sa-name">
						{{ $admin->name }}
					</div>
				</div>
			</a>
		@endforeach
	</div>
@stop