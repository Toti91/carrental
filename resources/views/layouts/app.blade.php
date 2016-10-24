<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Compiled and minified JavaScript -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/materialize.min.js"></script>

    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="/css/app.css">

</head>
<body id="app-layout">
	<script>
		function updateAccount(){
            var div = $('#account_money');
            $.post('/updateMoney', { _token: "{{ csrf_token() }}" }, function(data) {
                div.slideUp(function(){
                	div.html(data);
                	div.slideDown();
                });
            });
        }
        function updateParkedCars(){
            var div = $('#parked_cars');
            $.post('/updateParkedCars', { _token: "{{ csrf_token() }}" }, function(data) {
                div.slideUp(function(){
                	div.html(data);
                	div.slideDown();
                });
            });
        }
	</script>
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
	<div id="blackout"></div>
	<header>
		<div id="user-navbar">
			<div class="un-avatar"> <img src="{{ Auth::user()->avatar }}"> </div>
			<div class="un-name"> {{ Auth::user()->name }} </div>
			<a href="/logout" class="logout-button"> <i class="fa fa-sign-out"></i> </a>
		</div>

		<div id="logo">
			Car Rental <br>
			<i class="fa fa-car"></i>
			<small>Manager</small>
		</div>
		@if(empty($hidenav))
			<div id="navigation">
				<ul>
					<a href="/" @if($active_section == 'index') class="active"  @endif><li>Dashbaord</li></a>
					<a href="/garage" @if($active_section == 'garage') class="active"  @endif><li>Garage</li></a>
					<a href="/dealership" @if($active_section == 'dealership') class="active"  @endif><li>Dealership</li></a>
				</ul>
			</div>
		@endif
	</header>
	<div id="wrapper">
		@if(empty($hidenav))
			<div id="rental-bar">
				<div class="rental-info">
					<div class="rental-icon">
						<img src="/useruploads/rentals/{{ $rental->icon }}">
					</div>
					<div class="rental-name">
						{{ $rental->name }} <a href="/rental/settings"><i class="fa fa-cog"></i></a>
					</div>
				</div>
				<div class="rental-status">
					<div class="rs-section rental-money tooltip" data-position="bottom" data-delay="50" data-tooltip="Account">
						<div id="account_money"><small>$</small>{{ number_format($rental->money, 0, ',', '.') }}</div>
					</div>
				</div>
				<div class="rental-status">
					<div class="rs-section rental-money tooltip" data-position="bottom" data-delay="50" data-tooltip="Stock value">
						<small>$</small>{{ number_format(750.23, 2, ',', '.') }}
					</div>
				</div>
				<div class="rental-status">
					<div class="rs-section rental-money tooltip" data-position="bottom" data-delay="50" data-tooltip="Parked cars">
						<div id="parked_cars"><small><i class="fa fa-plug"></i></small>{{ number_format(\App\userCar::countParked(), 0, ',', '.') }}</div>
					</div>
				</div>
				<div class="rental-status">
					<div class="rs-section rental-money tooltip" data-position="bottom" data-delay="50" data-tooltip="Owned cars">
						<small><i class="fa fa-car"></i></small>{{ number_format(\App\userCar::countTotal(), 0, ',', '.') }}
					</div>
				</div>
			</div>
		@endif
	    <div id="content">
	   		@yield('content')
	    </div>
    </div>
    <script>
   	$(document).ready(function(){
      	$('.parallax').parallax();
       	$('.tooltip').tooltip({delay: 50});

       	// Alert / Flash messages
		    if($('.alert').is(':visible')){
		        $('.alert').delay(3000).fadeOut();
		    }
    });
   </script>
   @yield('script');
</body>
</html>
