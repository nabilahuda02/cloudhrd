@extends('layouts.module')
@section('content')

<div class="col-md-10 col-sm-8">

  @include('booking.header')

  <div class="col-md-12">
    <div >
      <h4>Claim Application Form
    </div>
    <div style="padding:15px;">
      {{ Former::horizontal_open(action('RoomBookingController@store'))
        -> id('bookingForm')
        -> rules(['name' => 'required'])
        -> method('POST') }}

      {{ Former::text('ref')
        -> label('Reference')
        -> value($booking->ref)
        -> readonly()
        -> disabled() }}

      {{ Former::text('user_id')
        -> label('Employee')
        -> value(Helper::userName($booking->user_id))
        -> readonly()
        -> disabled() }}

      {{ Former::text('status')
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
        </div>
      </div>
      {{ Former::close() }} 
    </div>
  </div>
</div>

@stop
@section('script')

  @include('booking.actions-scripts')

  <script>
      
      
      $('input:not([type=hidden],[type=search]),select,textarea').attr({
        readonly: true,
        disabled: true
      });
      $('#bookingForm').on('submit',function(e){
        e.preventDefault();
        return false;
      });
  </script>
@stop