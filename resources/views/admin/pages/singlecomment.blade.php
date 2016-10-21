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