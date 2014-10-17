@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')

  @include('booking.header')

	<div class="col-md-12">
		<div>
			<h4>Room Booking Histories</h4>
			<div class="clearfix"></div>
		</div>
		{{ Asset::push('js','app/tables.js')}}
		<table data-path="room-bookings" class="DT table table-vertical-center table-striped margin-none">
			<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">Booking Date</th>
					<th class="text-center">Room</th>
					<th class="text-center">Slots</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<hr>
	</div>

	@if(count($downlines) > 0)
		<div class="clearfix"></div>
		<br>
		<div class="col-md-12">
			<div>
				<h4>Other Room Bookings</h4>
				<div class="clearfix"></div>
			</div>
			<table data-path="other-room-bookings" class="DT table table-vertical-center table-striped margin-none">
				<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">User</th>
					<th class="text-center">Booking Date</th>
					<th class="text-center">Room</th>
					<th class="text-center">Slots</th>
					<th class="text-center">Action</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<hr>
		</div>
	@endif
	</div>
@stop