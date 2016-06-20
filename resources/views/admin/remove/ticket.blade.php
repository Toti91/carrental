<?PHP 
	$active = 'tickets'; 
?>

@extends('layouts.admin')

@section('title-bar')
	<div class="content-8 title-bar-content">
		<h3>Remove {{ $ticket->subject }}</h3>
	</div>
@stop

@section('container')
	<div class="content content-8 edit-content">
		<h3>Are you sure you want to delete this ticket and its comments?</h3>
		<form method="POST" action="/admin/tickets/remove">
			{{ csrf_field() }}
			<input type="hidden" name="ticket-id" value="{{ $ticket->id }}">
			<input type="submit" id="submit-car" class="submit-default" value="Delete">
		</form>
	</div>
@stop