<?php

class ShareComment extends \Eloquent {

  // Add your validation rules here
  public static $rules = [];

  public function user()
  {
    return $this->belongsTo('user', 'user_id', 'id');
  }

  public function isMine()
  {
    return $this->user_id === Auth::user()->id;
  }

}