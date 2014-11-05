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

		$category = Task__Category::create($data);

		$tag = new Task__Tag();
		$tag->name = 'New';
		$tag->label = 'default';
		$tag->tag_category_id = $category->id;
		$tag->save();

		Task__Main::all()->each(function($task) use ($tag) {
			$task->tags()->save($tag);
		});

		return $category;
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

	public function destroy($id)
	{
		$category = Task__Category::findOrFail($id);
		$category->delete();
	}
}