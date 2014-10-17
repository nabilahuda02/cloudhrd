<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::action('AuthController@login')
				->with('notification:warning', 'Please login to continue.');
		}
	}
});

Route::filter('hasRole', function($route, $request, $role)
{
	$user = Auth::user();
	if($user) 
	{
		if($user->hasRole($role) || $user->hasRole('Admin')) 
		{
			return;	
		}
	}
	if (Request::ajax())
	{
		return Response::make('Unauthorized', 401);
	}
	return Redirect::to('/')
		->with('notification:danger', 'Access denied.');
});

Route::filter('can', function($route, $request, $ability)
{
	$abilityParts = explode(':', $ability);
	$role = array_shift($abilityParts) . ' Admin';
	$user = Auth::user();
	if($user) 
	{
		if($user->ability([$role, 'Admin'], [$ability]))
		{
			return;	
		}
	}
	if (Request::ajax())
	{
		return Response::make('Unauthorized', 401);
	}
	return Redirect::to('/')
		->with('notification:danger', 'Access denied.');
});

Route::filter('canList', function($route, $request, $model){
	if(call_user_func([$model,'canList']))
		return;
	if (Request::ajax())
	{
		return Response::make('Unauthorized', 401);
	}
	return Redirect::to('/')
		->with('notification:danger', 'Access denied.');
});

Route::filter('admin', function()
{
	$user = Auth::user();
	if($user) 
	{
		if($user->hasRole('Admin')) 
		{
			return;	
		}
	}
	if (Request::ajax())
	{
		return Response::make('Unauthorized', 401);
	}
	return Redirect::to('/')
		->with('notification:danger', 'Access denied.');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


Route::filter('no_xhr', function()
{
	if(Request::ajax())
	{
		return Response::json("Bad request", 400);
	}
});

Route::filter('only_xhr', function()
{
	if(!Request::ajax())
	{
		return Redirec::back()
			->with('notification:error', 'Access denied.');
	}
});

