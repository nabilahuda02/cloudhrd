<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Task__Category extends \Eloquent 
{
    use SoftDeletingTrait;

    public $table = 'tag_categories';
    protected $fillable = ['name'];
    public static $validation_rules = [
        'create' => [
            'name' => 'required'
        ],
        'update' => [
            'name' => 'required'
        ]
    ];

    public function tags()
    {
        return $this->hasMany('Task__Tag', 'tag_category_id');
    }
}