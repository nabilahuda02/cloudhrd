<?php

class RoomBookingsTableSeeder extends Seeder {
	public function run()
	{
    DB::table('room_bookings')->truncate();
    DB::table('room_booking_rooms')->truncate();
    DB::table('lookup_timing_slots')->truncate();
    DB::table('room_booking_timing_slots')->truncate();

    $roomBookingRooms = [
      // [
      //   'name' => 'Meeting Room'
      // ]
    ];

    $lookupTimingSlots = [
      [
        'name' => 'Morning',
        'start' => '09:00',
        'end' => '10:30'
      ],
      [
        'name' => 'Late Morning',
        'start' => '11:00',
        'end' => '12:30'
      ],
      [
        'name' => 'Afternoon',
        'start' => '14:00',
        'end' => '15:30'
      ],
      [
        'name' => 'Late Afternoon',
        'start' => '16:00',
        'end' => '17:30'
      ]
    ];

    foreach ($roomBookingRooms as $roomBookingRoom) {
      $room = new RoomBooking__Room();
      $room->name = $roomBookingRoom['name'];
      $room->save();
    }

    foreach ($lookupTimingSlots as $lookupTimingSlot) {
      $slot = new Lookup__TimingSlot();
      $slot->name = $lookupTimingSlot['name'];
      $slot->start = $lookupTimingSlot['start'];
      $slot->end = $lookupTimingSlot['end'];
      $slot->save();
    }
  }
}