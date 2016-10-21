<?PHP 
	$active = 'tickets'; 
?>
@extends('layouts.admin')

@section('overlay-nav')
	<h2>Add new ticket</h2>
@stop

@section('overlay-content')
	<div class="single-overlay-content">
		<form method="POST" action="/admin/tickets/new" class="input-default" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="email" value="{{ Auth::user()->email }}">
			<input type="text" name="subject" placeholder="Subject" class="input-default">
			<textarea name="description" class="textarea-default" placeholder="Ticket"></textarea>
			<input type="submit" id="submit-car" class="submit-default">
		</form>
	</div>
@stop

@section('title-bar')
	<div class="content-4 title-bar-content">
		<h3>Tickets</h3>
	</div>
	<div class="content-6 title-bar-content">
		<h3>View</h3>
	</div>
@stop

@section('container')
	<div class="content content-4">
		@if($tickets)
			@foreach($tickets as $ticket)
				<?php 
					$user = \App\User::where('email', '=', $ticket->email)->first(); 
					$assigned = $ticket->user();

					if($ticket->status == 0){
						$class = "si-new";
					} elseif($ticket->status == 1){
						$class = "si-high";
					} elseif($ticket->status == 2){
						$class = "si-medium";
					} elseif($ticket->status == 3){
						$class = "si-low";
					} else {
						$class = "si-finished";
					}

				?>
				<a href="#" class="ticket-link" ticketId="{{ $ticket->id }}">
					<div class="single-item {{ $class }}">
						<div class="si-icon"> 
							@if($user)
								<img src="{{ $user->avatar }}"> 
							@else 
								<i class="fa fa-user"></i>
							@endif
						</div>
						<div class="si-top">
							<div class="si-name"> {{ $ticket->subject }} </div>
						</div>
						<div class="si-bottom">
							<div class="si-description"> <p>{{ substr($ticket->description, 0, 45) }}... </p> </div>
						</div>
						<div class="clear"></div>
					</div>
				</a>
			@endforeach
		@else
			No tickets
		@endif
	</div>
	<div class="content content-6">
		<div class="ticket-content">
			
		</div>
	</div>
@stop

@section('script')
	<script>
		$(document).ready(function(){
			var links = $('.ticket-link');

			links.click(function(){
				var ticketId = ($(this).attr('ticketId'));
				loadTicket(ticketId);

				return false;
			});


			function loadTicket(id){
				var container = $('.ticket-content'),
					links = $('.ticket-link');

				links.removeClass('si-active');
				$('a[ticketId="'+id+'"]').addClass('si-active');

				$.post( "/admin/ticket/"+id, { _token: '{{ csrf_token() }}' } , function(data) {
	               	container.fadeOut(function(){
	               		container.html(data).fadeIn();
	               		var input = $('#new-comment'),
	               			userId = '{{ Auth::user()->id }}',
	               			commentContainer = $('.ticket-comments');

						input.on('keypress', function(e) {
							if(e.which == 13) {
						        $.post( "/admin/ticket/newcomment/"+id, { _token: '{{ csrf_token() }}', message: input.val(), userId: userId } , function(data) {
						        	input.val('');
						        	$(data).appendTo(commentContainer).hide().slideDown(300);	
						        	var height = $(window).height() + 10000;
						        		$('html, body').animate({ scrollTop: height }, 300);
						        });
						    }
						});
	               	});
	            });
			}

			loadTicket({{ $loadTicket }});
		});
	</script>
@stop