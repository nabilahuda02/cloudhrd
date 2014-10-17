<div class="col-md-12 text-center">
  <br>
  <a href="<?php echo url('booking', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-list-alt"></span> View Room Bookings </a>
  <a href="<?php echo url('booking/create', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-file"></span> Create Room Booking </a>
  @if(Auth::user()->administers(RoomBooking__Main::$moduleId))
    <a href="<?php echo url('booking/admin/rooms', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-cog"></span> Manage Rooms</a>
    <a href="<?php echo url('booking/admin/timeslots', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-time"></span> Manage Time Slots</a>
    <a href="<?php echo url('booking/admin/reporting', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-list-alt"></span> Reporting</a>
  @endif
  <div class="clearfix"></div>
  <br>
</div>

<div class="clearfix"></div> <br>