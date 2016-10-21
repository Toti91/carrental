<?PHP $active_section = 'dealership'; ?>

@extends('layouts.app')

@section('title')
    CRM | Dealership
@stop

@section('content')
<div class="car-sale-window">
    <i class="fa fa-remove close-csw"></i>
    <div class="csw-content"></div>
</div>
<div class="container">
    <div id="dealership">
        @foreach($cars as $car)
            @if($car->status == 'enabled')
                <div class="single-car" id="{{ $car->id }}">
                    <div class="car-popularity">{{ $car->popularity }}<small>%</small> </div>
                    <div class="car-image category-{{ $car->category }}"> <img src="useruploads/cars/icon/{{ $car->image }}"> </div>
                    <div class="car-info">
                        <div class="car-category">{{ $car->category }}</div>
                        <h3> {{ $car->name }} </h3>
                        <small>${{ number_format($car->price, 0, ',', '.') }}</small>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        var blackout = $('#blackout'),
            content = $('.csw-content'),
            container = $('.car-sale-window');
        $('.single-car').click(function(){
            var id = $(this).attr('id');
            $.post('/getcarinfo/'+id, { _token: "{{ csrf_token() }}" }, function(data) {
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
