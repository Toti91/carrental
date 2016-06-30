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
					<a href="#" class="active"><li>Dashbaord</li></a>
					<a href="#"><li>Garage</li></a>
					<a href="#"><li>Dealership</li></a>
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
						<small>$</small>{{ number_format($rental->money, 0, ',', '.') }}
					</div>
				</div>
				<div class="rental-status">
					<div class="rs-section rental-money tooltip" data-position="bottom" data-delay="50" data-tooltip="Stock value">
						<small>$</small>{{ number_format(750, 0, ',', '.') }}
					</div>
				</div>
				<div class="rental-status">
					<div class="rs-section rental-money tooltip" data-position="bottom" data-delay="50" data-tooltip="Parked cars">
						<small><i class="fa fa-plug"></i></small>{{ number_format(34, 0, ',', '.') }}
					</div>
				</div>
				<div class="rental-status">
					<div class="rs-section rental-money tooltip" data-position="bottom" data-delay="50" data-tooltip="Owned cars">
						<small><i class="fa fa-car"></i></small>{{ number_format(235, 0, ',', '.') }}
					</div>
				</div>
			</div>
		@endif
	    <div id="content" style="min-height:800px;">
	   		@yield('content')
	    </div>
    </div>
    <script>
   	$(document).ready(function(){
      	$('.parallax').parallax();
       	$('.tooltip').tooltip({delay: 50});
    });
   </script>
</body>
</html>
