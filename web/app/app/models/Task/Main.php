<?php

class Task__Main extends \Eloquent {

    protected $fillable = ['description', 'owner_id', 'archived'];
    public $table = 'todos';
    public static $moduleId = 4;

    // Add your validation rules here
    public static $rules = [
        // 'total' => 'Required|Numeric|min:0',
        // 'medical_claim_type_id' => 'Required',
        // 'treatment_date' => 'Required'
    ];

    public function owner()
    {
        return $this->hasOne('User', 'id', 'owner_id');
    }

    public function followers()
    {
        return $this->belongsToMany('User', 'todo_followers', 'todo_id', 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany('Task__Tag', 'todo_tags', 'todo_id', 'tag_id');
    }

    public function orders()
    {
        return $this->hasMany('Task__Order', 'todo_id');
    }

    public function notes()
    {
        return $this->hasMany('Task__Note', 'todo_id');
    }

    public function uploads()
    {
        return $this->morphMany('Upload', 'imageable');
    }

    public function subtasks()
    {
        return $this->hasMany('Task__Subtask', 'todo_id');
    }

    public function sendAssignedEmail() {
        $task = $this;
        $user = $this->owner;
        Mail::send('emails.tasks.assigned', compact('user', 'task'), function($message) use ($user, $task)
        {
          $message->to($user->email, $user->getFullName())->subject('Task Assigned: ' . substr($task->description, 0, 20));
        });
    }

}