<?php

class Module Extends Eloquent{
	protected $table = 'modules';


  public static $validation_rules = [
    'edit' => [
      'name' => 'required'
    ]
  ];

  protected $fillable = ['name', 'approver', 'verifier'];

	public function owners(){
		return $this->hasMany('UserModule', 'module_id', 'id');
	}

  public function users(){
    return $this->belongsToMany('User', 'user_modules', 'id', 'user_id', 'module_id');
  }

  public function administers($user_id)
  {
    /**
     * if user is_admin
     */
    $user = User::find($user_id);
    if($user->is_admin)
      return true;

    /**
     * if user is inside module owner array
     */
    if(in_array($user_id, UserModule::where('module_id', $this->id)->lists('user_id')))
      return true;

    return false;
  }

  public function approverName()
  {
    if($this->approver == -2) { 
      return 'None';
    } else if($this->approver == -1) {
      return 'Admin and Module Owners';
    }
    return 'Unit Head';
  }

  public function verifierName()
  {
    if($this->verifier == -2) { 
      return 'None';
    } else if($this->verifier == -1) {
      return 'Admin and Module Owners';
    }
    return 'Unit Head';
  }

  public function hasVerifier()
  {
    if($this->verifier > -2) { 
      return true;
    }
    return false;
  }

  public function hasApprovers()
  {
    if($this->approver > -2) { 
      return true;
    }
    return false;

  }

  public function getVerifiers($itemUserId)
  {
    if(!$this->hasVerifier())
      return [];

    if($this->verifier === -1) {
      return $this->users;
    }

    if($this->verifier === 0) {
      return [User::find($itemUserId)->unit->head];
    }

  }

  public function getApprovers($itemUserId)
  {

    if(!$this->hasApprovers())
      return [];

    if($this->approver === -1) {
      return $this->users;
    }

    if($this->approver === 0) {
      return [User::find($itemUserId)->unit->head];
    }

  }
}