@extends('layouts.auth')
@section('login_heading')
    <h2>Login</h2>
@stop
@section('content')
    @if ( Session::get('error') )
        <div class="alert alert-danger">{{{ Session::get('error') }}}</div>
    @endif

    @if ( Session::get('notice') )
        <div class="alert alert-info">{{{ Session::get('notice') }}}</div>
    @endif
    <form method="POST" action="{{{ Confide::checkAction('AuthController@doLogin') ?: URL::to('/user/login') }}}" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
        <fieldset>
            <div class="form-group">
                <label for="email">{{{ Lang::get('confide::confide.username_e_mail') }}}</label>
                <input class="form-control" tabindex="1" placeholder="{{{ Lang::get('confide::confide.username_e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
            </div>
            <div class="form-group">
            <label for="password">
                {{{ Lang::get('confide::confide.password') }}}
            </label>
            <input class="form-control" tabindex="2" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="remember" class="checkbox">
                    <input type="hidden" name="remember" value="0">
                    <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
                    {{{ Lang::get('confide::confide.login.remember') }}}
                </label>
            </div>
            <div class="form-group">
                <button tabindex="3" type="submit" class="btn btn-primary btn-block">{{{ Lang::get('confide::confide.login.submit') }}}</button>
            </div>
        </fieldset>
    </form>
    <p class="text-center">
        {{link_to_action('AuthController@forgotPassword', 'Forgot Password')}} |
        {{link_to_action('AuthController@create', 'Register')}}
    </p>
@stop
