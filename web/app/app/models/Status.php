<?php

class Status extends Eloquent{
	protected $table = 'status';

  public static function selectOptions()
  {
    return ['' => 'Select One'] + (self::all()->lists('name', 'id'));
  }
}