@extends('layouts.auth')
@section('login_heading')
    <h2>{{app()->master_user->name}}</h2>
@stop
@section('content')
    {{Former::open_vertical(action('AuthController@postResetPassword', $token))}}
    <div class="form-group">
        {{ Former::text('email')
            ->label('Email')
            ->disabled()
            ->readonly()
            ->value($user->email)
            ->class('form-control')
            ->placeholder('Email') }}
        {{ Former::password('password')
            ->label('New Password')
            ->class('form-control')
            ->minlength(6)
            ->help('Minumum length: 6 characters')
            ->required() }}
        {{ Former::password('confirm_password')
            ->label('Confirm Password')
            ->class('form-control')
            ->help('Retype the above password')
            ->required() }}
    </div>
    <br>
    <button type="submit" class="btn btn-primary btn-block">Save New Password</button>
    <a href="{{action('AuthController@getLogin')}}" class="btn btn-link btn-block">Back To Login</a>
    {{Former::close()}}
@stop
@section('script')
    <script type="text/javascript">
        $('#password').keyup(function(e){
            $('#password_confirmation').attr('pattern', e.target.value);
        })
    </script>
@stop
