<?php

class ProfileController extends \BaseController {

	/**
	 * Display a listing of profiles
	 *
	 * @return Response
	 */
	public function index()
	{
		$profiles = UserProfile::all();

		return View::make('profiles.index', compact('profiles'));
	}

	/**
	 * Show the form for creating a new profile
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('profiles.create');
	}

	/**
	 * Store a newly created profile in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), UserProfile::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		UserProfile::create($data);

		return Redirect::route('profiles.index');
	}

	/**
	 * Display the specified profile.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$profile = UserProfile::findOrFail($id);

		return View::make('profiles.show', compact('profile'));
	}

	/**
	 * Show the form for editing the specified profile.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile = UserProfile::find($id);

		return View::make('profiles.edit', compact('profile'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$profile = UserProfile::findOrFail($id);

		$validator = Validator::make($data = Input::all(), UserProfile::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$profile->update($data);

		return Redirect::route('profiles.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		UserProfile::destroy($id);

		return Redirect::route('profiles.index');
	}


	public function __construct()
	{
		View::share('controller', 'My Profile');
	}

}