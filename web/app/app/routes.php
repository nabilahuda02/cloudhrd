<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::controller('auth','AuthController');

Route::get('/', function()
{
	return Redirect::to('wall');
});

Route::get('/reset', function(){
  Artisan::call('db:seed');
  if(file_exists(public_path() . '/uploads/temp')) {
    File::cleanDirectory(public_path() . '/uploads/temp');
  }
  return Redirect::action('leave.create');
});

Route::group(['before' => 'auth'], function(){

  View::share('user', Auth::user());
  Route::controller('wall','WallController');
  Route::resource('shares', 'SharesController');
  Route::resource('profile','ProfileController');
  Route::resource('profile_contacts','ProfileContactsController');
  Route::resource('profile_educations','ProfileEducationController');
  Route::resource('profile_emergencies','ProfileEmergencyController');
  Route::resource('profile_employment_history','ProfileEmploymentHistoryController');
  Route::resource('profile_family','ProfileFamilyController');

  /**
   * Modules
   */
  Route::resource('leave','LeaveController');
  Route::resource('medical','MedicalController');
  Route::resource('claims','GeneralClaimsController');
  Route::resource('booking','RoomBookingController');
  
  /**
   * FIXME: add filters
   */
  Route::group(['before' => 'administers_leave'], function(){
    Route::resource('leavetype', 'AdminLeaveTypeController');
    Route::resource('blockeddates', 'AdminLeaveBlockedDatesController');
    Route::get('/leave/admin/types', 'LeaveController@getAdminTypes');
    Route::get('/leave/admin/blocked-dates', 'LeaveController@getAdminBlockedDates');
    Route::get('/leave/admin/entitlements', 'LeaveController@getAdminEntitlement');
    Route::get('/leave/admin/entitlement/{user_id}', 'LeaveController@getAdminShowUserEntitlemnt');
    Route::post('/leave/admin/entitlement/{user_id}', 'LeaveController@postAdminShowUserEntitlemnt');
    Route::get('/leave/admin/reporting', 'LeaveController@getAdminReporting');
    Route::post('/leave/admin/reporting', 'LeaveController@postAdminReporting');
  });

  /**
   * FIXME: add filters
   */
  Route::group(['before' => 'administers_medical'], function(){
    Route::resource('medicaltype', 'AdminMedicalClaimTypeController');
    Route::get('/medical/admin/types', 'MedicalController@getAdminTypes');
    Route::get('/medical/admin/entitlements', 'MedicalController@getAdminEntitlement');
    Route::get('/medical/admin/entitlement/{user_id}', 'MedicalController@getAdminShowUserEntitlemnt');
    Route::post('/medical/admin/entitlement/{user_id}', 'MedicalController@postAdminShowUserEntitlemnt');
    Route::get('/medical/admin/reporting', 'MedicalController@getAdminReporting');
    Route::post('/medical/admin/reporting', 'MedicalController@postAdminReporting');
  });

  /**
   * FIXME: add filters
   */
  Route::group(['before' => 'administers_generalclaim'], function(){
    Route::resource('generalclaimtype', 'AdminGeneralClaimTypeController');
    Route::get('/claims/admin/types', 'GeneralClaimsController@getAdminTypes');
    Route::get('/claims/admin/reporting', 'GeneralClaimsController@getAdminReporting');
    Route::post('/claims/admin/reporting', 'GeneralClaimsController@postAdminReporting');
  });
  
  /**
   * FIXME: add filters
   */
  // Route::group(['before' => 'administers_bookings'], function(){
  //   Route::resource('timeslots', 'AdminLookupTimeslotsController');
  //   Route::resource('rooms', 'AdminRoomBookingRoomsController');
  //   Route::get('/booking/admin/timeslots', 'RoomBookingController@getTimeslot');
  //   Route::get('/booking/admin/rooms', 'RoomBookingController@getRooms');
  //   Route::get('/booking/admin/reporting', 'RoomBookingController@getAdminReporting');
  //   Route::post('/booking/admin/reporting', 'RoomBookingController@postAdminReporting');
  // });
  
  /**
   * FIXME: add filters
   */
  Route::group(['before' => 'isadmin'], function(){

    Route::controller('audits', 'AdminAuditController');

    Route::resource('unitadmin', 'AdminUnitController');
    Route::get('/unitadmin/view/chart', 'AdminUnitController@getChart');

    Route::resource('moduleadmin', 'AdminModuleController');
    Route::get('/moduleadmin/admins/{module_id}', 'AdminModuleController@getModuleAdmins');
    Route::post('/moduleadmin/admins/{module_id}', 'AdminModuleController@postModuleAdmins');
    Route::put('/moduleadmin/admins/{module_id}/{id}', 'AdminModuleController@putModuleAdmins');
    Route::delete('/moduleadmin/admins/{module_id}/{id}', 'AdminModuleController@deleteModuleAdmins');

    Route::resource('useradmin', 'AdminUserController');
    Route::get('/useradmin/change-password/{user_id}', 'AdminUserController@getChangePassword');
    Route::post('/useradmin/change-password/{user_id}', 'AdminUserController@postChangePassword');
    Route::controller('/useradminprofile', 'AdminUserProfileController');
    Route::resource('/organization', 'AdminOrganizationController');
  });

  Route::controller('ajax','AjaxController');
  Route::controller('data','DataController');
  Route::controller('upload','UploadController');
});

Route::controller('subscription', 'SubscriptionController');

Route::get('email_action/{hash}', function($hash) {
  $config = Helper::decrypt($hash);
  if(!$config)
    return Redirect::action('wall.index');
  
  switch ($config->type) {
    case 'leave':
      $item = Leave__Main::where('id', $config->id);
      break;
    case 'medical':
      $item = MedicalClaim__Main::where('id', $config->id);
      break;
    case 'general':
      $item = GeneralClaim__Main::where('id', $config->id);
      break;
  }

  $item = $item->where('status_id', $config->current_status)->first();
  if(!$item) {
    return 'Link no longer active';
  }

  $item->setStatus($config->next_status);
  if($config->type === 'leave' 
      && $config->next_status == 3
      && $item->leave_type_id == 1
    ) {
    $item->shares()->create([
      'type' => 'event',
      'user_id' => $item->user_id,
      'event_date' => $item->dates[0]->date,
      'title' => $item->user->profile->first_name . ' On Leave',
      'content' => $item->user->profile->first_name . ' will be on leave for ' . $item->total . ' day(s)'
    ]);
  }

  return 'Application status updated.';
});


Route::get('test', function(){
  return Share::with('comments', 'user', 'user.profile' , 'comments.user', 'comments.user.profile')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get()
                ->toJson();
});