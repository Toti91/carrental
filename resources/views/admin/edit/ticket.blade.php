<?PHP 
	$active = 'tickets'; 
?>

@extends('layouts.admin')

@section('title-bar')
	<div class="content-8 title-bar-content">
		<h3>Edit {{ $ticket->subject }}</h3>
	</div>
@stop

@section('container')
	<div class="content content-8 edit-content">
		<form method="POST" action="/admin/tickets/edit">
			{{ csrf_field() }}
			<input type="hidden" name="ticket-id" value="{{ $ticket->id }}">
			<select name="assigned-user" class="input-default input-2">
				@foreach($admins as $admin)
					<option value="{{ $admin->id }}" @if($ticket->assigned_user_id == $admin->id) selected @endif>{{ $admin->name }}</option>
				@endforeach
			</select>
			<input type="email" name="email" class="input-default input-2" value="{{ $ticket->email }}">
			<input type="text" name="subject" placeholder="Subject" class="input-default" value="{{ $ticket->subject }}">
			<textarea name="description" class="textarea-default" placeholder="Ticket">{!! e($ticket->description) !!}</textarea>
			<input type="submit" id="submit-car" class="submit-default">
		</form>
	</div>
@stop