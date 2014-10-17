<?php

class Leave__UserEntitlement extends Eloquent{
	protected $table = 'leave_user_entitlements';

	public function leaveType(){
		return $this->belongsTo('Leave__Type','leave_type_id','id');
	}
}