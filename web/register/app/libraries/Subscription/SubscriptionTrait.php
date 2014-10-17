<?php

/**
 * Subscription
 *     - Check
 *     - Expiration
 *     - Add
 */

use Carbon\Carbon;

namespace Subscription;

trait SubscriptionTrait
{

    /**
     * Method to check user subscription properties
     * @return boolean indicates whether subscription is active or not
     */
    
    protected $subscription;

    public function getSubscriptionAttribute()
    {
        if(isset($this->subscription)) {
            return $this->subscription;
        }
        return $this->subscription = new SubscriptionHandler($this);
    }
    
    protected $trial;

    public function getTrialAttribute()
    {
        if(isset($this->trial)) {
            return $this->trial;
        }
        return $this->trial = new TrialHandler($this);
    }
    
    protected $package;

    public function getPackageAttribute()
    {
        // if(isset($this->package)) {
        //     return $this->package;
        // }
        // $trial = $this->getTrialAttribute();
        // if($trial->started() && !$trial->expired()) {
            
        // }
        // return $this->package = $this->getSubscriptionAttribute()->package;
    }

}