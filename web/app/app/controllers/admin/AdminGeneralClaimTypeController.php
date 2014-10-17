<?php

class AdminGeneralClaimTypeController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return GeneralClaim__Type::all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $data = Input::all();
    $validator = Validator::make($data, GeneralClaim__Type::$rules);

    if ($validator->fails())
    {
      return App::abort(400);
    }
    $contact = GeneralClaim__Type::create($data);
    return $contact;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $contact = GeneralClaim__Type::findOrFail($id);

    $validator = Validator::make($data = Input::all(), GeneralClaim__Type::$rules);

    if ($validator->fails())
    {
      return App::abort(400, $validator->errors());
    }

    $contact->update($data);

    return $contact;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    GeneralClaim__Type::destroy($id);
    return array();
  }

}