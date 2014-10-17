@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')

  @include('booking.header', ['hide_entitlement' => true])

  <div class="col-md-12">
    <div>
      <h3>Query Room Bookings</h3>
      <div style="padding:15px">
        {{Former::open_vertical()}}
          <div class="row">
            <div class="col-xs-6">
              {{ Former::select('unit')
                -> label('Unit')
                -> selected(@$queryFormData['unit'])
                -> options(UserUnit::selectOptions()) }}
            </div>
            <div class="col-xs-6">
              {{ Former::select('user_id')
                  -> label('User')
                  -> selected(@$queryFormData['user_id'])
                  -> options(User::selectOptions(GeneralClaim__Main::$moduleId)) }}
            </div>
          </div>
          {{Former::populate(@$queryFormData)}}
          <div class="row">
            <div class="col-xs-6">
              {{ Former::text('purpose')
                  -> label('Search Purpose') }}
            </div>
            <div class="col-xs-6">
              {{ Former::select('status_id')
                  -> label('Status')
                  -> options(Status::selectOptions()) }}
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <label for="">Booking Date On:</label>
            </div>
            <div class="col-xs-6">
              {{ Former::input('booking_from_date')
                -> type('date')
                -> value(date('Y-m-01'))
                -> label('From Date') }}
            </div>
            <div class="col-xs-6">
              {{ Former::input('booking_to_date')
                -> type('date')
                -> label('To Date') }}
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <label for="">Claims Created On:</label>
            </div>
            <div class="col-xs-6">
              {{ Former::input('create_from_date')
                  -> type('date')
                  -> label('From Date') }}
            </div>
            <div class="col-xs-6">
              {{ Former::input('create_to_date')
                  -> type('date')
                  -> label('To Date') }}
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6">
              {{ Former::select('tabulate_by')
                  -> label('Tabulate By')
                  -> options([
                      '' => 'Select One',
                      'status_id' => 'Status',
                      'user_id'  => 'User',
                      'users.unit_id'  => 'Unit',
                      'year(room_bookings.booking_date), month(room_bookings.booking_date)' => 'Month',
                    ]) }}
            </div>
            <div class="col-xs-6">
              {{ Former::select('room_booking_room_id')
                -> label('Room')
                -> options(RoomBooking__Room::selectOptions()) }}
            </div>
          </div>
          <hr>
          <br>
          <button class="btn btn-primary">Run Query</button>
          <input class="btn btn-primary" type="submit" name="download" value="Download">
        {{Former::close()}}
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <br>
  
  @if(isset($tables))

  <div class="col-md-12">
    <div class="innerLR border-bottom">
      <div id="leave_type_rows">
        <h3>
          Room Bookings Query Results
        </h3>
        <hr>
        @foreach ($tables as $table)
          <br>
          <h4>
            {{$table['title']}}
          </h4>
          <br>
          @include('booking.admin.reporting-table', ['datas' => $table['data'], 'showaction' => true])
          <hr>
          <br>
        @endforeach
      </div>
    </div>
  </div>

  @endif
@stop
@section('script')
<script>
    $('[name="download"]').click(function(e){
      $('form').attr('target', '_blank');
      setTimeout(function() {
        $('form').removeAttr('target');
      }, 100);
    });
    // var table = tbl;
    // table.columns().eq( 0 ).each( function ( colIdx ) {
    //   $( 'input[type=text]', table.column(colIdx).footer() ).on('keyup change', function () {
    //     table
    //       .column( colIdx )
    //       .search( this.value )
    //       .draw();
    //   });
    //   $( 'select', table.column(colIdx).footer() ).on('change', function () {
    //     table
    //       .column( colIdx )
    //       .search( this.value )
    //       .draw();
    //   });
    // });
</script>
@stop