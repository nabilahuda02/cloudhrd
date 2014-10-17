<?php $booking = RoomBooking__Main::find($id); ?>
<div class="btn-group btn-group-xs ">
  @if($booking->canView())
    <a href="{{action('RoomBookingController@show',array($booking->id))}}" class="btn btn-primary"><i class="fa fa-folder-open"></i></a>
  @endif
  @if($booking->canEdit())
    <a href="{{action('RoomBookingController@edit',array($booking->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
  @endif
</div>