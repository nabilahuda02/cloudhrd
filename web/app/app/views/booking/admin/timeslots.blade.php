@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')

  @include('booking.header')

  <div class="col-md-12">
    <div>
      <div id="medical_type_rows">
        <h3>Time Slots</h3>
        <div class="row">
          <div class="col-md-4">
            {{ Former::text('name')
            -> label('Slot Name')
            -> placeholder('New Time Slot') }}
          </div>
          <div class="col-md-4">
            {{ Former::time('start')
              -> label('Start Time')
              -> label('09:00:00') }}
          </div>
          <div class="col-md-4">
            {{ Former::time('end')
              -> label('End Time')
              -> label('13:00:00') }}
          </div>
          <div class="clearfix"></div>
          <br>
        </div>
        <hr>
        <br>
      </div>
    </div>
  </div>
  {{Asset::push('js','app/duplicator/duplicator.js')}}
@stop
@section('script')
  <script>
      $('#medical_type_rows').duplicator({
        row: ".row",
        remotes: {
          post: '/timeslots',
          put: '/timeslots',
          delete: '/timeslots',
          get: '/timeslots'
        }
      });
  </script>

@stop