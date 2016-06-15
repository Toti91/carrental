<?PHP $active = 'cars'; ?>
@extends('layouts.admin')

@section('overlay-nav')
	<a href="#" class="overlay-link active-li" id="cars"><li>Cars</li></a>
	<a href="#" class="overlay-link" id="categories"><li>Categories</li></a>
@stop

@section('overlay-content')
	<div class="overlay-content" id="cars">
		Cars form
	</div>
	<div class="overlay-content" id="categories">
		categories
	</div>
@stop


@section('container')
	Cars
@stop