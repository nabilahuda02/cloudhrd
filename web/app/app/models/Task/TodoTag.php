<?php

class Task__TodoTag extends \Eloquent 
{

    public $table = 'todo_tags';
    protected $fillable = ['tag_id', 'todo_id', 'order'];

    public function tag()
    {
        return $this->belongsTo('Task__Tag', 'tag_id');
    }
}