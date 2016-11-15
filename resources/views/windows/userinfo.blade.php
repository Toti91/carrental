<i class="fa fa-remove close-ui"></i>
<div class="rental-image"> 
	<div class="user-image"> <img src="{{ $user->avatar}}"> </div>
	<img src="/useruploads/rentals/{{ $user->rental_image }}">
</div>
<div class="user-name"> {{ $user->name }} </div>
<div class="rental-info">
	<div class="rental-name"> <i class="fa fa-id-card-o"></i> {{ $user->rental }} </div>
	<div class="fallow-user">
			@if(\App\Follow::ifFollower(\Auth::user()->id, $user->id))
                <a href="/follow/{{ $user->id }}" class="trigger-follow unfollow"><i class="fa fa-user-times"></i> Unfollow</a>
            @else
                <a href="/follow/{{ $user->id }}" class="trigger-follow"><i class="fa fa-user-plus"></i> Follow</a>
            @endif
	</div>
	<div class="car-stats grid">
		<div class="grid-25 single-stat">
			<small>Total hours</small>
			 {{ \App\userCar::getUserTotalHours($user->id) }}
		</div>
		<div class="grid-25 single-stat">
			<small>Total cars</small>
			 {{ \App\userCar::getUserTotalCars($user->id) }}
		</div>
		<div class="grid-25 single-stat">
			<small>Stock value</small>
			${{ number_format($user->stock, 2, ',', '.') }}
		</div>
		<div class="grid-25 single-stat">
			<small>Created</small>
			{{ date('d.M y', strtotime($user->created)) }}
		</div>
	</div>
</div>