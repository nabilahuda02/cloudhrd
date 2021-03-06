@if($claim->canDelete())
  <button type="button" class="delete btn btn-large btn-danger pull-right"><i class="fa fa-trash-o"></i> Delete</button>
@endif

@if($claim->canEdit())
  <a href="{{action('claims.edit',array($claim->id))}}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Edit</a>
@endif

@if($claim->canCancel())
  <button type="button" class="status_cancel btn btn-large pull-right"><i class="fa fa-times"></i> Cancel</button>
@endif

@if($claim->canReject())
  <button type="button" class="status_reject btn btn-large pull-right"><i class="fa fa-times"></i> Reject</button>
@endif

@if($claim->canApprove())
  <button type="button" class="status_approve btn btn-large pull-right"><i class="fa fa-check"></i> Approve</button>
@endif

@if($claim->canVerify())
  <button type="button" class="status_verify btn btn-large pull-right"><i class="fa fa-check"></i> Verify</button>
@endif

@if($claim->canTogglePaid())
  <a href="{{action('GeneralClaimsController@togglePaid',array($claim->id))}}" class="btn btn-default pull-right"><i class="fa fa-momney"></i>
    @if($claim->is_paid) Set Unpaid @else Set Paid @endif
  </a>
@endif

@if($claim->canView())
  <a href="{{action('claims.show', $claim->id)}}" class="btn btn-primary btn-large pull-right"><i class="fa fa-eye"></i> View</a>
@endif
