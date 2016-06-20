<?PHP $active = 'index'; ?>
@extends('layouts/admin')

@section('sidebar')
	Sidebar
@stop

@section('title-bar')
	<div class="content-5 title-bar-content">
		<h3>Users</h3>
	</div>
	<div class="content-5 title-bar-content">
		<h3>Cars</h3>
	</div>
@stop

@section('container')
	<table class="stats-table">
		<tr>
			<td>{{ $users->count() }}</td>
			<td>{{ $cars->count() }}</td>
		</tr>
	</table>
@stop