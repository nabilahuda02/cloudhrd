<?php

/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
 */

class AuthController extends BaseController
{

    public function index()
    {
        return View::make('users.index');
    }

    /**
     * Displays the form for account creation
     *
     */
    public function create()
    {
        $domain = Input::get('domain');
        return View::make(Config::get('confide::signup_form'), compact('domain'));
    }

    /**
     * Stores new account
     *
     */
    public function store()
    {
        $user = new User;

        $user->name     = Input::get('name');
        $user->domain   = Input::get('domain');
        $user->database = User::generateDatabaseName();
        $user->username = Input::get('username');
        $user->email    = Input::get('email');
        $user->password = Input::get('password');
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        // 
        if(app()->env !== 'testing') {
            User::setRules('store');
            $user->password_confirmation = Input::get('password_confirmation');
        } else {
            User::setRules('testing');
        }


        // Save if valid. Password field will be hashed before save
        $user->save();

        if ($user->getKey()) {
            $user->roles()->sync([7]);
            $subscriptions = Config::get('subscriptions');
            $trial = $subscriptions['trial_without_reseller_code'];
            $duration = $subscriptions['subscription_duration'];
            if(Input::has('reseller_code') && $reseller = User::where('reseller_code', Input::get('reseller_code'))->first()) {
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
            $notice = Lang::get('confide::confide.alerts.account_created').' '.Lang::get('confide::confide.alerts.instructions_sent');
            return Redirect::action('AuthController@login')
                ->with('notice', $notice);
        } else {
            // Get validation errors (see Ardent package)
            $error = $user->errors()->all(':message');

            return Redirect::action('AuthController@create')
                ->withInput(Input::except('password'))
                ->with('error', $error);
        }
    }

    /**
     * Displays the login form
     *
     */
    public function login()
    {
        if (Confide::user()) {
            $user = Auth::user();
            // If user is logged, redirect to internal
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        } else {
            return View::make(Config::get('confide::login_form'));
        }
    }

    /**
     * Attempt to do login
     *
     */
    public function doLogin()
    {
        $input = array(
            'email'    => Input::get('email'), // May be the username too
            'username' => Input::get('email'), // so we have to pass both
            'password' => Input::get('password'),
            'remember' => Input::get('remember'),
        );

        if (Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            $user = Auth::user();
            if([7] === $user->roles->lists('id')) {
                $token = LoginToken::firstOrNew([
                    'domain' => $user->domain
                ]);
                $token->token = str_random(40);
                $token->save();
                Auth::logout();
                return Redirect::away('http://' . $user->domain . '/wall/index?token=' . $token->token);
            }
            return Redirect::intended('/');
        } else {
            $user = new User;

            // Check if there was too many login attempts
            if (Confide::isThrottled($input)) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif ($user->checkUserExists($input) and !$user->isConfirmed($input)) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

            return Redirect::action('AuthController@login')
                ->withInput(Input::except('password'))
                ->with('error', $err_msg);
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param    string  $code
     */
    public function confirm($code)
    {
        if (Confide::confirm($code)) {
            $user = User::where('confirmation_code', $code)->first();
            $user->cloudhrdmail('welcome', 'Welcome to CloudHRD, ' . $user->name);
            return Redirect::away('http://' . $user->domain);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
            return Redirect::action('AuthController@login')
                ->with('error', $error_msg);
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function forgotPassword()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function doForgotPassword()
    {
        if (Confide::forgotPassword(Input::get('email'))) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
            return Redirect::action('AuthController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
            return Redirect::action('AuthController@forgotPassword')
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function resetPassword($token)
    {
        return View::make(Config::get('confide::reset_password_form'))
            ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     */
    public function doResetPassword()
    {
        $input = array(
            'token'                 => Input::get('token'),
            'password'              => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        );

        User::setRules('resetPassword');

        // By passing an array with the token, password and confirmation
        if (Confide::resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::action('AuthController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::action('AuthController@resetPassword', array('token' => $input['token']))
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout()
    {
        Confide::logout();
        return Redirect::to('/');
    }

    public function __construct()
    {
        parent::__construct();
        View::share('controller', 'AuthController');
        Asset::push('js', 'login');
        Asset::push('css', 'login');
    }

}
