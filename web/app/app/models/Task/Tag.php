<?php

class Task__Tag extends \Eloquent 
{

    public $table = 'tags';
    // public $order = 0;
    // public $placement = 'left';
    protected $fillable = ['name', 'tag_category_id', 'label'];

    public static $validation_rules = [
        'create' => [
            'name' => 'required',
            'tag_category_id' => 'required',
            'label' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'tag_category_id' => 'required',
            'label' => 'required',
        ]
    ];

    public function category()
    {
        return $this->belongsTo('Task__Category', 'tag_category_id');
    }

    public function placements() {
        return $this->hasMany('Task__TagUserPlacement', 'tag_id');
    }

    public function orders() {
        return $this->hasMany('Task__TagUserOrder', 'tag_id');
    }
}