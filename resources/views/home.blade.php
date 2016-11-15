<?PHP $active_section = 'index'; ?>

@extends('layouts.app')

@section('title')
    CRM | Dashboard
@stop

@section('content')
<div class="container">
    <div class="left-content">
        
    </div>
    <div class="right-content">
        <div class="dashboard-users">
            <div class="du-nav">
                <div class="du-nav-item duni-follow active">
                    <div class="duni-icon"><i class="fa fa-user-circle-o"></i></div>
                </div>
                <div class="du-nav-item duni-stock">
                    <div class="duni-icon"><i class="fa fa-file-text-o"></i></div>
                </div>
                <div class="du-nav-item duni-search">
                    <div class="duni-icon"><i class="fa fa-search"></i></div>
                </div>
            </div>
            <div class="du-section dus-follow">
                <div class="dus-header">
                    Followed
                </div>
                <div class="dus-content">
                    @foreach($followers as $user)
                        @if(\Auth::user()->id != $user->id && $user->rental)
                            <a href="#" class="trigger-user-info" id="{{ $user->id }}">
                                <div class="single-user">
                                    <div class="su-avatar"><img src="{{ $user->avatar }}"></div>
                                    <div class="su-name">
                                        {{ $user->name }}
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div> 
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
@endsection
