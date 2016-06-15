<?PHP $active = 'cars'; ?>
@extends('layouts.admin')

@section('overlay-nav')
	<a href="#" class="overlay-link active-li" id="cars"><li class="active">Cars</li></a>
	<a href="#" class="overlay-link" id="categories"><li>Categories</li></a>
@stop

@section('overlay-content')
	<div class="overlay-content" id="cars">
		<form method="POST" action="/admin/cars/new">
			{{ csrf_field() }}
			<input type="number" name="category-id" placeholder="Category-Id"><br>
			<input type="string" name="name" placeholder="Name"><br>
			<input type="number" name="price" placeholder="Price"><br>
			<input type="submit" id="submit-car">
		</form>
		Cars form
	</div>
	<div class="overlay-content" id="categories">
		<form method="POST" action="/admin/cars/newcategory">
			{{ csrf_field() }}
			<input type="text" name="category" placeholder="category"><br>
			<input type="text" name="name" placeholder="Name"><br>
			<input type="number" name="price-min" placeholder="Price-Min"><br>
			<input type="number" name="price-max" placeholder="Price-Max"><br>
			<input type="submit" id="submit-category">
		</form>
	</div>
@stop


@section('container')
	Cars
@stop