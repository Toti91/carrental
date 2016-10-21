<?PHP 
	$active = 'malfunctions'; 
?>
@extends('layouts.admin')

@section('overlay-nav')
	<h2>Add new malfunction</h2>
@stop

@section('overlay-content')
	<div class="single-overlay-content">
		<form method="POST" action="/admin/malfunction/new" class="input-default" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="text" name="subject" placeholder="Malfunction" class="input-default">
			<textarea name="description" class="textarea-default" placeholder="Description"></textarea>
			<input type="text" name="subject" placeholder="Malfunction" class="input-default">
			<input type="submit" id="submit-car" class="submit-default">
		</form>
	</div>
@stop

@section('title-bar')
	<div class="content-6 title-bar-content">
		<h3>Malfunctions</h3>
	</div>
	<div class="content-4 title-bar-content">
		<h3></h3>
	</div>
@stop

@section('container')
	<div class="content content-6">
		@foreach($malfunctions as $mal)
			<div class="single-item">
				<div class="si-top">
					<div class="si-name"> {{ $mal->name }} </div>
					<div class="si-price"> ${{ number_format($mal->cost, 0, ',', '.') }} </div>
				</div>
				<div class="si-bottom">
					<div class="si-actions">
						<a href="#">edit</a>
						<a href="#">remove</a>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<div class="content content-4">
		
	</div>
@stop