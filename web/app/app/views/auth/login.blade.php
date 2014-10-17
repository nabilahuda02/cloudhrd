@extends('layouts.auth')
@section('content')
    <section class="col-md-4 col-md-offset-4">
        <br>
        <br>
        <br>
        <h1>CloudHRD</h1>
        <h4>Login to your Account</h4>
        <hr>
        @include('html.notifications')
        {{Former::open(action('AuthController@postLogin'))}}
        <div class="form-group">
            {{ Former::text('email')
                ->label('Email')
                ->class('form-control')
                ->placeholder('Email')
                ->id('newLoginFormUser')
                ->required() }}
        </div>
        <div class="form-group">
            {{ Former::password('password')
                ->label('Password')
                ->class('form-control')
                ->placeholder('Password')
                ->id('newLoginFormPass')
                ->required() }}
        </div>
        <button type="submit" class="btn btn-blue btn-block">Login</button>
        {{Former::close()}}
    </section>
@stop