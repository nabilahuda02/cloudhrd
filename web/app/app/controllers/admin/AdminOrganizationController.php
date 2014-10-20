<?php

class AdminOrganizationController extends BaseController
{

    public function index()
    {
        $locale = app()->user_locale;
        $user = Auth::user();
        $master_user = app()->master_user;
        return View::make('admin.organization.index', compact('user', 'master_user', 'locale'));
    }

    public function store()
    {
        $data = Input::all();
        $user = app()->master_user;
        $user->support_email = $data['support_email'];
        $locale = [
            'decimal_places' => $data['decimal_places'],
            'decimal_separator' => $data['decimal_separator'],
            'thousand_separator' => $data['thousand_separator'],
            'currency_symbol' => $data['currency_symbol'],
            'symbol_location' => $data['symbol_location'],
            'distance' => $data['distance'],
            'long_date' => $data['long_date'],
            'short_date' => $data['short_date'],
            'time_format' => $data['time_format'],
        ];
        $user->locale = json_encode($locale);
        if($user->save()) {
            DynamicDatabase::flush_cache();
            Session::flash('NotifySuccess', 'Organization Config Updated');
        } else {
            Session::flash('NotifyError', 'Error Updating Config');
        }
        return Redirect::back();
    }

    public function __construct()
    {
        View::share('controller', 'Organization');
    }

}