@if($booking->canDelete())
  <button type="button" class="delete btn btn-large pull-right"><i class="fa fa-trash-o"></i> Delete</button>
@endif

@if($booking->canEdit())
  <a href="{{action('RoomBookingController@edit',array($booking->id))}}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Edit</a>
@endif

@if($booking->canCancel())
  <button type="button" class="status_cancel btn btn-large pull-right"><i class="fa fa-times"></i> Cancel</button>
@endif

@if($booking->canReject())
  <button type="button" class="status_reject btn btn-large pull-right"><i class="fa fa-times"></i> Reject</button>
@endif

@if($booking->canApprove())
  <button type="button" class="status_approve btn btn-large pull-right"><i class="fa fa-check"></i> Approve</button>
@endif

@if($booking->canVerify())
  <button type="button" class="status_verify btn btn-large pull-right"><i class="fa fa-check"></i> Verify</button>
@endif

@if($booking->canView())
  <a href="{{action('booking.show', $booking->id)}}" class="btn btn-primary btn-large pull-right"><i class="fa fa-eye"></i> View</a>
@endif