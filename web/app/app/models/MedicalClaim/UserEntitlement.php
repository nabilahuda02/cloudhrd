<?php

class MedicalClaim__UserEntitlement extends \Eloquent 
{
    public $table = 'medical_claim_user_entitlements';

    public static $rules = [
        // 'title' => 'required'
    ];

    protected $fillable = [];

    public function getEntitlementAttribute($value)
    {
    return Helper::currency_format($value, false);
    }

}