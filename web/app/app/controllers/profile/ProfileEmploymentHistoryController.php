<?php

class ProfileEmploymentHistoryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /userprofilecontacts
	 *
	 * @return Response
	 */
	public function index()
	{
		return Profile__EmploymentHistory::where('user_profile_id', '=', Auth::user()->profile->id)
			->get();
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /userprofilecontacts
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$data['user_profile_id'] = Auth::user()->profile->id;
		$validator = Validator::make($data, Profile__EmploymentHistory::$rules);

		if ($validator->fails())
		{
			return App::abort(400, $validator->errors());
		}
		$contact = Profile__EmploymentHistory::create($data);
		return $contact;
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /userprofilecontacts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$contact = Profile__EmploymentHistory::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Profile__EmploymentHistory::$rules);

		if ($validator->fails())
		{
			return App::abort(400, $validator->errors());
		}

		$contact->update($data);

		return $contact;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /userprofilecontacts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Profile__EmploymentHistory::destroy($id);
		return array();
	}

}