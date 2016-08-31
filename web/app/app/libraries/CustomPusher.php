<?php

class CustomPusher extends Pusher
{
    public function fire($event, $data = [])
    {
        $this->trigger($this->channel, $event, $data);
    }

    public function __construct($key, $secret, $id, $options = [])
    {
        $this->channel = app('domain');
        parent::__construct($key, $secret, $id, $options);
    }
}
