<?php

class Payroll__SOCSOContribution extends Eloquent
{

    protected $table = 'socso_contributions';

    // Add your validation rules here
    public static $rules = [
        // 'description' => 'required',
    ];

    // Don't forget to fill this array
    protected $fillable = [
        'payroll_user_id',
        'name',
        'employee_contribution',
        'employer_contribution',
    ];

}
