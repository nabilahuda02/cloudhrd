<?php

class Task__TagUserOrder extends \Eloquent 
{
    public $table = 'tag_user_orders';
    protected $fillable = ['order', 'user_id', 'tag_id'];

    public static $validation_rules = [
        'create' => [
            'order' => 'required',
            'tag_id' => 'required'
        ],
        'update' => [
            'order' => 'required',
            'tag_id' => 'required'
        ]
    ];
}