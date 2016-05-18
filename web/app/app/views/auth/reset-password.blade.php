@extends('layouts.auth')
@section('content')
    <section class="col-md-4 col-md-offset-4">
        <br>
        <br>
        <br>
        <h1>{{app()->master_user->name}}</h1>
        <h4>Reset Password</h4>
        <hr>
        @include('html.notifications')
        {{Former::open(action('AuthController@postResetPassword', $token))}}
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
        <hr>
        <button type="submit" class="btn btn-primary btn-block">Send Reset Password Link</button>
        <a href="{{action('AuthController@getLogin')}}" class="btn btn-link btn-block">Back To Login</a>
        {{Former::close()}}
    </section>
@stop
@section('script')
    <script type="text/javascript">
        $('#password').keyup(function(e){
            $('#password_confirmation').attr('pattern', e.target.value);
        })
    </script>
@stop
