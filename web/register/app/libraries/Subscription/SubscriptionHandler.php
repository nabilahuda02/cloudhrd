<?php

namespace Subscription;

use \Carbon\Carbon;
use \Subscription;
use \SubscriptionPackage;

class SubscriptionHandler
{

    public function active()
    {
        return false;
    }

    public function current()
    {
        return null;
    }

    public function check()
    {
        return true;
    }

    public function expires()
    {
        if($this->current()) {
            
        }
    }

    protected $user;
    public $subscriptions;
    public $subscription;
    public $package;

    public function __construct($user)
    {
        $this->user = $user;
        $this->subscriptions = Subscription::where('user_id', $user->id)->get();
        foreach ($subscriptions as $subscription) {
            # code...
        }
        $this->package = null;
    }
}