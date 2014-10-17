<?php

class AdminLookupTimeslotsController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return Lookup__TimingSlot::all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $data = Input::all();
    $validator = Validator::make($data, Lookup__TimingSlot::$rules);

    if ($validator->fails())
    {
      return App::abort(400);
    }
    $contact = Lookup__TimingSlot::create($data);
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
    $contact = Lookup__TimingSlot::findOrFail($id);

    $validator = Validator::make($data = Input::all(), Lookup__TimingSlot::$rules);

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
    Lookup__TimingSlot::destroy($id);
    return array();
  }

}