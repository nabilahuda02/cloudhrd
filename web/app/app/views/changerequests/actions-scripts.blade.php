@if($changerequest->canDelete())
  {{ Form::open(array('id'=>'delete_form', 'method'=>'post','action'=>array('ChangeRequestsController@destroy',$changerequest->id))) }}
    {{ Form::hidden('_method', 'DELETE')}}
  {{ Form::close() }}
  <script>
    document.querySelector('.delete').onclick = function(){
      if(confirm('Are you sure you want to delete this?')) {
        document.getElementById('delete_form').submit();
      }
    };
  </script>
@endif

@if($changerequest->canVerify())
  {{ Form::open(array('id'=>'verify_form', 'method'=>'post','action'=>array('ChangeRequestsController@update',$changerequest->id))) }}
    {{ Form::hidden('_method', 'PUT')}}
    {{ Form::hidden('status_id', '2')}}
    {{ Form::hidden('_status', 'true')}}
  {{ Form::close() }}
  <script>
    document.querySelector('.status_verify').onclick = function(){
      if(confirm('Are you sure you want to verify this?')) {
        document.getElementById('verify_form').submit();
      }
    };
  </script>
@endif

@if($changerequest->canApprove())
  {{ Form::open(array('id'=>'approve_form', 'method'=>'post','action'=>array('ChangeRequestsController@update',$changerequest->id))) }}
    {{ Form::hidden('_method', 'PUT')}}
    {{ Form::hidden('status_id', '3')}}
    {{ Form::hidden('_status', 'true')}}
  {{ Form::close() }}
  <script>
    document.querySelector('.status_approve').onclick = function(){
      if(confirm('Are you sure you want to approve this?')) {
        document.getElementById('approve_form').submit();
      }
    };
  </script>
@endif

@if($changerequest->canReject())
  {{ Form::open(array('id'=>'reject_form', 'method'=>'post','action'=>array('ChangeRequestsController@update',$changerequest->id))) }}
    {{ Form::hidden('_method', 'PUT')}}
    {{ Form::hidden('status_id', '4')}}
    {{ Form::hidden('_status', 'true')}}
  {{ Form::close() }}
  <script>
    document.querySelector('.status_reject').onclick = function(){
      if(confirm('Are you sure you want to reject this?')) {
        document.getElementById('reject_form').submit();
      }
    };
  </script>
@endif

@if($changerequest->canCancel())
  {{ Form::open(array('id'=>'cancel_form', 'method'=>'post','action'=>array('ChangeRequestsController@update',$changerequest->id))) }}
    {{ Form::hidden('_method', 'PUT')}}
    {{ Form::hidden('status_id', '5')}}
    {{ Form::hidden('_status', 'true')}}
  {{ Form::close() }}
  <script>
    document.querySelector('.status_cancel').onclick = function(){
      if(confirm('Are you sure you want to cancel this?')) {
        document.getElementById('cancel_form').submit();
      }
    };
  </script>
@endif
