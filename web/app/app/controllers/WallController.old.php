<?php

class WallController__old extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		
		$bulletins = Share::orderBy('updated_at', 'desc')
			-> where('pinned', 0)
			-> take(15)
			-> get();
		
		$pinneds = Share::orderBy('updated_at', 'desc')
			-> where('pinned', 1)
			-> get();
			
		$currentuser = Auth::user();
		return View::make('wall.index', compact('pinneds', 'bulletins', 'currentuser'));
	}

	public function getCalendar()
	{
		return View::make('wall.calendar');
	}

	public function getDashboard()
	{
		return View::make('dashboard', ['hideSidebar' => true, 'controller' => 'My Dashboard']);
	}

	public function getEventShares()
	{
		$input = Input::all();
		$start = date('Y-m-d', strtotime($input['start']));
		$end = date('Y-m-d', strtotime($input['end']));
		return Share::whereBetween('event_date', [$start, $end])
			->get()
			->map(function($event){
				return [
					'id' => $event->id,
					'title' => $event->title . ' ' . $event->user->profile->first_name,
					'allDay' => true,
					'start' => strtotime($event->event_date),
					'className' => 'wall-share'
				];
			});
	}

	public function getEventLeavesMedical()
	{
		$input = Input::all();
		$start = date('Y-m-d', strtotime($input['start']));
		$end = date('Y-m-d', strtotime($input['end']));
		return Leave__Main::join('leave_dates', 'leave_dates.leave_id', '=', 'leaves.id')
			-> whereBetween('leave_dates.date', [$start, $end])
			-> where('leaves.leave_type_id', 2)
			-> where('leaves.status_id', 3)
			-> get()
			-> map(function($leave){
				return [
					'id' => $leave->id,
					'title' => $leave->ref . ' ' . $leave->user->profile->first_name,
					'allDay' => true,
					'start' => strtotime($leave->date),
					'className' => 'leaves'
				];
			});
	}

	public function getEventLeavesAnnual()
	{
		$input = Input::all();
		$start = date('Y-m-d', strtotime($input['start']));
		$end = date('Y-m-d', strtotime($input['end']));
		return Leave__Main::join('leave_dates', 'leave_dates.leave_id', '=', 'leaves.id')
			-> whereBetween('leave_dates.date', [$start, $end])
			-> where('leaves.leave_type_id', 1)
			-> where('leaves.status_id', 3)
			-> get()
			-> map(function($leave){
				return [
					'id' => $leave->id,
					'title' => $leave->ref . ' ' . $leave->user->profile->first_name,
					'allDay' => true,
					'start' => strtotime($leave->date),
					'className' => 'leaves'
				];
			});
	}

	public function getDeleteBulletin($bulletinId)
	{
		$bulletin = Share::find($bulletinId);
		if($bulletin && $bulletin->isMine()) {
			$bulletin->delete();
		}
		return Redirect::back();
	}

	public function getLeaveDetails($date_id)
	{
		$leave = Leave__Date::find($date_id)->leave;
		$type = $leave->type;
		return [
			'leave' => $leave
		];
	}

	public function getEditProfile()
	{
		$user = Auth::user();
		return View::make('wall.edit_profile', compact($user));
	}

	public function postChangePassword()
	{
		if($data = Input::all()) {

			$validator = Validator::make($data, User::$validation_rules['changepw']);

			if ($validator->fails())
			{
				Session::flash('NotifyDanger', 'Validation Error');
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$user = Auth::user();
			$user->password = Hash::make($data['password']);
			$user->save();
			if($user->id === 1) {
				$master_user = app()->master_user;
				$master_user->password = $user->password;
				$master_user->save();
			}
			Cache::flush();
			Session::flash('NotifySuccess', 'Password Changed Successfully');
			Auth::logout();
			return Redirect::to('/auth/logout');
		}
		return Redirect::back();
	}


	public function __construct()
	{
		View::share('controller', 'Public Wall');
	}

}