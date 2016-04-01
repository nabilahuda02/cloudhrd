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

Route::controller('auth', 'AuthController');

Route::get('/', function () {
    return Redirect::to('wall');
});

Route::group(['before' => 'auth'], function () {

    View::share('user', Auth::user());
    Route::controller('wall', 'WallController');
    Route::resource('shares', 'SharesController');
    Route::resource('profile', 'ProfileController');
    Route::resource('profile_contacts', 'ProfileContactsController');
    Route::resource('profile_educations', 'ProfileEducationController');
    Route::resource('profile_emergencies', 'ProfileEmergencyController');
    Route::resource('profile_employment_history', 'ProfileEmploymentHistoryController');
    Route::resource('profile_family', 'ProfileFamilyController');
    Route::get('/update-profile', 'ProfileController@requestUpdate');
    Route::post('/update-profile', 'ProfileController@doUpdateProfile');

    /**
     * Modules
     */
    Route::resource('leave', 'LeaveController');
    Route::resource('medical', 'MedicalController');
    Route::resource('claims', 'GeneralClaimsController');
    Route::resource('tasks', 'TasksController');
    Route::resource('payrolls', 'PayrollsController');

    Route::get('tasks/{task_id}/set-tag/{tag_id}', 'TasksController@setTag');
    Route::get('tasks/{task_id}/notes', 'TasksController@notes');
    Route::post('tasks/{task_id}/notes', 'TasksController@newNote');
    Route::put('tasks/{note_id}/notes', 'TasksController@updateNote');
    Route::delete('tasks/{note_id}/notes', 'TasksController@deleteNote');

    Route::get('tasks/{task_id}/add-follower/{user_id}', 'TasksController@addFollower');
    Route::get('tasks/{task_id}/remove-follower/{user_id}', 'TasksController@removeFollower');
    Route::get('tasks/{task_id}/set-owner/{user_id}', 'TasksController@setOwner');

    Route::resource('taskinfo', 'TaskInfoController');
    Route::resource('subtasks', 'TaskSubtasksController');
    Route::get('/subtasks/{subtask_id}/set-done', 'TaskSubtasksController@setDone');
    Route::get('/subtasks/{subtask_id}/set-undone', 'TaskSubtasksController@setUndone');

    Route::resource('task-categories', 'TaskCategoriesController');
    Route::resource('task-tags', 'TaskTagsController');
    Route::put('/task-tags/{tag_id}/update-name', 'TaskTagsController@updateName');
    Route::post('/task/set-order/{tag_category_id}', 'TasksController@setOrder');
    Route::post('/task/{tag_category_id}/set-archived', 'TasksController@setArchived');
    Route::post('/task/{tag_category_id}/unset-archived', 'TasksController@unsetArchived');

    Route::get('/task/stream', 'TasksController@streamTask');

    /**
     * FIXME: add filters
     */
    Route::group(['before' => 'administers_leave'], function () {
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
    Route::group(['before' => 'administers_medical'], function () {
        Route::resource('medicaltype', 'AdminMedicalClaimTypeController');
        Route::get('/medical/admin/types', 'MedicalController@getAdminTypes');
        Route::get('/medical/admin/entitlements', 'MedicalController@getAdminEntitlement');
        Route::get('/medical/admin/entitlement/{user_id}', 'MedicalController@getAdminShowUserEntitlemnt');
        Route::post('/medical/admin/entitlement/{user_id}', 'MedicalController@postAdminShowUserEntitlemnt');
        Route::get('/medical/admin/reporting', 'MedicalController@getAdminReporting');
        Route::post('/medical/admin/reporting', 'MedicalController@postAdminReporting');
        Route::get('/medical/{claim_id}/toggle-paid', 'MedicalController@togglePaid');
    });

    /**
     * FIXME: add filters
     */
    Route::group(['before' => 'administers_generalclaim'], function () {
        Route::resource('generalclaimtype', 'AdminGeneralClaimTypeController');
        Route::get('/claims/admin/types', 'GeneralClaimsController@getAdminTypes');
        Route::get('/claims/admin/reporting', 'GeneralClaimsController@getAdminReporting');
        Route::post('/claims/admin/reporting', 'GeneralClaimsController@postAdminReporting');
        Route::get('/claim/{claim_id}/toggle-paid', 'GeneralClaimsController@togglePaid');
    });

    /**
     * FIXME: add filters
     */
    Route::group(['before' => 'administers_payroll'], function () {
        Route::get('/payroll/generate', 'PayrollsController@generate');
        Route::post('/payroll/generate', 'PayrollsController@doGenerate');
    });

    /**
     * FIXME: add filters
     */
    Route::group(['before' => 'isadmin'], function () {

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
        Route::get('/useradmin/assume/{user_id}', 'AdminUserController@assume');
        Route::post('/useradmin/change-password/{user_id}', 'AdminUserController@postChangePassword');
        Route::controller('/useradminprofile', 'AdminUserProfileController');
        Route::resource('/organization', 'AdminOrganizationController');
        Route::get('/manage-user-template', 'AdminUserController@getManageTemplate');
        Route::post('/manage-user-template', 'AdminUserController@postManageTemplate');
        Route::get('/import-users', 'AdminUserController@getImportUsers');
        Route::get('/download-import-template', 'AdminUserController@getDownloadTemplate');
        Route::post('/import-users', 'AdminUserController@postImportUsers');
        Route::controller('subscription', 'SubscriptionController');
    });

    Route::controller('ajax', 'AjaxController');
    Route::controller('data', 'DataController');
    Route::controller('upload', 'UploadController');
});

Route::get('/resume', function () {
    if ($user_id = Session::pull('original_user_id')) {
        Auth::login(User::find($user_id));
        return Redirect::action('AdminUserController@index');
    }
    return Redirect::to('/');
});

Route::get('email_action/{hash}', function ($hash) {
    $config = Helper::decrypt($hash);
    if (!$config) {
        return Redirect::action('wall.index');
    }

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
    if (!$item) {
        return 'Link no longer active';
    }
    $item->setStatus($config->next_status);
    return 'Application status updated.';
});

Route::get('/backend/migratedb', 'AuthController@getMigrate');
Route::get('/backend/reset', function () {
    Artisan::call('db:seed');
    File::cleanDirectory(public_path() . '/uploads');
    file_put_contents(public_path() . '/uploads/.gitignore', "*\n!.gitignore\n!index.html");
    touch(public_path() . '/uploads/index.html');
    return Redirect::action('leave.create');
});
