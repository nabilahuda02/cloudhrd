<?php

class AdminUserController extends \BaseController {

  /**
   * Display a listing of users
   *
   * @return Response
   */
  public function index()
  {
    $users = User::all();
    return View::make('admin.users.index', compact('users'));
  }

  /**
   * Show the form for creating a new user
   *
   * @return Response
   */
  public function create()
  {
    return View::make('admin.users.create');
  }

  /**
   * Store a newly created user in storage.
   *
   * @return Response
   */
  public function store()
  {

    $validator = Validator::make($data = Input::all(), User::$validation_rules['registration']);

    if ($validator->fails())
    {
      Session::flash('NotifyDanger', 'Error Creating User');
      return Redirect::back()->withErrors($validator)->withInput();
    }

    Session::flash('NotifySuccess', 'User Created Successfully');
    $data['verified'] = 1;
    $original_password = $data['password'];
    $data['password'] = Hash::make($data['password']);
    $user = User::create($data);
    $data['user_id'] = $user->id;
    $user->audits()->create([
      'type' => 1,
      'ref'  => $user->id,
      'data' => $user->toArray()
    ]);
    UserProfile::create($data);

    /**
     * Send welcome email
     * firstname
     * email
     * password
     * domain
     */
    
    Mail::send('emails.welcome', [
        'firstname' => $user->profile->first_name,
        'email'     => $user->email,
        'password'  => $original_password,
        'domain'    => URL::action('AuthController@getLogin')
      ], function($message) use ($user)
    {
      $message->to($user->email, User::fullName($user->id))->subject('Welcome to CloudHRD, ' . User::fullName($user->id));
    });

    return Redirect::route('useradmin.index');
  }

  public function show($id)
  {
    return Redirect::action('useradmin.edit', $id);
  }

  /**
   * Show the form for editing the specified user.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $currentuser = User::find($id);
    return View::make('admin.users.edit', compact('currentuser'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $user = User::findOrFail($id);
    $profile =  UserProfile::where('user_id', $user->id)->first();

    $data = Input::all();

    $validator = Validator::make($data = Input::all(), User::$validation_rules['edit']);

    if ($validator->fails())
    {
      Session::flash('NotifyDanger', 'Error Updating User');
      return Redirect::back()->withErrors($validator)->withInput();
    }

    Session::flash('NotifySuccess', 'User Updated Successfully');
    if(!isset($data['is_admin'])) {
      $data['is_admin'] = false;
    }
    $user->update($data);
    $user->audits()->create([
      'type' => 2,
      'ref'  => $user->id,
      'data' => $user->toArray()
    ]);
    $profile->update($data);

    return Redirect::route('useradmin.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($uid)
  {
    User::find($uid)->delete();
    Session::flash('NotifySuccess', 'User Deleted Successfully');
    return Redirect::route('useradmin.index');
  }


  public function getChangePassword($uid)
  {
    $currentuser = User::find($uid);
    return View::make('admin.users.changepw', compact('currentuser'));
  }

  public function postChangePassword($uid)
  {
    $currentuser = User::findOrFail($uid);

    $validator = Validator::make($data = Input::all(), User::$validation_rules['changepw']);

    if ($validator->fails())
    {
      Session::flash('NotifyDanger', 'Error Updating User Password');
      return Redirect::back()->withErrors($validator)->withInput();
    }

    Session::flash('NotifySuccess', 'User Password Updated Successfully');
    $currentuser->password = Hash::make($data['password']);
    $currentuser->save();

    return Redirect::action('useradmin.index');

  }


  public function __construct()
  {
    View::share('controller', 'User Admin');
    View::share('types', User::all());
  }

}