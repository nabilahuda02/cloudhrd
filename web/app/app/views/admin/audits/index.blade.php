@extends('layouts.module')
@section('content')
<section id="units">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Audits
                    {{-- <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button> --}}
                </h2>
            </div>
            <div class="col-sm-6 section-drop-menu" >
                {{-- @include('admin.units.menu') --}}
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                <div class="table-responsive">
                    <table data-path="audits" class="DT table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Timestamp</th>
                                <th class="text-center">Module</th>
                                <th class="text-center">Ref</th>
                                <th class="text-center">Changes</th>
                                <th class="text-center">Action By</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
<script>
    $('table.DT').on( 'click', '.viewauditdetails', function () {

      var hidden = [
      'dates',
      'upload_hash',
      'updated_at',
      'created_at',
      'clinic',
      'entries',
      'type',
      'id'
      ];

      var rename = {

      };

      var mappings = {
        'leave_type_id': {{json_encode(Leave__Type::all()->lists('name', 'id'))}},
        'user_id': {{json_encode(UserProfile::all()->lists('first_name', 'user_id'))}},
        'status_id': {{json_encode(Status::all()->lists('name', 'id'))}},
        'medical_claim_type_id' : {{json_encode(MedicalClaim__Type::all()->lists('name', 'id'))}},
        'unit_id' : {{json_encode(UserUnit::all()->lists('name', 'id'))}},
        'is_admin': {0: 'False', 1: 'True'},
        'verified': {0: 'False', 1: 'True'}
    }

    var target = $(this);

    function toTitleCase(str) {
        var str = str.replace(/[_]+/g, ' ');
        return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()});
    }

    function format ( d ) {
        var data = target.data('auditdata');

        if(typeof data.data === 'string') {
          return 'Status : ' + data.data
      } else {
          var str = '<table>';
          $.each(data.data, function(index, value){
            if(hidden.indexOf(index) === -1) {
              if(mappings[index]) {
                value = mappings[index][value];
            }
            index = toTitleCase(index).replace(' Id', '');
            str = str + '<tr><td>' + index + '</td><td>' + value + '</td></tr>';
        }
    });
          var path = '';
          switch (data.auditable_type) {
            case 'Leave__Main' :
            path = 'leave';
            break;
            case 'MedicalClaim__Main':
            path = 'medical';
            break;
            case 'GeneralClaim__Main':
            path = 'claims';
            break;
            case 'User':
            path = 'useradmin';
            break;
        }
        console.log(data)
        str = str + '<table><hr>';
        str = str + '<br/> <a href="/' + path + '/' + data.data.id + '" class="btn btn-primary">View Current</a><br/> ';
        return str;
    }
}

var detailRows = [];
var tr = target.closest('tr');
var row = tbl.row( tr );
var idx = $.inArray( tr.attr('id'), detailRows );

if ( row.child.isShown() ) {
    tr.removeClass( 'details' );
    row.child.hide();
    detailRows.splice( idx, 1 );
}
else {
    tr.addClass( 'details' );
    row.child( format( row.data() ) ).show();
    if ( idx === -1 ) {
      detailRows.push( tr.attr('id') );
  }
}
});
</script>
@stop
