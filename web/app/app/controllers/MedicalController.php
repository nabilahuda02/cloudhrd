<?php

class MedicalController extends \BaseController {

	/**
	 * Display a listing of medicals
	 *
	 * @return Response
	 */
	public function index()
	{
		$downlines = Auth::user()->getDownline(MedicalClaim__Main::$moduleId);
		return View::make('medicals.index', compact('downlines'));
	}

	/**
	 * Show the form for creating a new medical
	 *
	 * @return Response
	 */
	public function create()
	{
		$medicalTypes = MedicalClaim__Type::all();
		return View::make('medicals.create', compact('medicalTypes'));
	}

	/**
	 * Store a newly created medical in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$data = Input::all();
		$data['upload_hash'] = md5(microtime());
		$rules = MedicalClaim__Main::$rules;

		if(!isset($data['user_id']) || (isset($data['user_id']) && !in_array($data['user_id'], Auth::user()->getDownline(MedicalClaim__Main::$moduleId)))) {
			$data['user_id'] = Auth::user()->id;
		}

		$rules['total'] = (isset($rules['total']) ? $rules['total'] . '|' : '' ) . 'Numeric|max:' . MedicalClaim__Type::find($data['medical_claim_type_id'])->user_entitlement_balance($data['user_id']);

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			Session::flash('NotifyDanger', 'Error Creating Claim');
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Session::flash('NotifySuccess', 'Claim Created Successfully');
		$medical = MedicalClaim__Main::create($data);

	    $medical->ref = 'MC-' . $medical->id;
	    $medical->save();

		$token = Input::get('_token');
		Upload::where('imageable_type', $token)->update([
			'imageable_type' => 'MedicalClaim__Main',
			'imageable_id'   => $medical->id
		]);

	    $medical->setStatus(1);

		return Redirect::route('medical.index');
	}

	/**
	 * Display the specified medical.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$medical = MedicalClaim__Main::findOrFail($id);
		if(!$medical->canView())
			return Redirect::action('medical.index');

		return View::make('medicals.show', compact('medical'));
	}

	/**
	 * Show the form for editing the specified medical.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$medical = MedicalClaim__Main::findOrFail($id);
		if(!$medical->canEdit())
			return Redirect::action('medical.index');

		return View::make('medicals.edit', compact('medical'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$medical = MedicalClaim__Main::findOrFail($id);

		$data = Input::all();

		/* update status */
		if(isset($data['_status']) && isset($data['status_id']) && (
				$medical->canApprove() || 
				$medical->canReject()  || 
				$medical->canCancel()  ||
				$medical->canVerify())
		) {
			$medical->setStatus($data['status_id']);
			Session::flash('NotifySuccess', 'Status Updated Successfully');
			return Redirect::action('medical.index');
		}
		
		if(!$medical->canEdit())
			return Redirect::action('medical.index');

		if(!isset($data['user_id']) || (isset($data['user_id']) && !in_array($data['user_id'], Auth::user()->getDownline(MedicalClaim__Main::$moduleId)))) {
			$data['user_id'] = Auth::user()->id;
		}

		$rules = MedicalClaim__Main::$rules;
		$rules['total'] = (isset($rules['total']) ? $rules['total'] . '|' : '' ) . 'max:' . (MedicalClaim__Type::find($data['medical_claim_type_id'])->user_entitlement_balance($medical->user_id) + $medical['total']);

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			Session::flash('NotifyDanger', 'Error Updating Claim');
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$medical->update($data);

    $medical->audits()->create([
    	'ref' => $medical->ref,
      'type' => 2,
      'data' => $medical->toArray()
    ]);

		Session::flash('NotifySuccess', 'Claim Updated Successfully');

		return Redirect::action('medical.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// Session::flash('NotifySuccess', 'Claim Deleted Successfully');
		// MedicalClaim__Main::destroy($id);

		// return Redirect::action('medical.index');
	}

	/**
	 * Administration section
	 */
	
	public function getAdminTypes()
	{
		return View::make('medicals.admin.types', ['hide_entitlement' => true]);
	}
	
	public function getAdminPanelClinics()
	{
		return View::make('medicals.admin.panel-clinics', ['hide_entitlement' => true]);
	}

	public function getAdminEntitlement()
	{
		return View::make('medicals.admin.userlist', ['hide_entitlement' => true]);
	}

	public function getAdminShowUserEntitlemnt($user_id)
	{
		$entitlement_user = User::findOrFail($user_id);
		return View::make('medicals.admin.userlist', ['hide_entitlement' => true, 'entitlement_user' => $entitlement_user]);
	}

