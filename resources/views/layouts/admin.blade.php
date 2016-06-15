<?PHP $user = Auth::user(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Car rental manager | admin</title>
	<link rel="stylesheet" type="text/css" href="/css/adminGlobal.css">
	<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>
<body>
	<!-- Sidebar -->
	<div id="sidebar">
		<div class="logo">
			Car Rental <br>
			<small>Manager</small>
		</div>
		<div class="clear"></div>

		<!-- Admin navigation -->
		<div class="siebar-nav">
			<ul id="sidebar-nav">
				<a href="/admin"><li @if($active == 'index') class="active" @endif>
					<i class="fa fa-dashboard"></i> <div class="wide-sidebar"> Dashboard </div>
				</li></a>
				<a href="/admin/cars"><li @if($active == 'cars') class="active" @endif>
					<i class="fa fa-car"></i> <div class="wide-sidebar"> Cars </div>
				</li></a>
				<a href="/admin/users"><li @if($active == 'users') class="active" @endif>
					<i class="fa fa-users"></i> <div class="wide-sidebar"> Users </div>
				</li></a>
				<a href="/admin/tickets"><li @if($active == 'tickets') class="active" @endif>
					<i class="fa fa-ticket"></i> <div class="wide-sidebar"> Tickets </div>
				</li></a>
			</ul>
		</div>
	</div>

	<!-- Main container -->
	<div id="container">
		<!-- Info/User bar -->
		<div id="info-bar">
			<div class="menu-button">
				<i class="fa fa-bars"></i>
			</div>

			<div class="user-section">
				<a class="user-action-button" href="#"><i class="fa fa-question"></i></a>
				<a class="user-action-button" href="#"><i class="fa fa-search"></i></a>
				<a class="user-action-button" href="#"><i class="fa fa-plus"></i></a>
				<a class="user-action-button" href="#"><i class="fa fa-bell-o"></i></a>
				<a class="user-action-button user-avatar" href="#"> <img src="{{ $user->avatar }}"> </a>
				<div class="user-name">{{ $user->name }}</div>
			</div>
		</div>
		<div id="title-bar">
			@yield('titlebar') 
		</div>

		@yield('container')
	</div>

	<script>
		// Menu button / Navigation animation
		$(document).ready(function(){
			$('.menu-button').click(function(){
				var sidebar = $('#sidebar'),
					names = $('.wide-sidebar'),
					logo = $('.logo');

				if(names.is(":visible")){
					logo.slideUp(function(){
						sidebar.animate({
						    width: '52px'
						}, 300);
					});
					names.fadeOut();
				} else {
					sidebar.animate({
					    width: '220px'
					  }, 300, function() {
					    logo.slideDown();
					    names.fadeIn();
					  });
				}
			});
		});
	</script>
</body>
</html>