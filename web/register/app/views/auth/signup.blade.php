@extends('layouts.signup')
@section('login_heading')
    <h2>Sign up</h2>
@stop
@section('content')

@if ( Session::get('error') )
    <div class="alert alert-error alert-danger">
        @if ( is_array(Session::get('error')) )
            {{ head(Session::get('error')) }}
        @endif
    </div>
@endif

@if ( Session::get('notice') )
    <div class="alert alert-info">{{ Session::get('notice') }}</div>
@endif

@if($unreg = Input::get('unreg'))
    @if($unreg === 'false')
       <div class="alert alert-info">Admin email for {{ Input::get('domain') }} has not yet been confirmed.</div>
    @else
       <div class="alert alert-info">{{ Input::get('domain') }} has not yet been registered. Sign up today.</div>
    @endif
@endif

<form method="POST" action="{{{ (Confide::checkAction('AuthController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Organization Name</label>
                <input required class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name') ? Input::old('name') : Input::get('name') }}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
                <input required class="form-control" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
                <input required class="form-control" placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="domain">Domain</label>
                <div class="input-group">
                    <input required class="form-control" type="text" name="domain" id="domain" value="{{{ Input::old('domain') ? Input::old('domain') : Input::get('domain') }}}">
                    <span class="input-group-addon">.cloudhrd.com</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="reseller_code">Reseller Code</label>
                <input placeholder="If any" class="form-control" type="text" name="reseller_code" id="reseller_code" value="{{{ Input::old('reseller_code') ? Input::old('reseller_code') : Input::get('reseller_code') }}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
                <input required class="form-control" placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ Input::old('username') }}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">{{{ Lang::get('confide::confide.e_mail') }}} <small>{{ Lang::get('confide::confide.signup.confirmation_required') }}</small></label>
                <input required class="form-control" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
            </div>
        </div>
    </div>
    <div style="margin-top:20px"></div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <button type="submit" class="btn btn-primary btn-block login-submit-btn">Create New Account</button>
        </div>
    </div>
    <div class="form-actions form-group">
    </div>
</form>
@stop
