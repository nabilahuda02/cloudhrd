<?php

class AdminMedicalClaimTypeController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return MedicalClaim__Type::all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $data = Input::all();
    $validator = Validator::make($data, MedicalClaim__Type::$rules);

    if ($validator->fails())
    {
      return App::abort(400);
    }
    $data['display_wall'] = isset($data['display_wall']) ? true : false;
    $type = MedicalClaim__Type::create($data);
    return $type;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $type = MedicalClaim__Type::findOrFail($id);

    $validator = Validator::make($data = Input::all(), MedicalClaim__Type::$rules);

    if ($validator->fails())
    {
      return App::abort(400, $validator->errors());
    }
    $data['display_wall'] = isset($data['display_wall']) ? true : false;
    $type->update($data);

    return $type;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    MedicalClaim__Type::destroy($id);
    return array();
  }

}