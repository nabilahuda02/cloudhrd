<?php

class UserSharePin Extends Eloquent{
    protected $table = 'user_share_pins';
    protected $fillable = ['share_id', 'user_id'];
}