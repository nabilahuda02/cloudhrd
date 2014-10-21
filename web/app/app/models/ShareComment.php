<?php

class ShareComment extends \Eloquent {

  // Add your validation rules here
  public static $rules = [];
  protected $fillable = [
    'user_id', 
    'share_id', 
    'comment'
  ];

  public function user()
  {
    return $this->belongsTo('user', 'user_id', 'id');
  }

  public function isMine()
  {
    return $this->user_id === Auth::user()->id;
  }

}