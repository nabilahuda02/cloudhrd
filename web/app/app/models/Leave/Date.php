<?php

class Leave__Date extends Eloquent{
	protected $table = 'leave_dates';

  public function leave()
  {
    return $this->hasOne('Leave__Main', 'id', 'leave_id');
  }
}