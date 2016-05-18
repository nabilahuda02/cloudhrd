@extends('layouts.auth')
@section('content')
    <section class="col-md-4 col-md-offset-4">
        <br>
        <br>
        <br>
        <h1>{{app()->master_user->name}}</h1>
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
        <hr>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
        <a href="{{action('AuthController@getForgotPassword')}}" class="btn btn-link btn-block">Forgot Password</a>
        {{Former::close()}}
    </section>
@stop
