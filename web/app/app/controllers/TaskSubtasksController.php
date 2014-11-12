<?php

class TaskSubtasksController extends \BaseController {

	public function store()
	{
		$validator = Validator::make($data = Input::all(), Task__Subtask::$validation_rules['create']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$subtask = new Task__Subtask();
		$subtask->todo_id = $data['todo_id'];
		$subtask->name = $data['name'];
		$subtask->save();
		return $subtask;
	}

	public function setDone($id)
	{
		$subtask = Task__Subtask::findOrFail($id);
		$subtask->is_done = 1;
		$subtask->save();
		return $subtask;
	}

	public function setUndone($id)
	{
		$subtask = Task__Subtask::findOrFail($id);
		$subtask->is_done = 0;
		$subtask->save();
		return $subtask;
	}

	public function destroy($id)
	{
		$subtask = Task__Subtask::findOrFail($id);
		$subtask->delete();
		return $subtask;
	}




}