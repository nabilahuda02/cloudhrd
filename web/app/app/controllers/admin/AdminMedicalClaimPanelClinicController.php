<?php

class AdminMedicalClaimPanelClinicController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return MedicalClaim__PanelClinic::all();
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
    $validator = Validator::make($data, MedicalClaim__PanelClinic::$rules);

    if ($validator->fails())
    {
      return App::abort(400);
    }
    $leaveType = MedicalClaim__PanelClinic::create($data);
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
    $leaveType = MedicalClaim__PanelClinic::findOrFail($id);

    $validator = Validator::make($data = Input::all(), MedicalClaim__PanelClinic::$rules);

    $data['future'] = isset($data['future']) ? true : false;
    $data['past'] = isset($data['past']) ? true : false;

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
    MedicalClaim__PanelClinic::destroy($id);
    return array();
  }

}