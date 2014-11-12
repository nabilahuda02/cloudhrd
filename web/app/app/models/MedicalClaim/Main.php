<?php

class MedicalClaim__Main extends \Eloquent {

	protected $fillable = ['upload_hash', 'medical_claim_type_id', 'user_id', 'status_id', 'treatment_date', 'total', 'remarks'];

	public $table = 'medical_claims';
	public static $moduleId = 2;

	// Add your validation rules here
	public static $rules = [
		'total' => 'Required|Numeric|min:0',
		'medical_claim_type_id' => 'Required',
		'treatment_date' => 'Required'
	];

  public static function underMe()
  {
    $downline = Auth::user()->getDownline(static::$moduleId, true);
    return self::whereIn('user_id', $downline)
      ->orderBy('id', 'desc');
  }

  public function uploads()
  {
    return $this->morphMany('Upload', 'imageable');
  }

  public function audits()
  {
    return $this->morphMany('Audit', 'auditable');
  }
  
  public function claim_type()
  {
    return $this->hasOne('MedicalClaim__Type', 'id', 'medical_claim_type_id');
  }

	public function type()
	{
		return $this->hasOne('MedicalClaim__Type', 'id', 'medical_claim_type_id');
	}

	public function status()
	{
		return $this->hasOne('Status', 'id', 'status_id');
	}

  public function setStatus($status_id)
  {
    $this->status_id = $status_id;
    if($status_id == 1) {
      if(Module::find(self::$moduleId)->hasVerifier()) {
        $this->emailCreated();
      } else {
        $this->emailVerified();
      }
      $this->audits()->create([
        'ref' => $this->ref,
        'type' => 1,
        'data' => $this->toArray()
      ]);
    } else {
      if($status_id == 2) {
        $this->emailVerified();
      } else if($status_id == 3) {
        $this->emailApproved();
      } else if($status_id == 4) {
        $this->emailRejected();
      }
      $this->audits()->create([
        'ref' => $this->ref,
        'type' => 3,
        'data' => $this->status_id
      ]);
    }
    $this->save();
  }

  public static function sparkData()
  {
    $users = Auth::user()
      -> getDownline(static::$moduleId);
    if(!$users)
      $users = [Auth::user()->id];

    $data = DB::table('medical_claims')
      ->select('status_id', DB::raw('count(*) as num'))
      ->whereIn('user_id', $users)
      ->groupBy('status_id')
      ->get();

    return $data;
  }

  public function user()
  {
    return $this->hasOne('User', 'id', 'user_id');
  }

  /* ACL */

  public function canView()
  {
    /**
     * If user is submitter
     */

    if(Auth::user()->id === $this->user_id)
      return true;
      
    /**
     * If user is admin or module owner
     */

    if(Auth::user()->administers(static::$moduleId))
      return true;
      
    /**
     * If module verifier
     */

    if(Auth::user()->isVerifier(static::$moduleId, $this->user_id))
      return true;
      
    /**
     * If module custodian is unit head and user belongs to the same unit (custodian)
     */

    if(Auth::user()->isApprover(static::$moduleId, $this->user_id))
      return true;

    /**
     * Else return false
     */

    return false;
  }

  public function canEdit()
  {
    /**
     * If user is submitter and status is new
     */
    
    if(Auth::user()->id === $this->user_id && $this->status_id === 1)
      return true;
      
    /**
     * If user is admin or module owner
     */

    if(Auth::user()->administers(static::$moduleId))
      return true;
      
    /**
     * If module custodian is unit head and user belongs to the same unit (custodian)
     */

    if(Auth::user()->isApprover(static::$moduleId, $this->user_id))
      return true;

    /**
     * Else return false
     */

    return false;
  }

  public function canVerify()
  {
    /**
     * If status is not verified
     */
    
    if($this->status_id !== 2 && Module::find(self::$moduleId)->hasVerifier()) {
      /**
       * If module custodian is unit head and user belongs to the same unit (custodian)
       */
      if(Auth::user()->isVerifier(static::$moduleId, $this->user_id))
        return true;
    }
    /**
     * Else return false
     */
    return false;
  }

  public function canApprove()
  {
    /**
     * If status is not approved
     */
    
    if($this->status_id !== 3) {
      /**
       * If user is admin or module owner
       */
      if(Auth::user()->administers(static::$moduleId))
        return true;
      /**
       * If module custodian is unit head and user belongs to the same unit (custodian)
       */
      if(Auth::user()->isApprover(static::$moduleId, $this->user_id))
        return true;
    }
    /**
     * Else return false
     */
    return false;
  }

