@extends('layouts.auth')
@section('login_heading')
    <h2>{{app()->master_user->name}}</h2>
@stop
@section('content')
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
      <div class="form-group">
          {{ Former::checkbox('remember_me')
              ->label('')
              ->text('Remember Me')
            }}
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <button type="submit" class="btn btn-primary btn-block login-submit-btn">Login</button>
          <!-- <button type="submit" class="btn btn-primary btn-block login-submit-btn">Create New Account</button> -->
        </div>
      </div>
      <div class="form-actions form-group">
      </div>
  {{Former::close()}}
@stop
