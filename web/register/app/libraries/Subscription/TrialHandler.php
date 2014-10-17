<?php

namespace Subscription;

use \Subscription;
use \SubscriptionPackage;

class TrialHandler
{

    public function activated()
    {
        
    }

    public function expired()
    {
        return true;
    }

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}