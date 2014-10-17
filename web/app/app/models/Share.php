<?php

class Share extends \Eloquent {

	// Add your validation rules here
	public static $rules = [];

	// Don't forget to fill this array
	protected $fillable = [
    'content', 
    'user_id', 
    'root_path', 
    'file_name', 
    'extension',
    'type', 
    'event_date', 
    'title', 
    'pinned',
    'shareable_id',
    'shareable_type'
  ];

  public function shareable()
  {
    return $this->morphTo();
  }

  public function user()
  {
    return $this->belongsTo('user');
  }

  public function isMine()
  {
    return $this->user->id === Auth::user()->id;
  }

  public function comments()
  {
    return $this->hasMany('ShareComment', 'share_id', 'id')
      ->orderBy('share_comments.created_at', 'desc');
  }

  public static function upcommingEvents()
  {
    return self::where('type', 'event')
      -> where('event_date', '>=', date('Y-m-d'))
      -> orderBy('event_date', 'asc')
      -> take(5)
      -> get();
  }

}