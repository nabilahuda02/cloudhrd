@if($leave->canDelete())
  <button type="button" class="delete btn btn-large pull-right"><i class="fa fa-trash-o"></i> Delete</button>
@endif

@if($leave->canEdit())
  <a href="{{action('LeaveController@edit',array($leave->id))}}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Edit</a>
@endif

@if($leave->canCancel())
  <button type="button" class="status_cancel btn btn-large pull-right"><i class="fa fa-times"></i> Cancel</button>
@endif

@if($leave->canReject())
  <button type="button" class="status_reject btn btn-large pull-right"><i class="fa fa-times"></i> Reject</button>
@endif

@if($leave->canApprove())
  <button type="button" class="status_approve btn btn-large pull-right"><i class="fa fa-check"></i> Approve</button>
@endif

@if($leave->canVerify())
  <button type="button" class="status_verify btn btn-large pull-right"><i class="fa fa-check"></i> Verify</button>
@endif

@if($leave->canView())
  <a href="{{action('leave.show', $leave->id)}}" class="btn btn-primary btn-large pull-right"><i class="fa fa-eye"></i> View</a>
@endif