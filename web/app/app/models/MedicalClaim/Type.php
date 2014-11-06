<?php

class MedicalClaim__Type extends \Eloquent 
{

  public $table = 'medical_claim_types';

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['name', 'default_entitlement', 'display_wall', 'colors'];

  public function user_entitlement($uid = null)
  {
    if(!$uid && Auth::user())
      $uid = Auth::user()->id;

    if(!$uid)
      return 0;

    $userOverride = $this->user_entitlement_override($uid)->first();
    if($userOverride === null) {
      return $this->default_entitlement;
    }
    return $userOverride->entitlement;
  }

  public function utilized_user_entitlement($uid = null)
  {
    if(!$uid && Auth::user())
      $uid = Auth::user()->id;

    if(!$uid)
      return 0;

    $leaves = $this->claims_by_type($this->id, $uid, date('Y'), true);

    if(!$leaves)
      return 0;
    return $leaves->sum('total');
  }


  public function claims_by_type($type_id, $uid, $year = null, $only_deduct_entitlement = false)
  {
    $medicalClaims = MedicalClaim__Main::where('medical_claim_type_id', $type_id)
      ->where('user_id', $uid)
      ->whereRaw('year(`treatment_date`) = ?', [$year]);
    if($only_deduct_entitlement)
      $medicalClaims->where('status_id', '<=', 3);

    return $medicalClaims->get();
  }

  public function user_entitlement_balance($uid = null)
  {
    return $this->user_entitlement($uid) - $this->utilized_user_entitlement($uid);
  }

  public function user_entitlement_override($uid = null)
  {
    $user = Auth::user();
    if($uid)
      $user = User::find($uid);

    return $this->belongsTo('MedicalClaim__UserEntitlement', 'id', 'medical_claim_type_id')
      ->where('user_id', $user->id)
      ->where('start_date', '<=', date('Y-m-d'))
      ->orderBy('start_date','desc');
  }

  public function effective_user_entitlement_override($uid = null)
  {
    $user = Auth::user();
    if($uid)
      $user = User::find($uid);

    $entitlement = MedicalClaim__UserEntitlement::where('user_id', $user->id)
      ->where('medical_claim_type_id', $this->id)
      ->where('start_date', '<=', date('Y-m-d'))
      ->orderBy('start_date','desc')
      ->first();
    if($entitlement)
      return $entitlement->entitlement;
    else
      return null;
  }

  public static function selectOptions()
  {
    return ['' => 'Select One'] + (static::all()->lists('name', 'id'));
  }

  public function getColors()
  {
    if(stristr($this->colors, ','))
      return explode(',', $this->colors);
    $base = new Color($this->colors);
    $offset = $base->lighten(20);
    return [$this->colors, '#' . $offset];
  }

  public function getDefaultEntitlementAttribute($value)
  {
    return Helper::currency_format($value, false);
  }

}