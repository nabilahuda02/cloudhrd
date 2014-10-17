<?php

class AdminLeaveBlockedDatesController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return Leave__BlockedDate::all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $data = Input::all();
    $validator = Validator::make($data, Leave__BlockedDate::$rules);

    if ($validator->fails())
    {
      return App::abort(400);
    }
    $leaveType = Leave__BlockedDate::create($data);
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
    $leaveType = Leave__BlockedDate::findOrFail($id);

    $validator = Validator::make($data = Input::all(), Leave__BlockedDate::$rules);

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
    Leave__BlockedDate::destroy($id);
    return array();
  }

}