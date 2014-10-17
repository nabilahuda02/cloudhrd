<?php

class UsersController extends \BaseController
{

    public $set_password_message            = 'Password set succesfully.';
    public $set_confirmation_message        = 'Activation set succesfully.';
    public $change_password_invalid_message = 'Invalid Old Password.';
    public $change_password_message         = 'Password changed succesfully.';

    /**
     * Display a listing of users
     *
     * @return Response
     */
    public function index()
    {
        if (!User::canList()) {
            return $this->_access_denied();
        }
        if (Request::ajax()) {
            $users_under_me = Auth::user()->getAuthorizedUserids(User::$show_authorize_flag);
            $query = Input::get('query');
            if (empty($users_under_me)) {
                $users = User::whereNotNull('users.created_at');
            } else {
                $users = User::whereIn('users.id', $users_under_me);
            }
            if(isset($query['type'])) {
                Log::info($query['type']);
                $roles = [0];
                switch ($query['type']) {
                    case 'subscribers':
                        $roles = [7];
                        break;
                    case 'resellers':
                        $roles = [6];
                        break;
                    case 'backend-users':
                        $roles = [1,2,3,4,5,8];
                        break;
                }

                $users->whereHas('roles', function($q) use ($roles) {
                    $q->whereIn('roles.id', $roles);
                });
            }
            $users = $users
                ->select(['users.id', 'users.name as "user_name"', 'organization_units.name', DB::raw('group_concat(assigned_roles.role_id) as role'), 'users.confirmed'])
                ->leftJoin('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                ->leftJoin('organization_units', 'organization_units.id', '=', 'users.organization_unit_id')
                ->groupBy('users.id');
            return Datatables::of($users)
                ->edit_column('role', function($row){
                    $roles = array_filter(explode(',',$row['role']));
                    if(in_array(7, $roles)) {
                        return 'Customer';
                    } else if (in_array(6, $roles)) {
                        return 'Reseller';
                    } else if (array_intersect([1,2,3,4,5,8], $roles)) {
                        return 'Admin';
                    }
                    return 'Unknown';
                })
                ->edit_column('confirmed', function($row){
                    return $row['confirmed'] ? 'Confirmed' : 'Not Confirmed';
                })
                ->add_column('actions', '{{View::make("users.actions-row", compact("id", "confirmed"))->render()}}')
                ->remove_column('id')
                ->make();
            return Datatables::of($users)->make();
        }
        Asset::push('js', 'datatables');
        return View::make('users.index');
    }

    /**
     * Show the form for creating a new user
     *
     * @return Response
     */
    public function create()
    {
        if (Request::ajax()) {
            return $this->_ajax_denied();
        }
        if (!User::canCreate()) {
            return $this->_access_denied();
        }
        return View::make('users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @return Response
     */
    public function store()
    {
        User::setRules('store');
        $data = Input::all();
        $roles = $data['roles'];
        if (!User::canCreate()) {
            return $this->_access_denied();
        }
        if(in_array('7', $data['roles'])) {
            $data['database'] = User::generateDatabaseName();
        }
        if(array_intersect(['1','2','3','4','5','6','8'], $data['roles'])) {
            User::setRules('reseller');
            if(in_array('6', $data['roles'])) {
                $data['reseller_code'] = User::getResellerCode();
            }
        }
        $data['confirmed'] = 1;
        $user = new User;
        unset($data['roles']);
        if(app()->env === 'testing') {
            User::setRules('testing');
            unset(User::$rules['domain']);
            unset($data['password_confirmation']);
        }
        $user->fill($data);
        if (!$user->save()) {
            return $this->_validation_error($user);
        }
        $user->roles()->sync($roles);
        if(in_array('7', $roles)) {
            $subscriptions = Config::get('subscriptions');
            $trial = $subscriptions['trial_without_reseller_code'];
            $duration = $subscriptions['subscription_duration'];
            if(Input::has('reseller_id') && $reseller = User::find(Input::get('reseller_id'))) {
                $user->reseller()->associate($reseller);
                $trial = $subscriptions['trial_with_reseller_code'];
            }
            $user->subscriptions()->save(new Subscription([
                'staff_count' => $subscriptions['minimum_user'],
                'start_date' => date('Y-m-d'),
                'end_date' => Carbon::create()
                    ->addMonths($trial)
                    ->toDateString(),
                'unit_price' => $subscriptions['per_user'],
                'is_trial' => true
            ]));
            $user->cloudhrdmail('welcome', 'Welcome to CloudHRD, ' . $user->name);
        }
        if (Request::ajax()) {
            return Response::json($user, 201);
        }
        return Redirect::route('users.index')
            ->with('notification:success', $this->created_message);
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        if (!$user->canShow()) {
            return $this->_access_denied();
        }
        if (Request::ajax()) {
            return $user;
        }
        Asset::push('js', 'show');
        return View::make('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (Request::ajax()) {
            return $this->_ajax_denied();
        }
        if (!$user->canUpdate()) {
            return $this->_access_denied();
        }
        return View::make('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $user = User::findOrFail($id);
        $data = Input::all();
        if (!$user->canUpdate()) {
            return $this->_access_denied();
        }
        User::setRules('update');
        $user->fill($data);
        if (!$user->updateUniques()) {
            return $this->_validation_error($user);
        }
        $data['roles'] = isset($data['roles'])?$data['roles']:[];
        $user->roles()->sync($data['roles']);
        if (Request::ajax()) {
            return $user;
        }
        Session::remove('_old_input');
        return Redirect::route('users.edit', $id)
            ->with('notification:success', $this->updated_message);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (!$user->canDelete()) {
            return $this->_access_denied();
        }
        if (!$user->delete()) {
            return $this->_delete_error();
        }
        if (Request::ajax()) {
            return Response::json($this->deleted_message);
        }
        return Redirect::route('users.index')
            ->with('notification:success', $this->deleted_message);
    }

    /**
     * ====================================================================================================================
     * Additional methods
     * ====================================================================================================================
     */

    public function profile()
    {
        return View::make('users.profile', ['controller' => 'Profile', 'user' => Auth::user()]);
    }

    public function getSetPassword($id)
    {
        $user = User::findOrFail($id);
        if (Request::ajax()) {
            return $this->_ajax_denied();
        }
        if (!$user->canSetPassword()) {
            return $this->_access_denied();
        }
        return View::make('users.set-password', compact('user'));
    }

    public function putSetPassword($id)
    {
        $user = User::findOrFail($id);
        $data = Input::all();
        if (!$user->canSetPassword()) {
            return $this->_ajax_denied();
        }
        User::setRules('setPassword');
        if (!$user->update($data)) {
            $this->_validation_error($user);
        }
        if (Request::ajax()) {
            return Response::json($this->set_password_message);
        }
        return Redirect::action('users.show', $user->id)
                                                   ->with('notification:success', $this->set_password_message);
    }

    public function putSetConfirmation($id = null)
    {
        $user = User::findOrFail($id);
        $data = Input::all();
        if (!$user->canSetConfirmation()) {
            return $this->_access_denied();
        }
        User::setRules('setConfirmation');
        if (!$user->update($data)) {
            return $this->_validation_error($user);
        }
        if (Request::ajax()) {
            return Response::json($this->set_confirmation_message);
        }
        return Redirect::action('users.show', $user->id)
                                                   ->with('notification:success', $this->set_confirmation_message);
    }

    public function getChangePassword()
    {
        $user = Auth::user();
        if (!$user->canSetPassword()) {
            return $this->_access_denied();
        }
        return View::make('users.change-password', compact('user'));
    }

    public function putChangePassword()
    {
        $user = Auth::user();
        $data = Input::all();
        if (!$user->canSetPassword()) {
            return $this->_access_denied();
        }
        User::setRules('changePassword');
        if (!Hash::check($data['old_password'], $user->password)) {
            if (Request::ajax()) {
                return Response::json($this->change_password_invalid_message, 400);
            }
            return Redirect::back()
                ->withErrors($user->validationErrors)
                ->withInput()
                ->with('notification:danger', $this->change_password_invalid_message);
        }
        if (!$user->update($data)) {
            return $this->_validation_error($user);
        }
        if (Request::ajax()) {
            return Response::json($this->set_password_message);
        }
        return Redirect::action('UsersController@profile', $user->id)
            ->with('notification:success', $this->set_password_message);
    }

    public function resetResellerCode($user_id)
    {
        $user = Auth::user()->ability(['Admin', 'User Admin'], []) 
            ? User::find($user_id) 
            : Auth::user();
        $user->reseller_code = User::getResellerCode();
        $user->save();
        return Redirect::back();
    }

    public function __construct()
    {
        parent::__construct();
        View::share('controller', 'UsersController');
    }

}
