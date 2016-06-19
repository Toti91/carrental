<?PHP 
	$user = Auth::user(); 
?>
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
	<!-- Overlay / Module -->
	<div class="overlay-shadow"></div>
	<div class="overlay">
		<div class="overlay-nav">
			<div class="close-overlay"> <i class="fa fa-remove"></i> </div>
			<ul id="overlay-nav">
				@yield('overlay-nav')
			</ul>
		</div>
		<div class="overlay-container">
			@yield('overlay-content')
		</div>
	</div>

	<!-- Flash messages -->

	@if(session()->has('flash_success'))
		<div class="alert success-alert">
			{{ session()->get('flash_success') }}
		</div>
	@endif
	@if(session()->has('flash_error'))
		<div class="alert error-alert">
			{{ session()->get('flash_error') }}
		</div>
	@endif
	@if(session()->has('flash_info'))
		<div class="alert info-alert">
			{{ session()->get('flash_info') }}
		</div>
	@endif

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
				<!-- These show as needed -->
				<a class="user-action-button" href="#"><i class="fa fa-question"></i></a>
				<a class="user-action-button" href="#"><i class="fa fa-search"></i></a>
				<a class="user-action-button add-button" href="#"><i class="fa fa-plus"></i></a>
				<!-- These alway show on header -->
				<a class="user-action-button" href="#" id="notification-toggle">
					<i class="fa fa-bell-o"></i>
				</a>
					<div id="notifications">
						<div class="noti-header"> Notifications </div>
						<div class="noti-content">
							
						</div>
					</div>
				<a class="user-action-button user-avatar" href="#"> <img src="{{ $user->avatar }}"> </a>
				<div class="user-name">{{ $user->name }}</div>
			</div>
		</div>
		<div id="title-bar">
			@yield('title-bar') 
		</div>

		@yield('container')
	</div>

	<script>
		// Menu button / Navigation animation
		$(document).ready(function(){
			$('.menu-button').click(function(){
				var sidebar = $('#sidebar'),
					container = $('#container'),
					names = $('.wide-sidebar'),
					logo = $('.logo');

				if(names.is(":visible")){
					logo.slideUp(function(){
						sidebar.animate({
						    width: '52px'
						}, 300);
						container.animate({
						    paddingLeft: '52px'
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
					container.animate({
						    paddingLeft: '220px'
						}, 300);
				}
			});

			$('.add-button').click(function(){
				showOverlay();
			});

			$('.close-overlay').click(function(){
				closeOverlay();
			});

			var contents = $('.overlay-content'),
				links = $('.overlay-link');

			links.click(function(){
				var active = $(this).attr('id'),
					content = $('#'+active+'.overlay-content');

				links.removeClass('active-li');
				$(this).addClass('active-li');
				contents.slideUp(function(){
					content.slideDown();
				});
			});

			function closeOverlay(){
				var shadow = $('.overlay-shadow'),
					overlay = $('.overlay'),
					content = $('.overlay-content');

				content.fadeOut();
				overlay.slideUp(function(){
					shadow.hide();
				});
			}

			function showOverlay(){
				var shadow = $('.overlay-shadow'),
					overlay = $('.overlay'),
					active = $('.active-li').attr('id'),
					content = $('#'+active+'.overlay-content'),
					contents = $('.overlay-content'),
					links = $('overlay-link');

				shadow.show();;
				overlay.slideDown(function(){
					content.fadeIn();
				});
			}

			function getNotifications(){
				$.post( "/admin/notifications/get", { _token: '{{ csrf_token() }}' } , function(data) {
					$('.noti-content').html(data);
					$.post( "/admin/notifications/update", { _token: '{{ csrf_token() }}' } , function(data) {
						checkUnseen();
					});
				});

				return 'success';
			}

			function checkUnseen(){
				$.post( "/admin/notifications/unseen", { _token: '{{ csrf_token() }}' } , function(data) {
					console.log(data);
					if(data != 0){
						$('#notification-toggle').addClass('unseen-notification');
					} else {
						$('#notification-toggle').removeClass('unseen-notification');
					}
				});
			}

			setInterval(checkUnseen, 10000);

			checkUnseen();

			//Notification toggle 
			$('#notification-toggle').click(function(){
				if($('#notifications').is(':hidden')){
					getNotifications();
					$('#notifications').show();

				} else {
					$('#notifications').hide();
				}
				return false;
			});
		});

		$(document).ready(function(){
			// Alert / Flash messages
		    if($('.alert').is(':visible')){
		        $('.alert').delay(3000).fadeOut();
		    }
		});
	</script>
	@yield('script')
</body>
</html>