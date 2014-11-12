<?php

class LeaveController extends \BaseController {

	/**
	 * Display a listing of leaves
	 *
	 * @return Response
	 */
	public function index()
	{
		$downlines = Auth::user()->getDownline(Leave__Main::$moduleId);
		return View::make('leaves.index', compact('downlines'));
	}

	/**
	 * Show the form for creating a new leave
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('leaves.create');
	}

	/**
	 * Store a newly created leave in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$data = Input::all();
		$rules = Leave__Main::$rules;

		if(!isset($data['user_id']) || (isset($data['user_id']) && !in_array($data['user_id'], Auth::user()->getDownline(Leave__Main::$moduleId)))) {
			$data['user_id'] = Auth::user()->id;
		}

		$data['dates'] = explode(',', $data['dates']);
		$data['total'] = count($data['dates']);
		$data['upload_hash'] = md5(microtime());

		$rules['total'] = (isset($rules['total']) ? $rules['total'] . '|' : '' ) . 'Numeric|max:' . Leave__Type::find($data['leave_type_id'])->user_entitlement_balance($data['user_id']);

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			Session::flash('NotifyDanger', 'Error Creating Leave');
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Session::flash('NotifySuccess', 'Leave Created Successfully');
		$leave = Leave__Main::create($data);
	    $leave->ref = 'LA-' . $leave->id;
	    $leave->save();

		foreach ($data['dates'] as $date) {
			$leaveDate = new Leave__Date();
			$leaveDate->leave_id = $leave->id;
			$leaveDate->date = Helper::short_date_to_mysql($date);
			$leaveDate->save();
		}

		$token = Input::get('noonce');
		Upload::where('imageable_type', $token)->update([
			'imageable_type' => 'Leave__Main',
			'imageable_id'   => $leave->id
		]);

		$leave->setStatus(1);

		return Redirect::route('leave.index');
	}

	/**
	 * Display the specified leave.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$leave = Leave__Main::findOrFail($id);

		if(!$leave->canView())
			return Redirect::action('leave.index');

		return View::make('leaves.show', compact('leave'));
	}

	/**
	 * Show the form for editing the specified leave.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$leave = Leave__Main::findOrFail($id);
		if(!$leave->canEdit())
			return Redirect::action('leave.index');

		return View::make('leaves.edit', compact('leave'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$leave = Leave__Main::findOrFail($id);

		$data = Input::all();

		/* update status */
		if(isset($data['_status']) && isset($data['status_id']) && (
				$leave->canApprove() || 
				$leave->canReject()  || 
				$leave->canCancel()  ||
				$leave->canVerify())
		) {
			if($data['status_id'] == 3 && $leave->leave_type_id == 1) {
	    }
			$leave->setStatus($data['status_id']);
  		Session::flash('NotifySuccess', 'Status Updated Successfully');
			return Redirect::action('leave.index');
		}
		
		if(!$leave->canEdit())
			return Redirect::action('leave.index');

		if(!isset($data['user_id']) || (isset($data['user_id']) && !in_array($data['user_id'], Auth::user()->getDownline(Leave__Main::$moduleId)))) {
			$data['user_id'] = Auth::user()->id;
		}

		$rules = Leave__Main::$rules;

		$data['dates'] = explode(',', $data['dates']);
		$data['total'] = count($data['dates']);
		$data['status_id'] = $leave->status_id;

		$rules['total'] = (isset($rules['total']) ? $rules['total'] . '|' : '' ) . 'Numeric|max:' . (Leave__Type::find($data['leave_type_id'])->user_entitlement_balance($data['user_id']) + $leave->total);

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			Session::flash('NotifyDanger', 'Error Updating Leave');
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$leave->update($data);
		Session::flash('NotifySuccess', 'Leave Updated Successfully');

    $leave->audits()->create([
    	'ref' => $leave->ref,
      'type' => 2,
      'data' => $leave->toArray()
    ]);

		Leave__Date::where('leave_id', $leave->id)->delete();

		foreach ($data['dates'] as $date) {
			$leaveDate = new Leave__Date();
			$leaveDate->leave_id = $leave->id;
			$leaveDate->date = Helper::short_date_to_mysql($date);
			$leaveDate->save();
		}

		return Redirect::action('leave.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// Session::flash('NotifySuccess', 'Leave Deleted Successfully');
		// Leave__Main::destroy($id);

		// return Redirect::action('leave.index');
	}

	/**
	 * Administration section
	 */
	
	public function getAdminTypes()
	{
		return View::make('leaves.admin.types', ['hide_entitlement' => true]);
	}
	
	public function getAdminBlockedDates()
	{
		return View::make('leaves.admin.blocked-dates');
	}

	public function getAdminEntitlement()
	{
		return View::make('leaves.admin.userlist', ['hide_entitlement' => true]);
	}

	public function getAdminShowUserEntitlemnt($user_id)
	{
		$entitlement_user = User::findOrFail($user_id);
		return View::make('leaves.admin.userlist', ['hide_entitlement' => true, 'entitlement_user' => $entitlement_user]);
	}

