<?php

class Leave__BlockedDate extends Eloquent {

	protected $table = 'leave_blocked_dates';

  public static $rules = ['name' => 'required'];

  protected $fillable = ['name', 'date'];

}