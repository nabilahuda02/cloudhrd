@extends('layouts.auth')
@section('content')
    <section class="col-md-4 col-md-offset-4">
        <br>
        <br>
        <br>
        <h1>{{app()->master_user->name}}</h1>
        <h4>Forgot Password</h4>
        <hr>
        @include('html.notifications')
        {{Former::open(action('AuthController@postForgotPassword'))}}
        <div class="form-group">
            {{ Former::text('email')
                ->label('Email')
                ->class('form-control')
                ->placeholder('Email')
                ->id('newLoginFormUser')
                ->help('We will send an email to this address with a link to reset your password')
                ->required() }}
        </div>
        <hr>
        <button type="submit" class="btn btn-primary btn-block">Send Reset Password Link</button>
        <a href="{{action('AuthController@getLogin')}}" class="btn btn-link btn-block">Back To Login</a>
        {{Former::close()}}
    </section>
@stop
