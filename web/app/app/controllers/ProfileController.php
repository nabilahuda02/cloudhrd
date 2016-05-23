<?php

class ProfileController extends \BaseController
{

    public function requestUpdate()
    {
        $currentuser = Auth::user();
        return View::make('changerequests.update', compact('currentuser'));
    }

    public function doUpdateProfile()
    {
        $data = Input::all();
        unset($data['_token']);
        $updates = [];
        foreach ($data['update'] as $key => $field_name) {
            $updates[] = new ChangeRequest__Item([
                'field_name' => $field_name,
                'key' => $key,
                'old_value' => Input::get('old_value.' . $key),
                'new_value' => Input::get('new_value.' . $key),
            ]);
        }
        $cr = ChangeRequest__Main::create([
            'user_id' => Auth::user()->id,
            'status_id' => 1,
        ]);
        $cr->ref = 'CR-' . $cr->id;
        $cr->save();
        $cr->items()->saveMany($updates);

        $cr->setStatus(1);

        return Redirect::action('WallController@getProfile')
            ->with('NotifySuccess', 'Change Request ' . $cr->ref . ' created and pending approval.');
    }

    public function __construct()
    {
        View::share('controller', 'My Profile');
    }

}
