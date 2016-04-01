<?php

class UserProfile extends Eloquent
{

    public static $validation_rules = [];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address',
        'user_field_00',
        'user_field_01',
        'user_field_02',
        'user_field_03',
        'user_field_04',
        'user_field_05',
        'user_field_06',
        'user_field_07',
        'user_field_08',
        'user_field_09',
        'bank_name',
        'bank_account',
        'kwsp_account',
        'kwsp_contribution',
        'lhdn_account',
        'pcb_contribution',
        'socso_account',
        'socso_contribution',
        'salary',
        'position',
        'gender',
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function userName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

}
