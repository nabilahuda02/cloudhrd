@extends('layouts.module')
@section('content')

<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')

  <div class="col-md-12">
    <div >
      <h4>Change Password</h4>
      <div class="clearfix"></div>
    </div>
    <div style="padding:15px;">
      {{ Former::vertical_open('/wall/change-password')
        -> method('POST') }}

      {{ Former::password('password')
          ->label('New Password')
          ->required() }}

      {{ Former::password('confirm_password')
          ->label('Confirm New Password')
          ->required() }}

      <div class="form-group">
        <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
          <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
        </div>
      </div>
      <div class="clearfix"></div>
      {{ Former::close() }}
    </div>

  </div>
</div>
@stop