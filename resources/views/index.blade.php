@extends('layouts.front')

@section('title')
    Car Rental Manager
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    Your Application's Landing Page. <br>
                    <a href="/login">Login</a><br>
                    <a href="/register">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
