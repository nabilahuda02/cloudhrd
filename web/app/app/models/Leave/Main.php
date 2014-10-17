<?php

class Leave__Main extends \Eloquent {

  protected $table = 'leaves';
  public static $moduleId = 1;

  // Add your validation rules here
  public static $rules = [
    'user_id' => 'required',
    'leave_type_id' => 'required',
    'dates' => 'required',
    'total' => 'required'
  ];

  // Add your validation rules here
  public static $rulesApprove = [
    'remark' => 'required'
  ];

  // Add your validation rules here
  public static $rulesPackage = [
    'id' => 'required',
    'name' => 'required'
  ];

  // Don't forget to fill this array
  protected $fillable = ['user_id','leave_type_id','remarks','total','status_id','upload_hash'];

  public function dates()
  {
    return $this->hasMany('Leave__Date', 'leave_id', 'id');
  }

  public function uploads()
  {
    return $this->morphMany('Upload', 'imageable');
  }

  public function shares()
  {
    return $this->morphMany('Share', 'shareable');
  }

  public function audits()
  {
    return $this->morphMany('Audit', 'auditable');
  }

  public static function underMe()
  {
    $downline = Auth::user()->getDownline(static::$moduleId, true);
    return self::whereIn('user_id', $downline)
      ->orderBy('id', 'desc');
  }

  public function claim_type()
  {
    return $this->belongsTo('Leave__Type', 'leave_type_id', 'id');
  }

  public function type()
  {
    return $this->hasOne('Leave__Type', 'id', 'leave_type_id');
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
        $this->shares()->delete();
      } else if($status_id == 5) {
        $this->shares()->delete();
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

    $data = DB::table('leaves')
      ->select('status_id', DB::raw('count(*) as num'))
      ->whereIn('user_id', $users)
      ->groupBy('status_id')
      ->get();

    return $data;
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
    return false;
  }

  public function user()
  {
    return $this->hasOne('User', 'id', 'user_id');
  }

  /**
   * Emails 
   */
  
  public function emailCreated()
  {
    $item = $this;
    $type = 'leave';
    $module = Module::find(self::$moduleId);
    $recepients = $module->getVerifiers($item->user_id);

    foreach ($recepients as $recepient) {

      $def_params = [
        'recepient' => $recepient->id,
        'id' => $this->id,
        'current_status' => $this->status_id,
        'next_status' => 1,
        'type' => 'leave',
      ];

      $verify = $def_params;
      $verify['next_status'] = 2;
      $verify = Helper::encrypt($verify);
      
      $reject = $def_params;
      $reject['next_status'] = 4;
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
        $message->to($recepient->email, Helper::userName($recepient->id))->subject('Leave Awaiting Verification: ' . $item->ref);
      });
    }
  }
  
  public function emailVerified()
  {
    $item = $this;
    $type = 'leave';
    $module = Module::find(self::$moduleId);
    $recepients = $module->getApprovers($item->user_id);

    foreach ($recepients as $recepient) {

      $def_params = [
        'recepient' => $recepient->id,
        'id' => $this->id,
        'current_status' => $this->status_id,
        'next_status' => 1,
        'type' => 'leave',
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
        $message->to($recepient->email, Helper::userName($recepient->id))->subject('Leave Awaiting Approval: ' . $item->ref);
      });
    }
  }

  public function emailApproved()
  {
    $item = $this;
    $type = 'leave';

    Mail::send('emails.applications.approved', compact('item', 'type'), function($message) use ($item)
    {
      $message->to($item->user->email, Helper::userName($item->user_id))->subject('Leave Approved: ' . $item->ref);
    });
  }

  public function emailRejected()
  {
    $item = $this;
    $type = 'leave';

    Mail::send('emails.applications.rejected', compact('item', 'type'), function($message) use ($item)
    {
      $message->to($item->user->email, Helper::userName($item->user_id))->subject('Leave Rejected: ' . $item->ref);
    });
  }

}