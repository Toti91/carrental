<?PHP $active_section = 'garage'; ?>

@extends('layouts.app')

@section('title')
    CRM | Garage
@stop

@section('content')
<script>
function getTimeRemaining(endtime){
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor( (t/1000) % 60 );
  var minutes = Math.floor( (t/1000/60) % 60 );
  var hours = Math.floor( (t/(1000*60*60)) );
  var days = Math.floor( t/(1000*60*60*24) );
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}
function initializeClock(id, endtime){
  var clock = document.getElementById(id);
  var timeinterval = setInterval(function(){
    var t = getTimeRemaining(endtime);
    clock.innerHTML = '<i class="fa fa-clock-o"></i> '+ t.hours + ':' +
                      '' + t.minutes + ':' +
                      '' + t.seconds;
    if(t.total<=0){
      clearInterval(timeinterval);
    }
  },1000);
}
</script>
<div class="car-sale-window">
    <i class="fa fa-remove close-csw"></i>
    <div class="csw-content"></div>
</div>
<div class="container">

    <table id="garage">
        <tr>
            <th></th>
            <th>Popularity</th>
            <th>Category</th>
            <th>Name</th>
            <th>Value</th>
            <th>Maintenance</th>
            <th>Hours</th>
            <th style="text-align:center;">Parked</th>
        </tr>
        @foreach($cars as $car)
            @if($car->status == 'enabled')
                <tr>
                    <td class="car-image category-{{ $car->category }}"> <img src="useruploads/cars/icon/{{ $car->image }}"> </td>
                    <td class="car-popularity">{{ $car->popularity }}<small>%</small> </td>
                    <td class="car-category">{{ $car->category }}</td>
                    <td class="car-name single-car" id="{{ $car->id }}" rentid="{{ $car->rent_id }}"> 
                        {{ $car->name }} <small>{{ $car->plate }}</small>
                    </td>
                    <td class="car-price">${{ number_format($car->new_price, 0, ',', '.') }}</td>
                    <td class="car-maintenance">
                        @if($car->maint_count >= 100)
                            <a href="/maintenance/{{ $car->rent_id }}">
                                <i class="fa fa-wrench tooltip" data-position="top" data-delay="10" data-tooltip="Requires maintenance"></i>
                            </a>
                        @else 
                            <i class="fa fa-check"></i>
                        @endif
                    </td>
                    <td class="car-hours">{{ $car->km_count }}</td>
                    <td class="car-parked">
                        @if($car->end < time() || !$car->rented)
                            <?PHP 
                                \App\userCar::resetCar($car->rent_id);
                            ?>
                            <a class="rent_link" href="/rentcar/{{ $car->rent_id }}">Rent</a>
                        @else 
                            <div id="clock-{{$car->rent_id}}"></div>
                            <script type="text/javascript">
                                initializeClock('clock-{{$car->rent_id}}', '{{ date("Y-m-d H:i:s", $car->end) }}');
                            </script>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </table>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        var blackout = $('#blackout'),
            content = $('.csw-content'),
            container = $('.car-sale-window');
        $('.single-car').click(function(){
            var id = $(this).attr('id'),
                rentId = $(this).attr('rentid');
            $.post('/getcarinfo/'+id+'/'+rentId, { _token: "{{ csrf_token() }}" }, function(data) {
                blackout.fadeIn(100);
                content.html(data);
                container.delay(100).slideDown(200);
            });
        });
        $('.close-csw').click(function(){
            container.slideUp(200);
            blackout.fadeOut(200);
        });
    });
</script>
@endsection
