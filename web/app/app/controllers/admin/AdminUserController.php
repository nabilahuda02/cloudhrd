<?php

class AdminUserController extends \BaseController
{

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

        if ($validator->fails()) {
            Session::flash('NotifyDanger', 'Error Creating User');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $this->createUser($data);

        return Redirect::route('useradmin.index');
    }

    public function assume($user_id)
    {
        Session::set('original_user_id', Auth::user()->id);
        $user = User::find($user_id);
        Auth::login($user);
        return Redirect::to('/');
    }

    private function createUser($data)
    {
        Session::flash('NotifySuccess', 'User Created Successfully');
        $data['verified'] = 1;
        $password = str_random();
        $data['password'] = Hash::make($password);
        $user = User::create($data);
        $data['user_id'] = $user->id;
        $user->audits()->create([
            'type' => 1,
            'ref' => $user->id,
            'data' => $user->toArray(),
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
            'email' => $user->email,
            'password' => $password,
            'domain' => URL::action('AuthController@getLogin'),
        ], function ($message) use ($user) {
            $message->to($user->email, User::fullName($user->id))->subject('Welcome to CloudHRD, ' . User::fullName($user->id));
        });
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
        $profile = UserProfile::where('user_id', $user->id)->first();

        $data = Input::all();

        $validator = Validator::make($data = Input::all(), User::$validation_rules['edit']);

        if ($validator->fails()) {
            Session::flash('NotifyDanger', 'Error Updating User');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Session::flash('NotifySuccess', 'User Updated Successfully');
        if (!isset($data['is_admin'])) {
            $data['is_admin'] = false;
        }
        $user->update($data);
        $user->audits()->create([
            'type' => 2,
            'ref' => $user->id,
            'data' => $user->toArray(),
        ]);
        $profile->update($data);

        return Redirect::route('useradmin.edit', $user->id);
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

        if ($validator->fails()) {
            Session::flash('NotifyDanger', 'Error Updating User Password');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Session::flash('NotifySuccess', 'User Password Updated Successfully');
        $currentuser->password = Hash::make($data['password']);
        $currentuser->save();

        return Redirect::action('useradmin.index');

    }

    public function getManageTemplate()
    {
        $custom_fields = app()->user_locale->profile_custom_fields;
        return View::make('admin.users.user-profile-template', compact('custom_fields'));
    }

    public function postManageTemplate()
    {
        $data = Input::all();
        $user = app()->master_user;
        $locale = app()->user_locale;
        unset($data['_token']);
        $locale->profile_custom_fields = $data;
        $user->locale = json_encode($locale);
        if ($user->save()) {
            Session::flash('NotifySuccess', 'User Template Updated');
        } else {
            Session::flash('NotifyDanger', 'Error Updating User Template');
        }
        return Redirect::action('AdminUserController@getManageTemplate');
    }

    public function getImportUsers()
    {
        $custom_fields = app()->user_locale->profile_custom_fields;
        return View::make('admin.users.import-users', compact('custom_fields'));
    }

    public function postImportUsers()
    {
        $file = Input::file('userimport');
        $users = Excel::load($file->getPathname())->toArray();

        // check duplicate emails

        $emails = array_pluck($users, 'email');
        if (count(array_unique($emails)) < count($emails)) {
            $values = array_count_values($emails);
            $not_uniques = [];
            foreach ($values as $key => $value) {
                if ($value > 1) {
                    $not_uniques[] = $key;
                }
            }
            $error = 'These emails have duplicates: <ul>' . implode('', array_map(function ($not_unique) {return '<li>' . $not_unique . '</li>';}, $not_uniques)) . '<ul>';
            Session::flash('NotifyDanger', $error);
            return Redirect::back();
        }

        $error_rows = 0;
        $error_messages = [];

        foreach ($users as $index => $user) {
            $validator = Validator::make($user, User::$validation_rules['registration']);
            if ($validator->fails()) {
                $error_rows++;
                $error_messages[] = 'Error on row ' . ($index + 2) . ' (' . $user['first_name'] . ') <ul>' . implode('', $validator->messages()->all('<li>:message</li>')) . '</ul>';
            }
        }

        $groups = [];
        $error_groups = [];
        $custom_fields = app()->user_locale->profile_custom_fields;
        foreach (array_pluck($users, 'unit') as $index => $unit) {
            $unit = trim($unit);
            if (in_array($unit, $error_groups)) {
                $error_rows++;
                $error_messages[] = 'Error on row ' . ($index + 2) . ' (' . $users[$index]['first_name'] . '). Group "' . $users[$index]['unit'] . '" does not exist.<br/>';
            } else if (!in_array($unit, $groups)) {
                $group = UserUnit::where('name', $unit)->first();
                if (!$group) {
                    $error_rows++;
                    $error_messages[] = 'Error on row ' . ($index + 2) . ' (' . $users[$index]['first_name'] . '). Group "' . $users[$index]['unit'] . '" does not exist.<br/>';
                    $error_groups[] = $unit;
                } else {
                    $users[$index]['unit_id'] = $group->id;
                }
            } else {
                $users[$index]['unit_id'] = $groups[$unit]->id;
            }

            // unset unit
            unset($users[$index]['unit']);
            foreach ($custom_fields as $k => $field) {
                $key = strtolower(str_replace(' ', '_', $field));
                if (isset($users[$index][$key])) {
                    $users[$index][$k] = $users[$index][$key];
                    unset($users[$index][$key]);
                }
            }
            $users[$index]['is_admin'] = ($users[$index]['is_admin'] === 'Yes') ? true : false;
        }

        if ($error_rows > 0) {
            $error = 'Upload aborted.<br/>' . implode('', $error_messages);
            Session::flash('NotifyDanger', $error);
            return Redirect::back();
        }

        foreach ($users as $data) {
            $this->createUser($data);
        }

        Session::flash('NotifySuccess', count($users) . ' Users Imported');
        return Redirect::back();
    }

    public function getDownloadTemplate()
    {
        $fields = [
            'Email',
            'First Name',
            'Last Name',
            'Address',
            'Unit',
            'Is Admin',
            'Password',
        ];
        $custom_fields = app()->user_locale->profile_custom_fields;
        foreach ($custom_fields as $field) {
            if ($field) {
                $fields[] = $field;
            }

        }
        return Excel::create('User Import Template', function ($excel) use ($fields) {
            $excel->sheet('User', function ($sheet) use ($fields) {
                $sheet->fromArray($fields);
            });
        })->export('xlsx');
    }

    public function __construct()
    {
        View::share('controller', 'User Admin');
        View::share('types', User::all());
    }

}
