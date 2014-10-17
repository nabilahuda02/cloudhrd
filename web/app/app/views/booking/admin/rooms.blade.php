@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')

  @include('booking.header')

  <div class="col-md-12">
    <div>
      <div id="room_type_rows">
        <h3>Rooms</h3>
        <div class="row">
          <div class="col-md-12">
            {{ Former::text('name')
            -> label('Room Name')
            -> placeholder('New Room') }}
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
      $('#room_type_rows').duplicator({
        row: ".row",
        remotes: {
          post: '/rooms',
          put: '/rooms',
          delete: '/rooms',
          get: '/rooms'
        }
      });
  </script>

@stop