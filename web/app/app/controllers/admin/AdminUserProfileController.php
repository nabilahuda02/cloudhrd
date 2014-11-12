<?php

class AdminUserProfileController extends \BaseController
{

    public function getProfileContacts($user_profile_id)
    {
      return Profile__Contact::where('user_profile_id', $user_profile_id)->get();
    }

    public function postProfileContacts($user_profile_id)
    {
      $input = Input::all();
      $input['user_profile_id'] = $user_profile_id;
      return Profile__Contact::create($input);
    }

    public function putProfileContacts($user_profile_id, $id)
    {
      $contact = Profile__Contact::findOrFail($id);
      $input = Input::all();
      $contact->update($input);
      return $contact;
    }

    public function deleteProfileContacts($user_profile_id, $id)
    {
      $contact = Profile__Contact::findOrFail($id);
      $contact->delete();
      return 'OK';
    }


    public function getProfileEducations($user_profile_id)
    {
      return Profile__Education::where('user_profile_id', $user_profile_id)->get();
    }

    public function postProfileEducations($user_profile_id)
    {
      $input = Input::all();
      $input['user_profile_id'] = $user_profile_id;
      return Profile__Education::create($input);
    }

    public function putProfileEducations($user_profile_id, $id)
    {
      $contact = Profile__Education::findOrFail($id);
      $input = Input::all();
      $contact->update($input);
      return $contact;
    }

    public function deleteProfileEducations($user_profile_id, $id)
    {
      $contact = Profile__Education::findOrFail($id);
      $contact->delete();
      return 'OK';
    }


    public function getProfileEmergencies($user_profile_id)
    {
      return Profile__Emergency::where('user_profile_id', $user_profile_id)->get();
    }

    public function postProfileEmergencies($user_profile_id)
    {
      $input = Input::all();
      $input['user_profile_id'] = $user_profile_id;
      return Profile__Emergency::create($input);
    }

    public function putProfileEmergencies($user_profile_id, $id)
    {
      $contact = Profile__Emergency::findOrFail($id);
      $input = Input::all();
      $contact->update($input);
      return $contact;
    }

    public function deleteProfileEmergencies($user_profile_id, $id)
    {
      $contact = Profile__Emergency::findOrFail($id);
      $contact->delete();
      return 'OK';
    }


    public function getProfileEmploymentHistory($user_profile_id)
    {
      return Profile__EmploymentHistory::where('user_profile_id', $user_profile_id)->get();
    }

    public function postProfileEmploymentHistory($user_profile_id)
    {
      $input = Input::all();
      $input['user_profile_id'] = $user_profile_id;
      return Profile__EmploymentHistory::create($input);
    }

    public function putProfileEmploymentHistory($user_profile_id, $id)
    {
      $contact = Profile__EmploymentHistory::findOrFail($id);
      $input = Input::all();
      $contact->update($input);
      return $contact;
    }

    public function deleteProfileEmploymentHistory($user_profile_id, $id)
    {
      $contact = Profile__EmploymentHistory::findOrFail($id);
      $contact->delete();
      return 'OK';
    }


    public function getProfileFamily($user_profile_id)
    {
      return Profile__Family::where('user_profile_id', $user_profile_id)->get();
    }

    public function postProfileFamily($user_profile_id)
    {
      $input = Input::all();
      $input['user_profile_id'] = $user_profile_id;
      return Profile__Family::create($input);
    }

    public function putProfileFamily($user_profile_id, $id)
    {
      $contact = Profile__Family::findOrFail($id);
      $input = Input::all();
      $contact->update($input);
      return $contact;
    }

    public function deleteProfileFamily($user_profile_id, $id)
    {
      $contact = Profile__Family::findOrFail($id);
      $contact->delete();
      return 'OK';
    }

    public function getDeleteFile($file_id) {
      $upload = Upload::findOrFail($file_id);
      $upload->delete();
    }


}