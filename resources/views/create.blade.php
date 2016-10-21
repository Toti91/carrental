<?PHP
	$hidenav = true;
?>
@extends('layouts.app')

@section('title')
	CRM | New rental
@stop

@section('content')
	<form action="create" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="text" name="name" placeholder="Name of your rental">
		<input type="file" name="image" accept="image/*">
		<input type="submit" id="submit-rental" class="btn blue waves-effect waves-light">
	</form>
@stop