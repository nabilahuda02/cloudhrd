<?php

class ProfileEducationController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /userprofilecontacts
	 *
	 * @return Response
	 */
	public function index()
	{
		return Profile__Education::where('user_profile_id', '=', Auth::user()->profile->id)
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
		$validator = Validator::make($data, Profile__Education::$rules);

		if ($validator->fails())
		{
			return App::abort(400);
		}
		$contact = Profile__Education::create($data);
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
		$contact = Profile__Education::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Profile__Education::$rules);

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
		Profile__Education::destroy($id);
		return array();
	}

}