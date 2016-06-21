<?PHP 
	$active = 'settings'; 
	//Settings
	$avarage_speed = \App\Setting::where('setting_name', '=', 'avarage_speed')->first();
?>
@extends('layouts/admin')

@section('sidebar')
	Sidebar
@stop

@section('title-bar')
	<div class="content-2 title-bar-content">
		<h3>Settings</h3>
	</div>
@stop

@section('container')
	<div class="content content-2">
		<ul id="settings-nav">
			<a href="#" id="general" class="settings-link active"><li>General Settings</li></a>
		</ul>
	</div>
	<div class="content content-8">
		<div id="general">
			<div class="single-setting">
				<form action="/admin/settings/change" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="setting" value="{{ $avarage_speed->setting_name }}">
					<div class="setting-name">
						Cars avarage speed
					</div>
					<input type="number" name="setting_value" value="{{ $avarage_speed->setting }}" class="setting-input"> kmh
					<input type="submit" id="submit" class="submit-default" value="Change">
				</form>
			</div>
		</div>
	</div>				
@stop