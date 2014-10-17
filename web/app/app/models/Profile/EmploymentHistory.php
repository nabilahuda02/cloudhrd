<?php

class Profile__EmploymentHistory extends \Eloquent {
  protected $table = 'user_profile_employment_histories';
	protected $fillable = ['company_name', 'start_date', 'end_date', 'user_profile_id'];
  public static $rules = [
    'company_name' => 'required',
    'start_date' => 'required',
    'end_date' => 'required',
    'user_profile_id' => 'required'
  ];
}