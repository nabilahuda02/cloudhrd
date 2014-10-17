@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')
  <div class="col-md-12">
    <div>
      <div class="page-header">
        @include('leaves.menu')
        <h3>Query Leaves</h3>
      </div>
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
                  -> options(User::selectOptions(Leave__Main::$moduleId)) }}
            </div>
          </div>
          {{Former::populate(@$queryFormData)}}
          <div class="row">
            <div class="col-xs-6">
              {{ Former::select('status_id')
                  -> label('Status')
                  -> options(Status::selectOptions()) }}
            </div>
            <div class="col-xs-6">
              {{ Former::select('leave_type_id')
                  -> label('Type')
                  -> options(Leave__Type::selectOptions())}}
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <label for="">Leave Taken On:</label>
            </div>
            <div class="col-xs-6">
              {{ Former::input('leave_from_date')
                  -> type('date')
                  -> value(date('Y-m-01'))
                  -> label('From Date') }}
            </div>
            <div class="col-xs-6">
              {{ Former::input('leave_to_date')
                  -> type('date')
                  -> label('To Date') }}
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <label for="">Leave Created On:</label>
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
                      'leave_type_id' => 'Leave Types',
                      'status_id' => 'Status',
                      'user_id'  => 'User',
                      'users.unit_id'  => 'Unit',
                      'year(leave_dates.date), month(leave_dates.date)' => 'Month',
                    ]) }}
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
          Leave Query Results
        </h3>
        <hr>
        @foreach ($tables as $table)
          <br>
          <h4>
            {{$table['title']}}
          </h4>
          <br>
          @include('leaves.admin.reporting-table', ['datas' => $table['data'], 'showaction' => true])
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