	public function postAdminShowUserEntitlemnt($user_id)
	{
		$entitlement_user = User::findOrFail($user_id);
		$data = Input::all();

		foreach(Leave__UserEntitlement::where('user_id', $user_id)->get() as $item) {
			$item->delete();
		}

		foreach($data['type'] as $type_id => $value) {
			if(floatval($value) > 0) {
				$entitlement = new Leave__UserEntitlement();
				$entitlement->user_id = $user_id;
				$entitlement->leave_type_id = $type_id;
				$entitlement->entitlement = $value;
				$entitlement->start_date = date('Y-m-01');
				$entitlement->save();
			}
		}

		Session::flash('NotifySuccess', 'Entitlement Overidden');
		return Redirect::back();

	}

	public function getAdminReporting()
	{
		return View::make('leaves.admin.reporting');
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
			 ["status_id"]=> string(0) "" 
			 ["leave_type_id"]=> string(0) "" 
			 ["leave_from_date"]=> string(10) "2014-07-01" 
			 ["leave_to_date"]=> string(0) "" 
			 ["create_from_date"]=> string(0) "" 
			 ["create_to_date"]=> string(0) "" 
			 ["tabulate_by"]=> string(0) ""
		 */
		
		$leaves = Leave__Main::join('users', 'users.id', '=', 'leaves.user_id')
			-> join('leave_dates', 'leave_dates.leave_id', '=', 'leaves.id');
		if($tabulate != 'year(leave_dates.date), month(leave_dates.date)') {
			$leaves->groupBy('leaves.id');
			$leaves->select(
				'leaves.id',
				'leaves.ref',
				'leaves.user_id',
				'leaves.status_id',
				'leaves.leave_type_id',
				'leaves.total',
				'users.unit_id',
				DB::raw('GROUP_CONCAT( leave_dates.date separator "<br>" ) as dates')
			);
		}

		if($input['unit']) {
			$leaves->where('users.unit_id', $input['unit']);
		}
		if($input['user_id']) {
			$leaves->where('user_id', $input['user_id']);
		}
		if($input['status_id']) {
			$leaves->where('status_id', $input['status_id']);
		}
		if($input['leave_type_id']) {
			$leaves->where('leave_type_id', $input['leave_type_id']);
		}
		if($input['create_from_date']) {
			$leaves->where('leaves.created_at', '>=', $input['create_from_date']);
		}
		if($input['create_to_date']) {
			$leaves->where('leaves.created_at', '<=', $input['create_to_date']);
		}
		if($input['leave_from_date']) {
			$leaves->where('leave_dates.date', '>=', $input['leave_from_date']);
		}
		if($input['leave_to_date']) {
			$leaves->where('leave_dates.date', '<=', $input['leave_to_date']);
		}		if($tabulate) {
			$groupLeave = clone $leaves;
			$lists_column = $tabulate;
			if($tabulate === 'users.unit_id')
				$lists_column = 'unit_id';
			if($tabulate === 'year(leave_dates.date), month(leave_dates.date)') {
				$groupLeave = $groupLeave
					->groupBy(DB::raw($tabulate))
					->get();

				// $leaves->groupBy('leaves.id');
				foreach ($groupLeave as $index => $data) {
					$tempLeave = clone $leaves;
					$tempLeave->groupBy('leaves.id');
					$tempLeave->select(
						'leaves.id',
						'leaves.ref',
						'leaves.user_id',
						'leaves.status_id',
						'leaves.leave_type_id',
						DB::raw('count(leave_dates.date) as total'),
						DB::raw('GROUP_CONCAT( leave_dates.date separator "<br>" ) as dates')
					);
					$value = explode('-', $data->date);
					array_pop($value);
					$value = implode('-', $value);
					$tables[] = [
						'title' => $value,
						'data'  => $tempLeave
							-> where('leave_dates.date', 'like', "$value%")
							-> get()
					];
					unset($tempLeave);
				}
			} else {
				$groupLeave = $groupLeave
					->groupBy(DB::raw($tabulate))
					->get()
					->lists($lists_column);
				$groupLeave = array_unique($groupLeave);
				foreach ($groupLeave as $index => $value) {
					$tempLeave = clone $leaves;
					$title = $value;
					switch ($tabulate) {
						case 'leave_type_id':
							$title = Leave__Type::find($title)->name;
							break;
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
						'data'  => $tempLeave
							-> where($tabulate, $value)
							-> get()
					];
					unset($tempLeave);
				}
			}
		} else {
			$tables = [[
				'title' => '',
				'data' => $leaves->get()
			]];
		}
		if(isset($input['download'])) {
			return Excel::create('Leave_Report_' . date('Ymd-His') , function($excel) use($tables) {
				foreach ($tables as $table) {
			    $excel->sheet($table['title'], function($sheet) use($table) {
		        $sheet->loadView('leaves.admin.reporting-table', ['datas' => $table['data']]);
			    });
				}
			})->export('xls');
		}
		return View::make('leaves.admin.reporting', compact('tables'))->withInput($input);
	}


	public function __construct()
	{
		View::share('controller', 'Leaves');
		View::share('types', Leave__Type::all());
	}

}