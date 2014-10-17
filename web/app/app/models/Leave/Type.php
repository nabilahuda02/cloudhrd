<?php

class Leave__Type extends Eloquent {

	protected $table = 'leave_types';

  public static $rules = ['name' => 'required', 'default_entitlement' => 'required|numeric|min:0'];

  protected $fillable = ['name', 'default_entitlement', 'past', 'future', 'display_calendar', 'display_wall', 'colors'];

  public static function nameArray()
  {
    return self::all()->lists('name', 'id');
  }

  public function user_entitlement($uid = null)
  {
    if(!$uid)
      $uid = Auth::User()->id;

    $effective = $this->effective_user_entitlement_override($uid);
    if($effective)
      return $effective;
    return $this->default_entitlement;
  }

  public function utilized_user_entitlement($uid = null, $year = null)
  {
    if(!$uid)
      $uid = Auth::User()->id;

    if(!$year)
      $year = date('Y');

    $leaves = DB::table('leaves')
      -> join('leave_dates', 'leave_dates.leave_id', '=', 'leaves.id')
      -> where('leaves.leave_type_id', $this->id)
      -> where('leaves.status_id', '<=', 3)
      -> where('leaves.user_id', $uid)
      -> whereRaw('year(leave_dates.date) = "' . $year . '"')
      -> count();

    return $leaves;
  }

  public function user_entitlement_balance($uid = null)
  {
    if(!$uid)
      $uid = Auth::User()->id;

    return $this->user_entitlement($uid) - $this->utilized_user_entitlement($uid);
  }

  public function effective_user_entitlement_override($uid)
  {
    if(!$uid)
      $uid = Auth::User()->id;

    $effective = Leave__UserEntitlement::where('leave_type_id', $this->id)
      -> where('user_id', $uid)
      -> where('start_date', '<=', date('Y-m-d'))
      -> orderBy('start_date', 'desc')
      -> first();

    if($effective)
      return $effective->entitlement;

  }

  public static function selectOptions()
  {
    return ['' => 'Select One'] + (static::all()->lists('name', 'id'));
  }

  public function getColors()
  {
    return explode(',', $this->colors);
  }

}