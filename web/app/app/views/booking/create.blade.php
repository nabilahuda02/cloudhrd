@extends('layouts.module')
@section('content')

<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')

	@include('booking.header')

	<div class="col-md-12">
		<div >
			<h4>Room Booking Application Form</h4>
			<div class="clearfix"></div>
		</div>
		<div style="padding:15px;">
			{{ Former::horizontal_open(action('RoomBookingController@store'))
				-> id('MyForm')
				-> rules(['name' => 'required'])
				-> method('POST') }}

			@if(Auth::user()->administers(RoomBooking__Main::$moduleId))

				{{ Former::select('user_id')
				    -> label('For User')
				    -> options(Helper::userArray(), null)
				    -> class('form-control col-md-4')
				    ->required() }}

			@endif

			@include('booking.form') 

			{{ Former::textarea('remarks') }}

			<div class="form-group">
				<div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
					<input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
				</div>
			</div>
			{{ Former::close() }}	
		</div>

	</div>

</div>


@stop