<?php

class UserProfile extends Eloquent
{

  public static $validation_rules = [];

  protected $fillable = ['user_id', 'first_name', 'last_name'];


  public function user()
  {
    return $this->belongsTo('User');
  }

  public function userName()
  {
    return $this->first_name . ' ' . $this->last_name; 
  }

}