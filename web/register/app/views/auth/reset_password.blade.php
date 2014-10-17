@extends('layouts.auth')
@section('login_heading')
    <h2>Reset Password</h2>
@stop
@section('content')
<form method="POST" action="{{{ (Confide::checkAction('AuthController@doResetPassword'))    ?: URL::to('/user/reset') }}}" accept-charset="UTF-8">
    <input type="hidden" name="token" value="{{{ $token }}}">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">

    <div class="form-group">
        <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
        <input class="form-control" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
    </div>
    <div class="form-group">
        <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
        <input class="form-control" placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation">
    </div>

    @if ( Session::get('error') )
        <div class="alert alert-error alert-danger">{{{ Session::get('error') }}}</div>
    @endif

    @if ( Session::get('notice') )
        <div class="alert alert-info">{{{ Session::get('notice') }}}</div>
    @endif

    <div class="form-actions form-group">
        <button type="submit" class="btn btn-primary btn-block">{{{ Lang::get('confide::confide.forgot.submit') }}}</button>
    </div>
</form>
@stop
