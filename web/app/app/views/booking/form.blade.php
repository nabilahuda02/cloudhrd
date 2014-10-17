{{ Former::date('booking_date')
    -> label('Booking Date')
    -> value(date('Y-m-d'))
    -> required() }}

<?php
    $slots_select = [];
    if(isset($booking)) {
        foreach ($booking->slots as $slot) {
            $slots_select[] = $slot->id;
        }
    }
?>
{{ Former::select('slots')
    -> id("slots")
    -> name('slots[]')
    -> label('Slots')
    -> multiple()
    -> options(Lookup__TimingSlot::former_options() ,null)
    -> class('form-control col-md-4')
    -> help('<br/> Ctrl + click to pick multiple slots')
    -> required() 
    -> select($slots_select) }}

<?php
    $room_options = [];
    if(isset($booking)) {
        $room_options = RoomBooking__Room::all()->lists('name', 'id');
    }
?>
{{ Former::select('room_booking_room_id')
    -> label('Room')
    -> class('form-control col-md-4')
    -> options($room_options)
    -> required() }}

{{ Former::text('purpose')
    -> required() }}


{{Helper::pageScript('app/booking.js')}}