@extends('layouts.auth')
@section('login_heading')
    <h2>Forget Password</h2>
@stop
@section('content')

        {{Former::open(action('AuthController@postForgotPassword'))}}
        <div class="form-group">
            {{ Former::text('email')
                ->label('Email')
                ->class('form-control')
                ->placeholder('your@email.com')
                ->id('newLoginFormUser')
                ->help('We will send an email to this address with a link to reset your password')
                ->required() }}
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <button type="submit" class="btn btn-primary btn-block login-submit-btn">Send Reset Password Link</button>
            </div>
        </div>
                {{Former::close()}}
    <!-- </section> -->
    <div class="login-card-footer">
      <a href="{{action('AuthController@getLogin')}}" class="btn btn-link btn-block">Back To Login</a>
    </div>
@stop
