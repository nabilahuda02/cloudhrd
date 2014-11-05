<?php

class AdminModuleController extends \BaseController {

  /**
   * Display a listing of modules
   *
   * @return Response
   */
  public function index()
  {
    $modules = Module::where('has_config', 1)->get();
    return View::make('admin.modules.index', compact('modules'));
  }

  /**
   * Show the form for editing the specified module.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $module = Module::findOrFail($id);
    return View::make('admin.modules.edit', compact('module'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $module = Module::findOrFail($id);

    $data = Input::all();

    $validator = Validator::make($data = Input::all(), Module::$validation_rules['edit']);

    if ($validator->fails())
    {
      Session::flash('NotifyDanger', 'Error Updating Module');
      return Redirect::back()->withErrors($validator)->withInput();
    }

    Session::flash('NotifySuccess', 'Module Updated Successfully');
    $module->update($data);

    return Redirect::route('moduleadmin.index');
  }

  public function getModuleAdmins($module_id)
  {
    return UserModule::where('module_id', $module_id)->get();
  }

  public function postModuleAdmins($module_id)
  {
    $input = Input::all();
    $input['module_id'] = $module_id;
    return UserModule::create($input);
  }

  public function putModuleAdmins($module_id, $id)
  {
    $input = Input::all();
    $userModule = UserModule::findOrFail($id);
    $userModule->update($input);
    return $input;
  }


  public function deleteModuleAdmins($module_id, $id)
  {
    $input = Input::all();
    $userModule = UserModule::findOrFail($id);
    $userModule->delete();
    return 'OK';
  }

  public function __construct()
  {
    View::share('controller', 'Module Admin');
  }

}