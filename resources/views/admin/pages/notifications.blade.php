@if($notifications)
	@foreach($notifications as $noti)
		<div class="single-notification @if(!$noti->seen) noti-unseen  @endif">
			<div class="noti-icon">
				<div class="hexagon {{ $noti->color }}">
					<i class="fa {{ $noti->icon }}"></i>
				</div>
			</div>
			<p>
				{!! ( new \App\NotificationService())->desipherMessage($noti->id); !!}
			</p>
		</div>
	@endforeach
@else
	<small>No notifications!</small>
@endif