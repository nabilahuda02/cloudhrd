<?php

class RoomBooking__Room extends \Eloquent {

  public $table = 'room_booking_rooms';

  // Add your validation rules here
  public static $rules = [
    // 'title' => 'required'
  ];

  // Don't forget to fill this array
  protected $fillable = ['name'];

  public static function selectOptions()
  {
    return ['' => 'Select One'] + (static::all()->lists('name', 'id'));
  }

}