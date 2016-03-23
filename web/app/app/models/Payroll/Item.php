<?php

class Payroll__Item extends Eloquent
{
    protected $table = 'payroll_items';

    protected $fillable = [
        'name',
        'payroll_user_id',
        'payrollable_type',
        'payrollable_id',
        'amount',
        'description',
    ];

    public static $rules = [
        'name' => 'required',
    ];

    public function getUnitPriceAttribute($value)
    {
        return Helper::currency_format($value, false);
    }
}
