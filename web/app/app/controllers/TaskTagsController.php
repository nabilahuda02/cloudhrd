<?php

class TaskTagsController extends \BaseController {

	public function index()
	{
		return Task__Tag::all();
	}

	public function store()
	{
		$validator = Validator::make($data = Input::all(), Task__Tag::$validation_rules['create']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		return Task__Tag::create($data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$category = Task__Tag::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Task__Tag::$validation_rules['update']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Task__TagUserOrder::where('user_id', Auth::user()->id)
			->where('tag_id', $id)
			->delete();
		$order = Task__TagUserOrder::create(['user_id' => Auth::user()->id, 'tag_id' => $id]);
		$order->order = $data['order'];
		$order->save();

		Task__TagUserPlacement::where('user_id', Auth::user()->id)
			->where('tag_id', $id)
			->delete();
		$placement = Task__TagUserPlacement::create(['user_id' => Auth::user()->id, 'tag_id' => $id]);
		$placement->name = $data['placement'];
		$placement->save();

		$category->update($data);
		return $category;
	}

	public function destroy($id)
	{
		$tag = Task__Tag::findOrFail($id);
		$otherTag = Task__Tag::where('tag_category_id', $tag->tag_category_id)->whereNotIn('id', [$tag->id])->first();
		if($otherTag) {
			$otherTag->tasks()->attach($tag->tasks->lists('id'));
			$tag->tasks()->sync([]);
			$tag->delete();
		}
	}

	public function updateName($id)
	{
		$tag = Task__Tag::findOrFail($id);
		$input = Input::all();
		$tag->name = $input['value']['name'];
		$tag->label = $input['value']['label'];
		$tag->save();
		return $input;
	}
}