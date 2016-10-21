<?PHP 
	$afford = $rental->money / $car->price;
?>
<div class="csw-image category-{{ $car->category }}">
	<div class="csw-popularity"> {{ $car->popularity }}<small>%</small> </div>
	<div class="csw-category"> {{ $car->category }} </div>
	<img src="/useruploads/cars/{{ $car->image }}">
</div>
<div class="csw-info">
	<div class="csw-name">
		{{ $car->name }}
	</div>
	<div class="csw-price">
		${{ number_format($car->price, 0, ',', '.') }}
	</div>
	<div class="csw-price tooltip" data-position="top" data-delay="10" data-tooltip="Every 100 hours.">
		Maintenance cost:
		${{ number_format($car->maint_cost, 0, ',', '.') }}
	</div>
	@if(isset($rent_car))
		<div class="sell-car">
			<div class="sell-car-title">Sell</div>
			<div class="clear"></div>
			@if($rent_car->end < time() || !$rent_car->rented)
				<a href="/sellcar/{{ $rent_car->id }}" class="sell-button">Sell for ${{ number_format($rent_car->price, 0, ',', '.') }}</a>
			@else
				You can only sell the car when it is not leased
			@endif
		</div>
	@endif
	<div class="csw-buy">
		<h3> How many you want to buy? </h3>
		<ul>
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
</div>
<div class="clear"></div>
<script>
	$('.tooltip').tooltip({delay: 50});
</script>