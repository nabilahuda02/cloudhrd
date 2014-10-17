<?php

class DashboardController extends BaseController {

	public function index()
	{

		$users = UserProfile::where('user_id','=','2')->get();
		$item = array(
			'users'=>$users
		);

		return View::make('dashboard.dashboard', $item);
	}


  public function __construct()
  {
    View::share('controller', 'Dashboard');
    View::share('hideSidebar', true);
  }

}