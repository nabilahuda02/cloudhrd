<?php

class RoomBookingController extends \BaseController {

	/**
	 * Display a listing of bookings
	 *
	 * @return Response
	 */
	public function index()
	{

		$downlines = Auth::user()->getDownline(Leave__Main::$moduleId);
		return View::make('booking.index', compact('downlines'));
	}

	/**
	 * Show the form for creating a new booking
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('booking.create');
	}

	/**
	 * Store a newly created booking in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$data = Input::all();
		$rules = RoomBooking__Main::$rules;

		if(!isset($data['user_id']) || (isset($data['user_id']) && !in_array($data['user_id'], Auth::user()->getDownline(RoomBooking__Main::$moduleId)))) {
			$data['user_id'] = Auth::user()->id;
		}

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			Session::flash('NotifyDanger', 'Error Creating Booking');
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Session::flash('NotifySuccess', 'Booking Created Successfully');
		$booking = RoomBooking__Main::create($data);
		$booking->slots()->sync($data['slots']);
		$booking->save();

    $booking->ref = 'BK-' . $booking->id;
    $booking->status_id = 1;
    $booking->save();

		$booking->setStatus(1);

		return Redirect::route('booking.index');
	}

	/**
	 * Display the specified booking.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$booking = RoomBooking__Main::findOrFail($id);
		if(!$booking->canView())
			return Redirect::action('booking.index');

		return View::make('booking.show', compact('booking'));
	}

	/**
	 * Show the form for editing the specified booking.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$booking = RoomBooking__Main::findOrFail($id);
		if(!$booking->canEdit())
			return Redirect::action('booking.index');

		return View::make('booking.edit', compact('booking'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$booking = RoomBooking__Main::findOrFail($id);

		$data = Input::all();
		/* update status */
		if(isset($data['_status']) && isset($data['status_id']) && (
				$booking->canApprove() || 
				$booking->canReject()  || 
				$booking->canCancel()  ||
				$booking->canVerify())
		) {
			if($data['status_id'] == 3) {
        $slotsstr = '';
        foreach($booking->slots as $slot) {
          $slotsstr = $slotsstr . '<li>' . $slot->pretty() . '</li>';
        }
        $booking->shares()->create([
          'type' => 'event',
          'user_id' => $booking->user_id,
          'event_date' => $booking->booking_date,
          'title' => $booking->room->name . ' will be occupied',
          'content' => $booking->user->profile->first_name . 
            ' will be using ' . $booking->room->name . 
            ' during these slots: <br> <ul class="timeline-inner-ul">' . $slotsstr . '</ul>'
        ]);
			}
			$booking->setStatus($data['status_id']);
			Session::flash('NotifySuccess', 'Status Updated Successfully');
			return Redirect::action('booking.index');
		}
		
		if(!$booking->canEdit())
			return Redirect::action('booking.index');

