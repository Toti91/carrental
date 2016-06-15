<?PHP $active = 'cars'; ?>
@extends('layouts.admin')

@section('overlay-nav')
	<a href="#" class="overlay-link" id="cars"><li class="active">Cars</li></a>
	<a href="#" class="overlay-link" id="categories"><li>Categories</li></a>
@stop

@section('overlay-content')
	<div class="overlay-content" id="cars">
		Cars
	</div>
	<div class="overlay-content" id="categories">
		categories
	</div>
@stop


@section('container')
	Cars
@stop