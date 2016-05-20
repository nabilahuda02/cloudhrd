@extends('layouts.module')
@section('content')
<div class="col-md-12">

    @include('html.notifications')
    <div class="col-md-12">
        <div>
            <div class="page-header">
                @include('medicals.menu')
                <h3>Query Medical Claims</h3>
            </div>
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
                    -> options(User::selectOptions(MedicalClaim__Main::$moduleId)) }}
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
                    {{ Former::select('medical_claim_type_id')
                    -> label('Type')
                    -> options(MedicalClaim__Type::selectOptions())}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Treatment Date On:</label>
                </div>
                <div class="col-xs-6">
                    {{ Former::input('medical_from_date')
                    -> type('date')
                    -> value(date('Y-m-01'))
                    -> label('From Date') }}
                </div>
                <div class="col-xs-6">
                    {{ Former::input('medical_to_date')
                    -> type('date')
                    -> label('To Date') }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Claim Created On:</label>
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
                    'medical_claim_type_id' => 'Claim Types',
                    'status_id' => 'Status',
                    'user_id'  => 'User',
                    'users.unit_id'  => 'Unit',
                    'year(medical_claims.treatment_date), month(medical_claims.treatment_date)' => 'Month',
                    ]) }}
                </div>
                <div class="col-xs-6">
                </div>
            </div>
            <hr>
            <br>
            <button class="btn btn-primary">Run Query</button>
            <input class="btn btn-primary" type="submit" name="download" value="Download">
            {{Former::close()}}
        </div>
    </div>
    <div class="clearfix"></div>
    <br>

    @if(isset($tables))
    <div class="col-md-12">
        <div class="innerLR border-bottom">
            <div>
                <h3>
                Medical Claims Query Results
                </h3>
                <hr>
                @foreach ($tables as $table)
                <br>
                <h4>
                {{$table['title']}}
                </h4>
                <br>
                @include('medicals.admin.reporting-table', ['datas' => $table['data'], 'showaction' => true])
                <hr>
                <br>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
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