		if(!isset($data['user_id']) || (isset($data['user_id']) && !in_array($data['user_id'], Auth::user()->getDownline(RoomBooking__Main::$moduleId)))) {
			$data['user_id'] = Auth::user()->id;
		}
		$rules = RoomBooking__Main::$rules;
		$validator = Validator::make($data, $rules);
		if ($validator->fails())
		{
			Session::flash('NotifyDanger', 'Error Updating Booking');
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$booking->update($data);
		$booking->slots()->sync($data['slots']);
    $booking->audits()->create([
    	'ref' => $booking->ref,
      'type' => 2,
      'data' => $booking->toArray()
    ]);
		Session::flash('NotifySuccess', 'Booking Updated Successfully');
		return Redirect::action('booking.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// Session::flash('NotifySuccess', 'Booking Deleted Successfully');
		// RoomBooking__Main::destroy($id);

		// return Redirect::action('booking.index');
	}


	/**
	 * Administration section
	 */
	
	public function getTimeslot()
	{
		return View::make('booking.admin.timeslots');
	}

	public function getRooms()
	{
		return View::make('booking.admin.rooms');
	}

	public function getAdminReporting()
	{
		return View::make('booking.admin.reporting');
	}

	public function postAdminReporting()
	{
		$input = Input::all();
		$tables = [];
		$tabulate = $input['tabulate_by'];

		// dd($input);

		/*
			["unit"]=> string(0) "" 
			["user_id"]=> string(0) "" 
			["purpose"]=> string(0) "" 
			["status_id"]=> string(0) "" 
			["room_booking_room_id"]=> string(0) "" 
			["booking_from_date"]=> string(10) "2014-07-01" 
			["booking_to_date"]=> string(0) "" 
			["create_from_date"]=> string(10) "2014-07-01" 
			["create_to_date"]=> string(0) "" 
			["tabulate_by"]=> string(0) "" 
		 */
		
		$room_bookings = RoomBooking__Main::join('users', 'users.id', '=', 'room_bookings.user_id');
		$room_bookings->select(
			'room_bookings.id',
			'room_bookings.ref',
			'room_bookings.purpose',
			'room_bookings.room_booking_room_id',
			'room_bookings.status_id',
			'room_bookings.user_id',
			'room_bookings.booking_date',
			'room_bookings.created_at',
			'users.unit_id'
		);
		if($tabulate != 'year(room_bookings.booking_date), month(room_bookings.booking_date)') {
			$room_bookings->groupBy('room_bookings.id');
		}

		if($input['unit']) {
			$room_bookings->where('users.unit_id', $input['unit']);
		}
		if($input['user_id']) {
			$room_bookings->where('user_id', $input['user_id']);
		}
		if($input['purpose']) {
			$input['purpose'] = '%' . $input['purpose'] . '%';
			$room_bookings->where('purpose', 'like', $input['purpose']);
		}
		if($input['status_id']) {
			$room_bookings->where('status_id', $input['status_id']);
		}
		if($input['room_booking_room_id']) {
			$room_bookings->where('room_booking_room_id', $input['room_booking_room_id']);
		}
		if($input['booking_from_date']) {
			$room_bookings->where('room_bookings.booking_date', '>=', $input['booking_from_date']);
		}
		if($input['booking_to_date']) {
			$room_bookings->where('room_bookings.booking_date', '<=', $input['booking_to_date']);
		}
		if($input['create_from_date']) {
			$room_bookings->where('room_bookings.created_at', '>=', $input['create_from_date']);
		}
		if($input['create_to_date']) {
			$room_bookings->where('room_bookings.created_at', '<=', $input['create_to_date']);
		}
		if($tabulate) {
			$groupClaims = clone $room_bookings;
			$lists_column = $tabulate;
			if($tabulate === 'users.unit_id')
				$lists_column = 'unit_id';
			if($tabulate === 'year(room_bookings.booking_date), month(room_bookings.booking_date)') {
				$groupClaims = $groupClaims
					->groupBy(DB::raw($tabulate))
					->get();

				// dd(last(DB::getQueryLog()));
				// dd($groupClaims);

				foreach ($groupClaims as $index => $data) {

					$tempClaims = clone $room_bookings;
					// $tempClaims->groupBy('room_bookings.id');
					$tempClaims->select(
						'room_bookings.id',
						'room_bookings.ref',
						'room_bookings.purpose',
						'room_bookings.room_booking_room_id',
						'room_bookings.status_id',
						'room_bookings.user_id',
						'room_bookings.booking_date',
						'room_bookings.created_at',
						'users.unit_id'
					);
					$value = explode('-', $data->booking_date);
					array_pop($value);
					$value = implode('-', $value);
					$tables[] = [
						'title' => $value,
						'data'  => $tempClaims
							-> where('room_bookings.booking_date', 'like', "$value%")
							-> get()
					];
					unset($tempClaims);
				}
			} else {
				$groupClaims = $groupClaims
					->groupBy(DB::raw($tabulate))
					->get()
					->lists($lists_column);
				$groupClaims = array_unique($groupClaims);
				foreach ($groupClaims as $index => $value) {
					$tempClaims = clone $room_bookings;
					$title = $value;
					switch ($tabulate) {
						case 'status_id':
							$title = Status::find($title)->name;
							break;
						case 'user_id':
							$title = UserProfile::find($title)->userName();
							break;
						case 'users.unit_id':
							$title = UserUnit::find($title)->name;
							break;
					}
					$tables[] = [
						'title' => $title,
						'data'  => $tempClaims
							-> where($tabulate, $value)
							-> get()
					];
					unset($tempClaims);
				}
			}
		} else {
			$tables = [[
				'title' => '',
				'data' => $room_bookings->get()
			]];
		}
		if(isset($input['download'])) {
			return Excel::create('RoomBooking_Report_' . date('Ymd-His') , function($excel) use($tables) {
				foreach ($tables as $table) {
			    $excel->sheet($table['title'], function($sheet) use($table) {
		        $sheet->loadView('booking.admin.reporting-table', ['datas' => $table['data']]);
			    });
				}
			})->export('xls');
		}
		return View::make('booking.admin.reporting', compact('tables'))->withInput($input);
	}


	/**
	 * Constructor
	 */


	public function __construct()
	{
		View::share('controller', 'Room Booking');
		View::share('booking_slots', Lookup__TimingSlot::all());
	}

}