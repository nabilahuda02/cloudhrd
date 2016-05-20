@extends('layouts.module')
@section('content')
<div class="col-md-12">

  @include('html.notifications')

  <div class="col-md-12">
    <div>
      <div id="leave_type_rows">
        <div class="page-header">
          @include('leaves.menu')
          <h3>Blocked Dates</h3>
        </div>
        <div class="row">
          <div class="col-md-6">
            {{ Former::text('name')
            -> placeholder('Hari Raya') }}
          </div>
          <div class="col-md-6">
            {{ Former::input('date')
              -> type('date')
              -> value(date('Y-m-d')) }}
          </div>
          <div class="clearfix"></div>
          <br>
        </div>
        <hr>
        <br>
      </div>
    </div>
  </div>

@stop
@section('script')
  <script>

      $('#leave_type_rows').duplicator({
        row: ".row",
        remotes: {
          post: '/blockeddates',
          put: '/blockeddates',
          delete: '/blockeddates',
          get: '/blockeddates'
        }
      });
  </script>

@stop