  public function canReject()
  {
    /**
     * If status is not cancelled
     */
    
    if($this->status_id !== 4) {
      /**
       * If user is admin or module owner
       */
      if(Auth::user()->administers(static::$moduleId))
        return true;
      
      /**
       * If module verifier
       */

      if(Auth::user()->isVerifier(static::$moduleId, $this->user_id))
        return true;
      
      /**
       * If module custodian is unit head and user belongs to the same unit (custodian)
       */
      if(Auth::user()->isApprover(static::$moduleId, $this->user_id))
        return true;
    }
    /**
     * Else return false
     */
    return false;
  }

  public function canCancel()
  {

    /**
     * If user is the owner and status is pending
     */
    if($this->status_id === 1) {
      if(Auth::user()->id === $this->user_id) {
        return true;
      }
    }

    /**
     * If status is not cancelled
     */
    if($this->status_id !== 5) {
      /**
       * If user is admin or module owner
       */
      if(Auth::user()->administers(static::$moduleId))
        return true;
      /**
       * If module custodian is unit head and user belongs to the same unit (custodian)
       */
      if(Auth::user()->isApprover(static::$moduleId, $this->user_id))
        return true;
    }
    /**
     * Else return false
     */
    return false;
  }
  
  public function canDelete()
  {
    return $this->status_id === 1 || $this->status_id === 5;
  }

  /**
   * Emails 
   */
  
  public function emailCreated()
  {
    $item = $this;
    $type = 'medical';
    $module = Module::find(self::$moduleId);
    $recepients = $module->getVerifiers($item->user_id);

    foreach ($recepients as $recepient) {
      $def_params = [
        'recepient' => $recepient->id,
        'id' => $this->id,
        'current_status' => $this->status_id,
        'next_status' => 1,
        'type' => 'medical',
      ];

      $verify = $def_params;
      $reject = $def_params;

      $verify['next_status'] = 2;
      $reject['next_status'] = 4;

      $verify = Helper::encrypt($verify);
      $reject =  Helper::encrypt($reject);

      $actions = [
        [
          'label'  => 'Verify',
          'action' => URL::to('email_action', $verify),
          'class'  => 'primary'
        ],
        [
          'label'  => 'Reject',
          'action' => URL::to('email_action', $reject)
        ],
      ];
      Mail::send('emails.applications.created', compact('item', 'type', 'recepient', 'actions'), function($message) use ($item, $recepient)
      {
          $message->to($recepient->email, User::fullName($recepient->id))->subject('Medical Claim Awaiting Verification: ' . $item->ref);
      });
    }
  }
  
  public function emailVerified()
  {
    $item = $this;
    $type = 'medical';
    $module = Module::find(self::$moduleId);
    $recepients = $module->getApprovers($item->user_id);

    foreach ($recepients as $recepient) {
      $def_params = [
        'recepient' => $recepient->id,
        'id' => $this->id,
        'current_status' => $this->status_id,
        'next_status' => 1,
        'type' => 'medical',
      ];

      $approve = $def_params;
      $reject = $def_params;

      $approve['next_status'] = 3;
      $reject['next_status'] = 4;

      $approve = Helper::encrypt($approve);
      $reject =  Helper::encrypt($reject);

      $actions = [
        [
          'label'  => 'Approve',
          'action' => URL::to('email_action', $approve),
          'class'  => 'primary'
        ],
        [
          'label'  => 'Reject',
          'action' => URL::to('email_action', $reject)
        ],
      ];
      Mail::send('emails.applications.verified', compact('item', 'type', 'recepient', 'actions'), function($message) use ($item, $recepient)
      {
          $message->to($recepient->email, User::fullName($recepient->id))->subject('Medical Claim Awaiting Approval: ' . $item->ref);
      });
    }
  }

  public function emailApproved()
  {
    $item = $this;
    $type = 'medical';

    Mail::send('emails.applications.approved', compact('item', 'type'), function($message) use ($item)
    {
      $message->to($item->user->email, User::fullName($item->user_id))->subject('Medical Claim Approved: ' . $item->ref);
    });
  }

  public function emailRejected()
  {
    $item = $this;
    $type = 'medical';

    Mail::send('emails.applications.rejected', compact('item', 'type'), function($message) use ($item)
    {
      $message->to($item->user->email, User::fullName($item->user_id))->subject('Medical Claim Rejected: ' . $item->ref);
    });
  }

  public function getTotalAttribute($value)
  {
    return Helper::currency_format($value, false);
  }

  public function getTreatmentDateAttribute($date)
  {
    return Helper::mysql_to_short_date($date);
  }

}