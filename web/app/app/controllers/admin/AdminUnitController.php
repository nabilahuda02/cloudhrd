<?php

class AdminUnitController extends \BaseController {

  /**
   * Display a listing of units
   *
   * @return Response
   */
  public function index()
  {
    $units = UserUnit::all();
    return View::make('admin.units.index', compact('units'));
  }

  /**
   * Show the form for creating a new unit
   *
   * @return Response
   */
  public function create()
  {
    return View::make('admin.units.create');
  }

  /**
   * Store a newly created unit in storage.
   *
   * @return Response
   */
  public function store()
  {

    $validator = Validator::make($data = Input::all(), UserUnit::$validation_rules);



    if ($validator->fails())
    {
      Session::flash('NotifyDanger', 'Error Creating Unit');
      return Redirect::back()->withErrors($validator)->withInput();
    }

    $parent_id = isset($data['parent_id']) ? $data['parent_id'] : null;

    if($parent_id) {
      $parent = UserUnit::find($parent_id);
      $parent->children()->create($data);
    } else {
      UserUnit::create($data);
    }

    Session::flash('NotifySuccess', 'Unit Created Successfully');

    return Redirect::route('unitadmin.index');
  }

  /**
   * Show the form for editing the specified unit.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $unit = UserUnit::find($id);
    return View::make('admin.units.edit', compact('unit'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $unit = UserUnit::findOrFail($id);
    $data = Input::all();

    $validator = Validator::make($data = Input::all(), UserUnit::$validation_rules);

    if ($validator->fails())
    {
      Session::flash('NotifyDanger', 'Error Updating Unit');
      return Redirect::back()->withErrors($validator)->withInput();
    }

    $parent_id = isset($data['parent_id']) ? $data['parent_id'] : null;

    $unit->update($data);

    if($parent_id) {
      $parent = UserUnit::find($parent_id);
      $unit->makeChildOf($parent);
    }

    Session::flash('NotifySuccess', 'Unit Updated Successfully');

    return Redirect::route('unitadmin.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    UserUnit::find($id)->delete();
    Session::flash('NotifySuccess', 'Unit Deleted Successfully');
    return Redirect::route('unitadmin.index');
  }

  public function getChart()
  {
    $orgs = UserUnit::all();
    return View::make('admin.units.chart', compact('orgs'));
  }


  public function __construct()
  {
    View::share('controller', 'Unit Admin');
    View::share('types', UserUnit::all());
  }

}