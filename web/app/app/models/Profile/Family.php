<?php

class Profile__Family extends \Eloquent {
  protected $table = 'user_profile_family_members';
	protected $fillable = ['name', 'dob', 'lookup_family_relationship_id', 'user_profile_id'];
  public static $rules = [
    'name' => 'required',
    'dob' => 'required',
    'lookup_family_relationship_id' => 'required',
    'user_profile_id' => 'required'
  ];
}