@extends('layouts.auth')
@section('login_heading')
    <h2>Forgot Password</h2>
@stop
@section('content')

@if ( Session::get('error') )
    <div class="alert alert-error alert-danger">{{{ Session::get('error') }}}</div>
@endif

@if ( Session::get('notice') )
    <div class="alert alert-info">{{{ Session::get('notice') }}}</div>
@endif
<form method="POST" action="{{ (Confide::checkAction('AuthController@doForgotPassword')) ?: URL::to('/user/forgot') }}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">

    <div class="form-group">
        <label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
        <div class="input-append input-group">
            <input class="form-control" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
            <span class="input-group-btn">
                <input class="btn btn-primary" type="submit" value="{{{ Lang::get('confide::confide.forgot.submit') }}}">
            </span>
        </div>
    </div>
</form>
<p class="text-center">
    {{link_to_action('AuthController@login', 'Login')}} |
    {{link_to_action('AuthController@create', 'Register')}}
</p>
@stop
