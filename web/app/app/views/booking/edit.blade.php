@extends('layouts.module')
@section('content')

<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')

  @include('booking.header')

  <div class="col-md-12">
    <div >
      <h4>Claim Application Form
    </div>
    <div style="padding:15px;">
      {{ Former::horizontal_open(action('RoomBookingController@update', $booking->id))
        -> id('bookingForm')
        -> rules(['name' => 'required'])
        -> method('POST') }}

      {{ Former::hidden('_method', 'PUT') }}

      {{ Former::text('ref')
        -> label('Reference')
        -> value($booking->ref)
        -> readonly()
        -> disabled() }}

      {{ Former::text('user_id')
        -> label('Employee')
        -> value(User::fullName($booking->user_id))
        -> readonly() }}

      {{ Former::text('status_name')
        -> label('Status')
        -> value($booking->status->name)
        -> readonly()
        -> disabled() }}

      {{Former::populate($booking)}}

      @include('booking.form')

      {{ Former::textarea('remarks') 
        -> value($booking->remarks) }}

      <div class="form-group">
        <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">

          @include('booking.actions-buttons')
        
          <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">

        </div>
      </div>
      {{ Former::close() }} 
    </div>

  </div>

</div>


@stop
@section('script')

  @include('booking.actions-scripts')

@stop