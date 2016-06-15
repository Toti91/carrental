<?PHP 
	$active = 'cars'; 
	$categories = \App\Category::get();
?>
@extends('layouts.admin')

@section('overlay-nav')
	<a href="#" class="overlay-link active-li" id="cars"><li class="active">Cars</li></a>
	<a href="#" class="overlay-link" id="categories"><li>Categories</li></a>
@stop

@section('overlay-content')
	<div class="overlay-content" id="cars">
		<form method="POST" action="/admin/cars/new" class="input-default">
			{{ csrf_field() }}
			<select name="category-id" class="input-default">
				@foreach($categories as $category)
					<option value="{{ $category->id }}"> {{ $category->category }} - {{ $category->name }} </option>
				@endforeach
			</select>
			<input type="text" name="name" placeholder="Name" class="input-default">
			<input type="number" name="price" placeholder="Price" class="input-default">
			<input type="submit" id="submit-car" class="submit-default">
		</form>
	</div>
	<div class="overlay-content" id="categories">
		<form method="POST" action="/admin/cars/newcategory">
			{{ csrf_field() }}
			<input type="text" name="category" placeholder="category" class="input-default">
			<input type="text" name="name" placeholder="Name" class="input-default">
			<input type="number" name="price-min" placeholder="Price-Min" class="input-default input-2 leftFloat">
			<input type="number" name="price-max" placeholder="Price-Max" class="input-default input-2 rightFloat">
			<input type="submit" id="submit-category" class="submit-default">
		</form>
	</div>
@stop


@section('container')
	Cars
@stop