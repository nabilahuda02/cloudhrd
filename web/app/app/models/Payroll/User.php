<?php

class Payroll__User extends Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'description' => 'required',
    ];

    // Don't forget to fill this array
    protected $fillable = [
        'user_id',
        'payroll_id',
        'total',
    ];

    protected $table = 'payroll_user';

    public function payroll()
    {
        return $this->belongsTo('Payroll__Main');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function items()
    {
        return $this->hasMany('Payroll__Item', 'payroll_user_id');
    }

    public function updateTotal()
    {
        $this->total = $this->items()->sum('amount');
        $this->save();
        return $this->total;
    }

}
