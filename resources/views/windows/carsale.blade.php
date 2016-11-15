<?PHP 
	$afford = $rental->money / $car->price;
?>
<div class="csw-info">
	<div class="csw-name">
		{{ $car->name }} @if(isset($rent_car)) <small>{{ $rent_car->plate }}</small>  @endif
	</div>
	<div class="csw-image category-{{ $car->category }}">
		<div class="csw-popularity"> {{ $car->popularity }}<small>%</small> </div>
		<div class="csw-category"> {{ $car->category }} </div>
		<img src="/useruploads/cars/{{ $car->image }}">
	</div>
	<div class="clear"></div>
	<div class="grid">
		<div class="grid-50">
			<div class="csw-price">
				@if(isset($rent_car))
					${{ number_format($rent_car->price, 0, ',', '.') }} <br>
					<small>Value</small>
				@else
					${{ number_format($car->price, 0, ',', '.') }} <br>
					<small>Price</small>
				@endif
			</div>
		</div>
		<div class="grid-50">
			<div class="csw-price tooltip" data-position="top" data-delay="10" data-tooltip="Every 100 hours.">
				${{ number_format($car->maint_cost, 0, ',', '.') }} <br>
				<small>Maintenance cost</small>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	@if(isset($rent_car))
		@if($rent_car->end < time() || !$rent_car->rented)
			<a href="/sellcar/{{ $rent_car->id }}" class="sell-button">
				<div class="sell-car">
					<i class="fa fa-money"></i> <br>
					<small>Sell </small> <br> ${{ number_format($rent_car->price, 0, ',', '.') }}
				</div>
			</a>
		@endif
	@endif

	@if(isset($rent_car) && $rent_car->malfunctions()->first())
		<?PHP $malfunction = $rent_car->malfunctions()->first(); ?>
		<a class="fix-car-link" id="{{ $rent_car->id }}" href="/fixcar/{{ $rent_car->id }}" class="sell-button">
			<div class="fix-car">
				<i class="fa fa-wrench"></i> <br>
				<small>Fix Car </small> <br> ${{ number_format($malfunction->cost, 0, ',', '.') }}
			</div>
		</a>
	@endif
	<div class="clear"></div>
	@if(!isset($rent_car))
	<div class="csw-buy">
		<ul>
			<li>Buy:</li>
			<a href="/buycar/{{ $car->id }}/1" @if($afford < 1) class="disabledLink" @endif>
				<li>1</li>
			</a>
			<a href="/buycar/{{ $car->id }}/2" @if($afford < 2) class="disabledLink" @endif>
				<li>2</li>
			</a>
			<a href="/buycar/{{ $car->id }}/3" @if($afford < 3) class="disabledLink" @endif>
				<li>3</li>
			</a>
			<a href="/buycar/{{ $car->id }}/4" @if($afford < 4) class="disabledLink" @endif>
				<li>4</li>
			</a>
			<a href="/buycar/{{ $car->id }}/5" @if($afford < 5) class="disabledLink" @endif>
				<li>5</li>
			</a>
			<a href="/buycar/{{ $car->id }}/6" @if($afford < 6) class="disabledLink" @endif>
				<li>6</li>
			</a>
			<a href="/buycar/{{ $car->id }}/7" @if($afford < 7) class="disabledLink" @endif>
				<li>7</li>
			</a>
			<a href="/buycar/{{ $car->id }}/8" @if($afford < 8) class="disabledLink" @endif>
				<li>8</li>
			</a>
			<a href="/buycar/{{ $car->id }}/9" @if($afford < 9) class="disabledLink" @endif>
				<li>9</li>
			</a>
			<a href="/buycar/{{ $car->id }}/10" @if($afford < 10) class="disabledLink" @endif>
				<li>10</li>
			</a>
			<a href="/buycar/{{ $car->id }}/15" @if($afford < 15) class="disabledLink" @endif>
				<li>15</li>
			</a>
			<a href="/buycar/{{ $car->id }}/20" @if($afford < 20) class="disabledLink" @endif>
				<li>20</li>
			</a>
		</ul>
	</div>
	@endif
	@if(isset($rent_car) && $rent_car->malfunctions()->first())
		<?PHP $malfunction = $rent_car->malfunctions()->first(); ?>
		<div class="fix-car-description">
			<i class="fa fa-exclamation-triangle danger-fa"></i> <b>{{ $malfunction->name }}</b>: {{ $malfunction->description }}
		</div>
		<div class="clear"></div>
	@endif
	@if(isset($rent_car))
		<div class="car-stats grid">
			<div class="grid-25 single-stat">
				<small>Total hours</small>
				{{ $rent_car->km_count }} hours
			</div>
			<div class="grid-25 single-stat">
				<small>Next inspection</small>
				{{ 100 - $rent_car->maint_count }} hours
			</div>
			<div class="grid-25 single-stat">
				<small>Avarage Income</small>
				${{ number_format($avarageIncome, 0, ',', '.') }}
			</div>
			<div class="grid-25 single-stat">
				<small>Avarage time</small>
				{{ number_format($avarageTime, 0, ',', '.') }} hours
			</div>
		</div>
		<div class="car-history">
		<table>
			<tr>
				<th></th>
				<th>Distance</th>
				<th>Time</th>
				<th>Car Out</th>
				<th>Car In</th>
				<th>Income</th>
			</tr>
			@foreach($histories as $history)
				<tr>
					<td class="history-active">
						@if(time() < $history->end && time() > $history->start)
							<div class="history-is-active"></div>
						@endif
					</td>
					<td class="history-plate">{{ $history->time * 65 }} <small>km</small></td>
					<td class="history-time">{{ $history->time }} <small>hours</small></td>
					<td class="history-date">{{ date('d.M',$history->start) }} <small>{{ date('H:i',$history->start) }}</small></td>
					<td class="history-date">{{ date('d.M',$history->end) }} <small>{{ date('H:i',$history->end) }}</small></td>
					<td class="history-rent">${{ number_format($history->total_rent, 0, ',', '.') }}</td>
				</tr>
			@endforeach
			<tr class="history-end"></tr>
		</table>
		</div>
	@endif
</div>
<div class="clear"></div>
<script>
	$('.tooltip').tooltip({delay: 50});
</script>