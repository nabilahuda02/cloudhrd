@if($changerequest->canDelete())
  <button type="button" class="delete btn btn-large btn-danger pull-right"><i class="fa fa-trash-o"></i> Delete</button>
@endif

@if($changerequest->canCancel())
  <button type="button" class="status_cancel btn btn-large pull-right"><i class="fa fa-times"></i> Cancel</button>
@endif

@if($changerequest->canReject())
  <button type="button" class="status_reject btn btn-large pull-right"><i class="fa fa-times"></i> Reject</button>
@endif

@if($changerequest->canApprove())
  <button type="button" class="status_approve btn btn-large pull-right"><i class="fa fa-check"></i> Approve</button>
@endif

@if($changerequest->canVerify())
  <button type="button" class="status_verify btn btn-large pull-right"><i class="fa fa-check"></i> Verify</button>
@endif
