<?php

class UserProfile extends Eloquent
{

    public static $validation_rules = [];

    protected $fillable = [
        'user_id',
        'ic_no',
        'staff_no',
        'first_name',
        'last_name',
        'date_join',
        'user_image',
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
        'kwsp_employer_contribution',
        'lhdn_account',
        'pcb_contribution',
        'socso_account',
        'socso_contribution',
        'socso_employer_contribution',
        'salary',
        'resigned_date',
        'employee_type',
        'position',
        'gender',
    ];

    public static $employeeTypeOptions = [
        '' => 'Select One',
        'Permanent' => 'Permanent',
        'Contract' => 'Contract',
        'Internship' => 'Internship',
        // ENUM('Permanent', 'Contract', 'Internship')
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function userName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getUserImageAttribute($value)
    {
        if (!$value) {
            return '/images/user.jpg';
        }
        return $value;
    }

}
