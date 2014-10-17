<?php

class Master__User extends Eloquent
{

    protected $table = 'users';
    protected $connection = 'master';

    public function cards()
    {
        return $this->hasMany('Master__Card', 'user_id', 'id');
    }

}