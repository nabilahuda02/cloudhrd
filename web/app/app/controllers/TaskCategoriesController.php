<?php

class TaskCategoriesController extends \BaseController {

	public function index()
	{
		return Task__Category::all();
	}

	public function store()
	{
		$validator = Validator::make($data = Input::all(), Task__Category::$validation_rules['create']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		return Task__Category::create($data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$category = Task__Category::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Task__Category::$validation_rules['update']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$category->update($data);
		
		return $category;
	}
}