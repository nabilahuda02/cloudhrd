<?php

class SubscriptionController extends BaseController
{

    public function getIndex()
    {
        global $app;
        $user = Auth::user();
        $master_user = $app->master_user;
        return View::make('subscription.index', compact('user', 'master_user'));
    }

    public function __construct()
    {
        View::share('controller', 'Subscription');
    }

}