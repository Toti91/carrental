<?PHP $active_section = 'garage'; ?>

@extends('layouts.app')

@section('title')
    CRM | Garage
@stop

@section('content')
<div class="car-sale-window">
    <i class="fa fa-remove close-csw"></i>
    <div class="csw-content"></div>
</div>
<div class="container">
    <table id="garage">
        <tr>
            <th></th>
            <th class="mobile-2">Popularity</th>
            <th>Name</th>
            <th class="mobile-1 center-align">Value</th>
            <th class="mobile-1 center-align">Next maint.</th>
            <th class="center-align">Status</th>
            <th class="center-align">Parked</th>
        </tr>
        @foreach($cars as $car)
            @if($car->status == 'enabled')
                <tr id="{{ $car->rent_id }}">
                    <td class="car-image category-{{ $car->category }}"> <img src="useruploads/cars/icon/{{ $car->image }}"> </td>
                    <td class="car-popularity mobile-2">{{ $car->popularity }}<small>%</small> </td>
                    <td class="car-name single-car" id="{{ $car->id }}" rentid="{{ $car->rent_id }}"> 
                        <div class="flash-car car-{{ $car->rent_id }}">
                            <span class="mobile-3">{{ $car->name }}</span> <small>{{ $car->plate }}</small>
                            @if(Auth::user()->access == 1)
                               <small> (<a href="/returncar/{{ $car->rent_id }}">return</a>)</small>
                            @endif
                        </div>
                    </td>
                    <td class="car-price  mobile-1">
                        <div class="flash-car car-{{ $car->rent_id }}">
                            <small>$</small>{{ number_format($car->new_price, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="car-hours mobile-1">
                        <div class="car-hours-container">
                            @if($car->maint_count < 100)
                                <div class="car-hours-filler" style="width:{{ $car->maint_count }}%;"></div>
                            @else
                                <div class="car-hours-filler car-hours-filler-full"></div>
                            @endif
                        </div>
                    </td>
                    <td class="car-maintenance">
                        <div class="flash-car car-{{ $car->rent_id }}">
                            <?PHP 
                                $malfunction = \App\userCar::find($car->rent_id)->malfunctions()->first();
                            ?>
                            @if($malfunction)
                                <i class="fa fa-exclamation-triangle danger-fa tooltip" data-position="top" data-delay="10" data-tooltip="{{ $malfunction->description }}"></i>
                            @elseif($car->maint_count >= 100)
                                <a class="maintain-car" id="{{ $car->rent_id }}" href="/maintenance/{{ $car->rent_id }}">
                                    <i class="fa fa-wrench meh-fa"></i>
                                </a>
                            @else 
                                <i class="fa fa-check success-fa"></i>
                            @endif
                        </div>
                    </td>
                    <td class="car-parked">
                        @if($car->end < time())
                            <?PHP 
                               // \App\userCar::resetCar($car->rent_id);
                            ?>
                            <a class="rent_link" id="{{ $car->rent_id }}" href="/rentcar/{{ $car->rent_id }}"><i class="fa fa-tachometer"></i> Rent</a>
                        @else 
                            <div class="clock" id="clock-{{$car->rent_id}}" end="{{ date("Y-m-d H:i:s", $car->end) }}" car="{{ $car->rent_id }}"></div>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </table>
        {{ $cars->links() }}
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('.clock').each(function(){
            var endTime = $(this).attr('end');
            var rentId = $(this).attr('car');
            $(this).countdown(endTime)
                .on('update.countdown', function(event) {
                  var format = '%H:%M:%S';
                  $(this).html('<i class="fa fa-clock-o"></i> '+event.strftime(format));
                })
                .on('finish.countdown', function(event) {
                    $(this).parent().html('<a class="rent_link" id="'+rentId+'" href="/rentcar/'+rentId+'"><i class="fa fa-tachometer"></i> Rent</a>');
                    updateParkedCars();
                    $('.rent_link').click(function(){
                        var rentId = $(this).attr('id');
                        rentCar(rentId);

                        return false;
                    });
                });
        });

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

                $('.fix-car-link').click(function(){
                    var rentId = $(this).attr('id');
                    repairCar(rentId);

                    return false;
                });
            });
        });

        $('.rent_link').click(function(){
            var rentId = $(this).attr('id');
            rentCar(rentId);

            return false;
        });

        function rentCar(rentId){
            var tr = $('#garage tr#'+rentId);
            $('#clock-'+rentId).html('<div class="loading"></div>');

            $.post('/rentcar/'+rentId, { _token: "{{ csrf_token() }}" }, function(data) {
                if(data.error === false){
                    tr.children('.car-price').html('<div class="flash-car car-'+rentId+'">'+data.new_value+'</div>');
                    tr.children('.car-parked').html('<div id="clock-'+rentId+'"></div>');

                    $('#clock-'+rentId).countdown(data.end_time)
                        .on('update.countdown', function(event) {
                          var format = '%H:%M:%S';
                          $(this).html('<i class="fa fa-clock-o"></i> '+event.strftime(format));
                        })
                        .on('finish.countdown', function(event) {
                            $(this).parent().html('<a class="rent_link" id="'+rentId+'" href="/rentcar/'+rentId+'"><i class="fa fa-tachometer"></i> Rent</a>');
                            updateParkedCars();
                            $('.rent_link').click(function(){
                                var rentId = $(this).attr('id');
                                rentCar(rentId);

                                return false;
                            });
                        });

                    updateAccount();
                    updateParkedCars();
                    updateStock();

                    if(data.maintenance === true){
                        tr.children('.car-maintenance').html('<div class="flash-car car-'+rentId+'"><a class="maintain-car" id="'+rentId+'" href="/maintenance/'+rentId+'"> <i class="fa fa-wrench meh-fa"></i> </a></div>');

                        $('.maintain-car').click(function(){
                            var rentId = $(this).attr('id');
                            maintainCar(rentId);

                            return false;
                        });

                        tr.children('.car-hours').children('.car-hours-container').children('.car-hours-filler').addClass('car-hours-filler-full');
                        tr.children('.car-hours').children('.car-hours-container').children('.car-hours-filler').animate({width: '100%'}, 1000);

                    }
                    else {
                        tr.children('.car-hours').children('.car-hours-container').children('.car-hours-filler').animate({width: data.maint_count+'%'}, 1000);
                    }

                    if(data.malfunction === true){
                        tr.children('.car-maintenance').html('<div class="flash-car car-'+rentId+'"><i class="fa fa-exclamation-triangle danger-fa tooltip" data-position="top" data-delay="10" data-tooltip="Breakdown!!!"></i></div>');

                        showAlert('Car broke down!', 'error-alert');
                    }
                    
                    $("div.car-"+rentId).slideUp(function(){
                        $(this).delay(650).slideDown();
                    });

                    showAlert('You\'r car was rented for '+data.time+' hours and you earned $'+data.total_rent, 'success-alert');
                }
                else {
                    showAlert(data.error_message, 'error-alert');
                }
            });
        }

        function maintainCar(rentId){
            var tr = $('#garage tr#'+rentId);

             tr.find('.car-maintenance').html('<div class="loading"></div>');

            $.post('/maintenance/'+rentId, { _token: "{{ csrf_token() }}" }, function(data) {
                if(data.error === false){
                    tr.children('.car-maintenance').html('<div class="flash-car car-'+rentId+'"><i class="fa fa-check success-fa"></i></div>');

                    tr.children('.car-hours').children('.car-hours-container').children('.car-hours-filler').removeClass('car-hours-filler-full');
                    tr.children('.car-hours').children('.car-hours-container').children('.car-hours-filler').animate({width: '0%'}, 1000);

                    updateAccount();
                    showAlert('You\'r car was maintained for $'+data.cost, 'success-alert');
                }
                else {
                    tr.children('.car-maintenance').html('<div class="flash-car car-'+rentId+'"><a class="maintain-car" id="'+rentId+'" href="/maintenance/'+rentId+'"> <i class="fa fa-wrench meh-fa"></i> </a></div>');

                    $('.maintain-car').click(function(){
                        var rentId = $(this).attr('id');
                        maintainCar(rentId);

                        return false;
                    });

                    showAlert(data.error_message, 'error-alert');
                }
            });
        }

        function repairCar(rentId){
            var tr = $('#garage tr#'+rentId);

            container.slideUp(200);
            blackout.fadeOut(200);

            tr.children('.car-maintenance').html('<div class="loading"></div>');

            $.post('/fixcar/'+rentId, { _token: "{{ csrf_token() }}" }, function(data) {
                if(data.error === false){
                    if(data.maintenance === true){
                        tr.children('.car-maintenance').html('<div class="flash-car car-'+rentId+'"><a class="maintain-car" id="'+rentId+'" href="/maintenance/'+rentId+'"> <i class="fa fa-wrench meh-fa"></i> </a></div>');

                        $('.maintain-car').click(function(){
                            var rentId = $(this).attr('id');
                            maintainCar(rentId);

                            return false;
                        });
                    } else {
                        tr.children('.car-maintenance').html('<div class="flash-car car-'+rentId+'"><i class="fa fa-check success-fa"></i></div>');
                    }
                    updateAccount();
                    showAlert('You\'r car was fixed for $'+data.cost, 'success-alert');
                }
                else {
                    showAlert(data.error_message, 'error-alert');
                }
            });

            return false;
        }

        $('.maintain-car').click(function(){
            var rentId = $(this).attr('id');
            maintainCar(rentId);

            return false;
        });

        $('.close-csw').click(function(){
            container.slideUp(200);
            blackout.fadeOut(200);
        });
    });
</script>
@endsection
