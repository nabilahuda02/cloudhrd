<?php

class Task__TagUserPlacement extends \Eloquent 
{
    public $table = 'tag_user_placements';
    protected $fillable = ['name', 'user_id', 'tag_id'];

    public static $validation_rules = [
        'create' => [
            'name' => 'required',
            'tag_id' => 'required'
        ],
        'update' => [
            'name' => 'required',
            'tag_id' => 'required'
        ]
    ];
}