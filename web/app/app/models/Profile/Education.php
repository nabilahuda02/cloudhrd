<?php

class Profile__Education extends \Eloquent {
  protected $table = 'user_profile_education_histories';
	protected $fillable = ['institution', 'course', 'start_date', 'end_date', 'user_profile_id'];
  public static $rules = [
    'institution' => 'required',
    'course' => 'required',
    'start_date' => 'required',
    'end_date' => 'required',
    'user_profile_id' => 'required'
  ];
}