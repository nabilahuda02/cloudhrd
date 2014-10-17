<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {
  use SoftDeletingTrait;
  protected $dates = ['deleted_at'];

	public static $validation_rules = [
    'registration' => [
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
      'confirm_password' => 'same:password',
      'first_name' => 'required'
    ],
		'edit' => [
			'email' => 'required|email',
			'first_name' => 'required'
		],
    'changepw' => [
      'password' => 'required|min:6',
      'confirm_password' => 'same:password',
    ],
	];

	protected $fillable = ['email', 'verified', 'password', 'unit_id', 'is_admin', 'email_password'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

  public function profile()
  {
    return $this->hasOne('UserProfile');
  }

  public function unit()
  {
    return $this->belongsTo('UserUnit', 'unit_id', 'id');
  }
  
  public static function byToken($token = null) 
  {
  	if(!$token) {
  		return null;
  	}
  	return self::where('verify_token', '=', $token)->first();
  }

  public function audits()
  {
    return $this->morphMany('Audit', 'auditable');
  }

  public function getDownline($moduleId, $withMe = false)
  {

    /**
     * If user is admin or user is module owner
     */
    $users = [];
    if(Module::find($moduleId)->administers($this->id)) {
      $users = static::all()->lists('id');
      if($withMe === false) {
        if(($key = array_search($this->id, $users)) !== false) {
          unset($users[$key]);
        }
      }
      return $users;
    }

    /**
     * If user is unit head and approver is set to unit head (modules.approver = 0)
     */
    $downlines = [];
    $user_unit = $this->unit;
    if($user_unit->user_id === $this->id && (Module::find($moduleId)->approver === 0 || Module::find($moduleId)->verifier === 0)) {
      $downlines = $user_unit->users->lists('id');
    }
    if($withMe === false) {
      if(($key = array_search($this->id, $downlines)) !== false) {
        unset($downlines[$key]);
      }
    }
    if(count($downlines) == 0 && $withMe) {
      return [$this->id];
    }
    return $downlines;
  }

  public static function selectOptions($moduleId)
  {
    return (UserProfile::whereIn('user_id', Auth::user()->getDownline($moduleId, true))->get()->lists('first_name', 'id'));
  }

  public function getUnread()
  {
    return 0;
  }

  public function loginAction()
  {
  	return 'WallController@getIndex';
  }

  // Checks whether a user is responsible to administer a module

  public function administers($moduleId)
  {
    if($this->is_admin || in_array($this->id, UserModule::where('module_id', $moduleId)->lists('user_id')))
      return true;
  }

  public function isApprover($moduleId, $itemUserId)
  {

    $module = Module::find($moduleId);
    if($module->approver === 0) {
      $itemUser = User::find($itemUserId);
      if($itemUser->unit->user_id === $this->id)
        return true;
    }
    return false;
  }

  public function isVerifier($moduleId, $itemUserId)
  {

    $module = Module::find($moduleId);
    if($module->verifier === 0) {
      $itemUser = User::find($itemUserId);
      if($itemUser->unit->user_id === $this->id)
        return true;
    }
    return false;
  }

  public function avatar()
  {
    return str_replace('orginal', 'avatar', $this->profile->user_image);
  }

  public static function fullName($uid) {
    $user = static::findOrFail($uid);
    return $user->profile->first_name . ' ' . $user->profile->last_name;
  }

  public function __construct(array $attributes = array())
  {
    if ( ! isset(static::$booted[get_class($this)]))
    {
      static::boot();
      static::$booted[get_class($this)] = true;
    }
    $this->fill($attributes);
    
    // self::deleting(function($user){
    //   Leave__Main::where('user_id', $user->id)->delete();
    //   GeneralClaim__Main::where('user_id', $user->id)->delete();
    //   MedicalClaim__Main::where('user_id', $user->id)->delete();
    //   RoomBooking__Main::where('user_id', $user->id)->delete();
    //   UserProfile::where('user_id', $user->id)->delete();
    //   Share::where('user_id', $user->id)->delete();
    //   ShareComment::where('user_id', $user->id)->delete();
    // });
  }

}
