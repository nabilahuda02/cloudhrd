<?php

class AdminRoomBookingRoomsController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return RoomBooking__Room::all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $data = Input::all();
    $validator = Validator::make($data, RoomBooking__Room::$rules);

    if ($validator->fails())
    {
      return App::abort(400);
    }
    $contact = RoomBooking__Room::create($data);
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
    $contact = RoomBooking__Room::findOrFail($id);

    $validator = Validator::make($data = Input::all(), RoomBooking__Room::$rules);

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
    RoomBooking__Room::destroy($id);
    return array();
  }

}