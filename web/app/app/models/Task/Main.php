<?php

class Task__Main extends \Eloquent {

	protected $fillable = ['upload_hash', 'medical_claim_type_id', 'user_id', 'status_id', 'treatment_date', 'total', 'remarks'];
	public $table = 'tasks';
	public static $moduleId = 4;

	// Add your validation rules here
	public static $rules = [
		// 'total' => 'Required|Numeric|min:0',
		// 'medical_claim_type_id' => 'Required',
		// 'treatment_date' => 'Required'
	];

    public function uploads()
    {
        return $this->morphMany('Upload', 'imageable');
    }

    public function audits()
    {
        return $this->morphMany('Audit', 'auditable');
    }

    public function owner()
    {
        return $this->hasOne('User', 'id', 'user_id');
    }

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
    return false;
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

}