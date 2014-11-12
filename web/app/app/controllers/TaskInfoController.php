<?php

class TaskInfoController extends \BaseController {

	/**
	 * Display a listing of medicals
	 *
	 * @return Response
	 */
	public function show($id)
	{
        return Task__Main::with('owner', 'owner.profile', 'followers', 'followers.profile', 'subtasks', 'notes', 'uploads')->findOrFail($id);
	}

}