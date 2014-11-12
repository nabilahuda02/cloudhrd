@if($medical->canDelete())
  <button type="button" class="delete btn btn-large btn-danger pull-right"><i class="fa fa-trash-o"></i> Delete</button>
@endif

@if($medical->canEdit())
  <a href="{{action('MedicalController@edit',array($medical->id))}}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Edit</a>
@endif

@if($medical->canCancel())
  <button type="button" class="status_cancel btn btn-large pull-right"><i class="fa fa-times"></i> Cancel</button>
@endif

@if($medical->canReject())
  <button type="button" class="status_reject btn btn-large pull-right"><i class="fa fa-times"></i> Reject</button>
@endif

@if($medical->canApprove())
  <button type="button" class="status_approve btn btn-large pull-right"><i class="fa fa-check"></i> Approve</button>
@endif

@if($medical->canVerify())
  <button type="button" class="status_verify btn btn-large pull-right"><i class="fa fa-check"></i> Verify</button>
@endif

@if($medical->canView())
  <a href="{{action('medical.show', $medical->id)}}" class="btn btn-primary btn-large pull-right"><i class="fa fa-eye"></i> View</a>
@endif