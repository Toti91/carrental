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
    <link rel="stylesheet" href="/css/frontPage.css">

</head>
<body id="app-layout">
	<header>
		<div id="header-links">
			<div class="facebook-login">
				<a href="redirect" class="waves-effect waves-light btn blue darken-4 right"><small>Login with</small> Facebook</a>
			</div>
		</div>
		<div id="logo">
			Car Rental <br>
			<i class="fa fa-car"></i>
			<small>Manager</small>
		</div>
	</header>
	<div id="wrapper">
		<div class="parallax-container">
		<h1 class="parallax-text">Manage your own <br> car rental. <br> <small>Join now!</small></h1>
	      <div class="parallax"><img src="images/car.jpg"></div>
	    </div>

	    <div id="content" style="min-height:800px;">
	   		@yield('content')
	    </div>
    </div>
    <script>
   	$(document).ready(function(){
      $('.parallax').parallax();
    });
   </script>
</body>
</html>
