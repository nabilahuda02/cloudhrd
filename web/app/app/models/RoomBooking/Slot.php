<?php

class RoomBooking__Slot extends \Eloquent {

  public $table = 'room_booking_slots';

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['name', 'start', 'end'];

}