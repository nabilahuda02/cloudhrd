<?php

class AuthController extends BaseController
{

    public function getIndex()
    {
        return Redirect::action('AuthController@getLogin');
    }

    public function getLogin()
    {
        Session::keep(array('user_id'));
        return View::make('auth.login');
    }

    public function getForgotPassword()
    {
        return View::make('auth.forgot-password');
    }

    public function postForgotPassword()
    {
        $email = Input::get('email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            return Redirect::back()
                ->withInput()
                ->with('NotifyWarning', "User with the email addres {$email} not found.");
        }
        $user->verify_token = str_random(128);
        $user->save();
        $this->_sendResetPasswordEmail($user);
        return Redirect::back()
            ->with('NotifySuccess', "We have emailed a reset password link to: {$email}");
    }

    public function getResetPassword($token = '')
    {
        $user = User::byToken($token);
        if ($user) {
            return View::make('auth.reset-password', compact('user', 'token'));
        }
        return Redirect::action('AuthController@getForgotPassword')
            ->with('NotifyWarning', 'Invalid Token');
    }

    public function postResetPassword($token = '')
    {
        $user = User::byToken($token);
        if ($user) {
            $validator = Validator::make($data = Input::all(), User::$validation_rules['changepw']);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $user->password = Hash::make(Input::get('password'));
            $user->verify_token = null;
            $user->save();
            return Redirect::action('AuthController@getLogin')
                ->with('NotifySuccess', 'Password reset successful. Login with your new password.');
        }
        return Redirect::action('AuthController@getForgotPassword')
            ->with('NotifyWarning', 'Invalid Token');
    }

    public function postLogin()
    {
        $email = Input::get('email');
        $password = Input::get('password');
        if (Auth::attempt(compact('email', 'password'))) {
            if (!Auth::user()->verified) {
                Session::flash('user_id', Auth::user()->id);
                Auth::logout();
                return Redirect::back()
                    ->with('NotifyDanger', 'Email has not yet been verified. Click ' . link_to_action('AuthController@getResend', 'here') . ' to resend verification email.');
            }
            return Redirect::action(Auth::user()->loginAction());
        }
        return Redirect::back()
            ->with('NotifyDanger', 'Invalid email or password');
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::action('AuthController@getLogin');
    }

    public function getRegister()
    {
        return View::make('auth.register');
    }

    public function postRegister()
    {

        $validator = Validator::make($data = Input::all(), User::$validation_rules['registration']);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->unit_id = 1;
        $user->verify_token = md5(Hash::make(time() . $user->password));
        $user->save();

        $user_profile = new UserProfile();
        $user_profile->first_name = $data['first_name'];
        $user_profile->last_name = $data['last_name'];
        $user_profile->user_id = $user->id;
        $user_profile->user_image = '/images/user.png';
        $user_profile->save();

        Event::fire('user.created', array($user));
        $this->_sendVerificationEmail($user);

        return Redirect::action('AuthController@getLogin')
            ->with('NotifyInfo', 'Registration Successful. An email will be sent to you shortly to verify your email address. Please click on the link inside the email.');

    }

    public function getResend()
    {

        $user_id = Session::get('user_id');

        if (!$user_id) {
            return Redirect::action('AuthController@getLogin');
        }

        $user = User::find($user_id);

        $this->_sendVerificationEmail($user);

        return Redirect::action('AuthController@getLogin')
            ->with('NotifyInfo', 'An email will be sent to you shortly to verify your email address. Please click on the link inside the email.');

    }

    public function getVerify($token = null)
    {

        if ($token) {
            $user = User::byToken($token);
            if ($user) {
                if ($user->verified === 0) {
                    $user->verified = 1;
                    $user->save();
                    Event::fire('user.verified', array($user));

                    $user->audits()->create([
                        'type' => 1,
                        'ref' => $user->id,
                        'data' => $user->toArray(),
                    ]);

                    return Redirect::action('AuthController@getLogin')
                        ->with('NotifySuccess', 'Email verified. Please Login');
                }
            }
        }
        return Redirect::action('AuthController@getLogin')
            ->with('NotifyDanger', 'Error validating email address.');
    }

    public function getUnlist($token = null)
    {

        if ($token) {
            $user = User::byToken($token);
            if ($user) {
                if ($user->verified === 0) {
                    $user->delete();
                    return Redirect::action('AuthController@getLogin')
                        ->with('NotifySuccess', 'Email unlisted. Thank you');
                }
            }
        }
        return Redirect::action('AuthController@getLogin');

    }

    private function _sendVerificationEmail(User $user)
    {

        Mail::send('emails.auth.verify', [
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name,
            'token' => $user->verify_token,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->profile->first_name . ' ' . $user->profile->last_name)->subject('Welcome to CloudHRD!');
        });
    }

    private function _sendResetPasswordEmail(User $user)
    {
        Mail::send('emails.auth.reset-password', [
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name,
            'token' => $user->verify_token,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->profile->first_name . ' ' . $user->profile->last_name)->subject('Reset Password for CloudHRD');
        });
    }

    public function getMigrate()
    {
        $from = app_path() . '/database/automigrations/';
        $to = app_path() . '/database/donemigrations/';
        $dbs = array_filter(Master__User::all()->lists('database'));
        $dbs[] = 'cloudhrd_app';
        $messages = '';

        foreach (scandir($from) as $file) {
            if (!in_array($file, ['.', '..']) && !file_exists($to . $file)) {
                $messages .= "Migrating {$file}...\n";
                foreach ($dbs as $db) {
                    $command = 'mysql -f -h 127.0.0.1 -u root ' . $db . ' < ' . $from . $file;
                    $messages .= "DB: {$db}\n Command: {$command}\n.....\n";
                    shell_exec($command);
                }
                touch($to . $file);
            }
        }

        $messages .= "Done! \n\n<a href=\"/\">Home</a>";

        return $messages;

    }

    public function __construct()
    {
        View::share('controller', 'Authentication');
        Asset::pull('css', 'application');
        Asset::push('css', 'login');
    }

}
