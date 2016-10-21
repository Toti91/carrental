<div id="ticket">
	<div class="ticket-header">
		@if($assigned)
			<div class="ticket-assigned-user">
				<div class="tau-avatar"> <img src="{{ $assigned->avatar }}"> </div>
				<div class="tau-name"> {{ $assigned->name }} </div> <br>
				<small>Assigned to</small>
			</div>
		@else 
			<div class="ticket-assigned-user">
				<a href="/admin/tickets/assign/{{ $ticket->id }}">Assing ticket to user <i class="fa fa-link"></i></a>
			</div>
		@endif
		<div class="ticket-actions">
			<a href="/admin/tickets/edit/{{ $ticket->id }}"><i class="fa fa-edit"></i> Edit</a> <br>
			<a href="/admin/tickets/remove/{{ $ticket->id }}"><i class="fa fa-remove"></i> Remove</a>
		</div>
	</div>
	<div class="ticket-subject">
		<div class="ticket-priority">
			<a href="/admin/tickets/setstatus/{{ $ticket->id }}/1" class="@if($ticket->status == 1) active @endif  statusButton">High</a> 
			<a href="/admin/tickets/setstatus/{{ $ticket->id }}/2" class="@if($ticket->status == 2) active @endif  statusButton">Medium</a> 
			<a href="/admin/tickets/setstatus/{{ $ticket->id }}/3" class="@if($ticket->status == 3) active @endif  statusButton">Low</a> 
			<a href="/admin/tickets/setstatus/{{ $ticket->id }}/4" class="@if($ticket->status == 4) active @endif  statusButton">Finished</a> 
		</div>
		<h3> {{ $ticket->subject }} </h3>
	</div>
	<div class="ticket-message">
		<p>
			{!! nl2br(e($ticket->description)) !!}
		</p>
	</div>

	<div class="ticket-comments">
		@foreach($comments as $comment)
			<?PHP $commentUser = $comment->user; ?>

			<div class="comment">
				<div class="comment-user">
					<div class="cu-avatar">
						<img src="{{ $commentUser->avatar }}">
					</div>
					<div class="cu-name"> {{ $commentUser->name }} </div>
				</div>
				<br>
				<div class="comment-message">
					<p> {{ $comment->comment }} </p>
				</div>
				<div class="clear"></div>
			</div>
		@endforeach
	</div>
	<div class="new-comment">
		<input type="text" name="new-comment" class="input-default" id="new-comment" placeholder="Add comment...">
	</div>
</div>