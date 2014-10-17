@extends('layouts.default')
@section('content')
    <h2>New User</h2>
    <hr>
    {{ Former::open(action('users.store')) }}
        <div class="form-group">
            <label for="domain" class="control-label col-lg-2 col-sm-2">Domain</label>
            <div class=" col-lg-10 col-sm-10">
                <div class="input-group">
                  <input required class="form-control" type="text" name="domain" id="domain" value="{{{ Input::old('domain') ? Input::old('domain') : Input::get('domain') }}}">
                  <span class="input-group-addon">.cloudhrd.com</span>
                </div>
                <div class="help-block">This domain will be used the user's admin to log in.</div>
            </div>

        </div>
        @include('users.form')
        {{ Former::password('password')
            ->required() }}
        {{ Former::password('password_confirmation')
            ->label('Confirm Password')
            ->required() }}
        @include('users.actions-footer', ['has_submit' => true])
@stop
@section('scripts')
    <script>
    $('form').submit(function(){
        var roles = $('#roles').val();
        var domain = $('#domain');
        if(roles.indexOf('7') > -1) {
            if(!domain.val().trim()) {
                $.notify('Domain is required if creating a customer.', 'danger');
                return false;
            }
        } else {
            domain.val('');
        }
    });
    </script>
@stop