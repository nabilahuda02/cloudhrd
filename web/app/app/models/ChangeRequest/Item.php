<?php

class ChangeRequest__Item extends Eloquent
{

    // Add your validation rules here
    public static $rules = [];

    // Don't forget to fill this array
    protected $fillable = ['change_request_id', 'field_name', 'key', 'old_value', 'new_value'];

    protected $table = 'change_request_items';

    public function changeRequest()
    {
        return $this->belongsTo('ChangeRequest__Main', 'change_request_id');
    }

}
