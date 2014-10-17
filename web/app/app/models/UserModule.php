<?php

class UserModule Extends Eloquent{
	protected $table = 'user_modules';
  protected $fillable = ['module_id', 'user_id'];
}