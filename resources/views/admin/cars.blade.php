<?PHP 
	$active = 'cars'; 
?>
@extends('layouts.admin')

@section('overlay-nav')
	<a href="#" class="overlay-link active-li" id="cars"><li class="active">Cars</li></a>
	<a href="#" class="overlay-link" id="categories"><li>Categories</li></a>
@stop

@section('overlay-content')
	<div class="overlay-content" id="cars">
		<form method="POST" action="/admin/cars/new" class="input-default" enctype="multipart/form-data">
			{{ csrf_field() }}
			<select name="category-id" class="input-default">
				@foreach($categories as $category)
					<option value="{{ $category->id }}"> {{ $category->category }} - {{ $category->name }} </option>
				@endforeach
			</select>
			<input type="text" name="name" placeholder="Name" class="input-default">
			<input type="number" name="price" placeholder="Price" class="input-default">
			<input type="file" name="image" accept="image/*">
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

@section('title-bar')
	<div class="content-6 title-bar-content">
		<h3>Cars</h3>
	</div>
	<div class="content-4 title-bar-content">
		<h3>Categories</h3>
	</div>
@stop

@section('container')
	<div class="content content-6">
		@foreach($cars as $car)
			<div class="single-item">
				<div class="si-icon"> <img src="/useruploads/{{ $car->image }}"> </div>
				<div class="si-top">
					<div class="si-name"> {{ $car->name }} </div>
					<div class="si-price"> {{ number_format($car->price, 0, ',', '.') }} ISK </div>
				</div>
				<div class="si-bottom">
					<div class="si-status status-{{ $car->status }}"> <a href="#">{{ $car->status }}</a> </div>
					<div class="si-actions">
						<a href="#">edit</a>
						<a href="#">remove</a>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<div class="content content-4">
		@foreach($categories as $category)
			<div class="single-item">
				<div class="si-icon"> <img src="{{ Auth::user()->avatar }}"> </div>
				<div class="si-top">
					<div class="si-name"> {{ $category->category }} - {{ $category->name }} </div>
					<div class="si-price"> 
						${{ number_format($category->price_min, 0, ',', '.') }} - ${{ number_format($category->price_max, 0, ',', '.') }} 
					</div>
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
@stop