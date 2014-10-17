<?php

class Profile__Emergency extends \Eloquent {
  protected $table = 'user_profile_emergency_contacts';
	protected $fillable = ['name', 'phone', 'address', 'user_profile_id'];
  public static $rules = [
    'name' => 'required',
    'phone' => 'required',
    'address' => 'required',
    'user_profile_id' => 'required'
  ];
}