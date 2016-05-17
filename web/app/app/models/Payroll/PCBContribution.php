<?php

class Payroll__PCBContribution extends Eloquent
{

    protected $table = 'pcb_contributions';

    // Add your validation rules here
    public static $rules = [
        // 'description' => 'required',
    ];

    // Don't forget to fill this array
    protected $fillable = [
        'payroll_user_id',
        'name',
        'employee_contribution',
    ];

}
