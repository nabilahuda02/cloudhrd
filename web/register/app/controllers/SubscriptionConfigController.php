<?php

class SubscriptionConfigController extends BaseController
{


    public function index()
    {
        $config = Config::get('subscriptions');
        return View::make('admin.subscription-config.index', compact('config'));
    }

    public function update()
    {
        $rules = [
            'trial_with_reseller_code' => 'required|numeric',
            'trial_without_reseller_code' => 'required|numeric',
            'per_user' => 'required|numeric',
            'currency' => 'required|alpha|size:3',
            'minimum_user' => 'required|numeric',
            'subscription_duration' => 'required|numeric'
        ];
        $validator = Validator::make($input = Input::all(), $rules);
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('notification:danger', 'Validation Error');
        } else {
            unset($input['_method']);
            unset($input['_token']);
            $input['trial_with_reseller_code'] = intval($input['trial_with_reseller_code']);
            $input['trial_without_reseller_code'] = intval($input['trial_without_reseller_code']);
            $input['per_user'] = intval($input['per_user']);
            $input['minimum_user'] = intval($input['minimum_user']);
            $input['subscription_duration'] = intval($input['subscription_duration']);
            $input['currency'] = strtoupper($input['currency']);
            $config = '<?php ' . "\r\n\r\n" . 'return ' . var_export($input, true) . ';';
            file_put_contents(app_path() . '/config/subscriptions.php', $config);
            return Redirect::back()
                ->with('notification:success', 'Subscription Config Updated');
        }
    }

    public function __construct() {
        parent::__construct();
        View::share('SubscriptionConfig', 'Upload');
    }

}