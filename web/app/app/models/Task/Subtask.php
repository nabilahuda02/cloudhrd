<?php

class Task__Subtask extends \Eloquent 
{

    public $table = 'todo_subtasks';

    public static $validation_rules = [
        'create' => [
            'name' => 'required',
            'todo_id' => 'required',
        ]
    ];

}