	public function postAdminShowUserEntitlemnt($user_id)
	{
		$entitlement_user = User::findOrFail($user_id);
		$data = Input::all();

		foreach(MedicalClaim__UserEntitlement::where('user_id', $user_id)->get() as $item) {
			$item->delete();
		}

		foreach($data['type'] as $type_id => $value) {
			if(floatval($value) > 0) {
				$entitlement = new MedicalClaim__UserEntitlement();
				$entitlement->user_id = $user_id;
				$entitlement->medical_claim_type_id = $type_id;
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
		return View::make('medicals.admin.reporting');
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
			 ["medical_claim_type_id"]=> string(0) "" 
			 ["medical_from_date"]=> string(10) "2014-07-01" 
			 ["medical_to_date"]=> string(0) "" 
			 ["create_from_date"]=> string(0) "" 
			 ["create_to_date"]=> string(0) "" 
			 ["medical_claim_panel_clinic_id"]=> string(0) "" 
			 ["tabulate_by"]=> string(0) ""
		 */
		
		$medical_claims = MedicalClaim__Main::join('users', 'users.id', '=', 'medical_claims.user_id');
		if($tabulate != 'year(medical_claims.treatment_date), month(medical_claims.treatment_date)') {
			$medical_claims->groupBy('medical_claims.id');
			$medical_claims->select(
				'medical_claims.id',
				'medical_claims.ref',
				'medical_claims.treatment_date',
				'medical_claims.user_id',
				'medical_claims.status_id',
				'medical_claims.medical_claim_type_id',
				'medical_claims.total',
				'users.unit_id'
			);
		}

		if($input['unit']) {
			$medical_claims->where('users.unit_id', $input['unit']);
		}
		if($input['user_id']) {
			$medical_claims->where('user_id', $input['user_id']);
		}
		if($input['status_id']) {
			$medical_claims->where('status_id', $input['status_id']);
		}
		if($input['medical_claim_type_id']) {
			$medical_claims->where('medical_claim_type_id', $input['medical_claim_type_id']);
		}
		if($input['create_from_date']) {
			$medical_claims->where('medical_claims.created_at', '>=', $input['create_from_date']);
		}
		if($input['create_to_date']) {
			$medical_claims->where('medical_claims.created_at', '<=', $input['create_to_date']);
		}
		if($input['medical_from_date']) {
			$medical_claims->where('medical_claims.treatment_date', '>=', $input['medical_from_date']);
		}
		if($input['medical_to_date']) {
			$medical_claims->where('medical_claims.treatment_date', '<=', $input['medical_to_date']);
		}		
		if($tabulate) {
			$groupMedical = clone $medical_claims;
			$lists_column = $tabulate;
			if($tabulate === 'users.unit_id')
				$lists_column = 'unit_id';
			if($tabulate === 'year(medical_claims.treatment_date), month(medical_claims.treatment_date)') {
				$groupMedical = $groupMedical
					->groupBy(DB::raw($tabulate))
					->get();

				foreach ($groupMedical as $index => $data) {
					$tempMedical = clone $medical_claims;
					$tempMedical->groupBy('medical_claims.id');
					$tempMedical->select(
						'medical_claims.id',
						'medical_claims.ref',
						'medical_claims.treatment_date',
						'medical_claims.user_id',
						'medical_claims.status_id',
						'medical_claims.medical_claim_type_id',
						'medical_claims.total',
						'users.unit_id'
					);
					$value = explode('-', $data->treatment_date);
					array_pop($value);
					$value = implode('-', $value);
					$tables[] = [
						'title' => $value,
						'data'  => $tempMedical
							-> where('medical_claims.treatment_date', 'like', "$value%")
							-> get()
					];
					unset($tempMedical);
				}
			} else {
				$groupMedical = $groupMedical
					->groupBy(DB::raw($tabulate))
					->get()
					->lists($lists_column);
				$groupMedical = array_unique($groupMedical);
				foreach ($groupMedical as $index => $value) {
					$tempMedical = clone $medical_claims;
					$title = $value;
					switch ($tabulate) {
						case 'medical_claim_type_id':
							$title = MedicalClaim__Type::find($title)->name;
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
						'data'  => $tempMedical
							-> where($tabulate, $value)
							-> get()
					];
					unset($tempMedical);
				}
			}
		} else {
			$tables = [[
				'title' => '',
				'data' => $medical_claims->get()
			]];
		}
		if(isset($input['download'])) {
			return Excel::create('MedicalClaim_Report_' . date('Ymd-His') , function($excel) use($tables) {
				foreach ($tables as $table) {
			    $excel->sheet($table['title'], function($sheet) use($table) {
		        	$sheet->loadView('medicals.admin.reporting-table', ['datas' => $table['data']]);
			    });
				}
			})->export('xls');
		}
		return View::make('medicals.admin.reporting', compact('tables'))->withInput($input);
	}


	public function __construct()
	{
		View::share('controller', 'Medical Claims');
		View::share('types', MedicalClaim__Type::all());
	}

}