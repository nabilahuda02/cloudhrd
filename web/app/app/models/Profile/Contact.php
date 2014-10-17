<?php

class Profile__Contact extends \Eloquent {
  protected $table = 'user_profile_contacts';
	protected $fillable = ['name', 'number', 'user_profile_id'];
  public static $rules = [
    'name' => 'required',
    'number' => 'required',
    'user_profile_id' => 'required'
  ];
}