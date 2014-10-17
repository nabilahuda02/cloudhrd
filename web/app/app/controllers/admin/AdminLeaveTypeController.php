<?php

class AdminLeaveTypeController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return Leave__Type::all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $data = Input::all();
    $data['future'] = isset($data['future']) ? true : false;
    $data['past'] = isset($data['past']) ? true : false;
    $data['display_calendar'] = isset($data['display_calendar']) ? true : false;
    $data['display_wall'] = isset($data['display_wall']) ? true : false;
    $validator = Validator::make($data, Leave__Type::$rules);

    if ($validator->fails())
    {
      return App::abort(400);
    }
    $leaveType = Leave__Type::create($data);
    return $leaveType;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $leaveType = Leave__Type::findOrFail($id);

    $validator = Validator::make($data = Input::all(), Leave__Type::$rules);

    $data['future'] = isset($data['future']) ? true : false;
    $data['past'] = isset($data['past']) ? true : false;
    $data['display_calendar'] = isset($data['display_calendar']) ? true : false;
    $data['display_wall'] = isset($data['display_wall']) ? true : false;

    if ($validator->fails())
    {
      return App::abort(400, $validator->errors());
    }

    $leaveType->update($data);

    return $leaveType;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    Leave__Type::destroy($id);
    return array();
  }

}