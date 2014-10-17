<?php

class SharesController extends \BaseController {

	/**
	 * Display a listing of shares
	 *
	 * @return Response
	 */
	public function index()
	{
		$shares = Share::all();

		return View::make('shares.index', compact('shares'));
	}

	/**
	 * Show the form for creating a new share
	 *
	 * @return Response
	 */
	public function create()
	{
		return Redirect::back();
		// View::make('shares.create');
	}

	public function show($id)
	{
		$share = Share::findOrFail($id);
		return $share;
	}

	/**
	 * Store a newly created share in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Share::$rules);

		if (Input::file('imageinput')) {
			$file = Input::file('imageinput');
			$uid = md5(date('ymdhis'));
			$data['root_path'] = '/uploads/' . $uid;
			$data['file_name'] = $name = $file->getClientOriginalName();
			$data['extension'] = $extension = $file->getClientOriginalExtension();

			$folder = public_path() . $data['root_path'];
			$file->move($folder, 'original.' . $extension);

			Helper::resizeImage($folder, $extension, 'thumbnail', 200);
			Helper::resizeImage($folder, $extension, 'medium', 800);

		} else if ($validator->fails()) {
			dd('validation');
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$data['user_id'] = Auth::user()->id;

		Share::create($data);

		return Redirect::back();
	}

	/**
	 * Display the specified share.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function show($id)
	// {
	// 	$share = Share::findOrFail($id);
	// 	return View::make('shares.show', compact('share'));
	// }

	/**
	 * Show the form for editing the specified share.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$share = Share::find($id);

		return View::make('shares.edit', compact('share'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$share = Share::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Share::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$share->update($data);

		return Redirect::route('shares.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Share::destroy($id);

		return Redirect::route('shares.index');
	}


	public function __construct()
	{
		View::share('controller', 'My Shared Stuff');
	}

}