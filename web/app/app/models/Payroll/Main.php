<?php

class Payroll__Main extends Eloquent
{

    // Add your validation rules here
    public static $rules = [
        'name' => 'required',
    ];

    public static $moduleId = 5;

    // Don't forget to fill this array
    protected $fillable = ['name', 'total', 'status_id'];

    protected $table = 'payrolls';

    public function status()
    {
        return $this->hasOne('Status', 'id', 'status_id');
    }

    public function payrollUsers()
    {
        return $this->hasMany('Payroll__User', 'payroll_id');
    }

    public function setStatus($status_id)
    {
        $this->status_id = $status_id;
        switch ($status_id) {
            case 7:
                // do something on published
                break;

            default:
                # code...
                break;
        }
        $this->save();
    }

    /* ACL */

    public static function canGenerate()
    {

        /**
         * If user is admin or module owner
         */

        if (Auth::user()->administers(static::$moduleId)) {
            return true;
        }

        /**
         * Else return false
         */

        return false;
    }

    public function canView()
    {
        /**
         * If user is submitter
         */

        if (Auth::user()->id === $this->user_id) {
            return true;
        }

        /**
         * If user is admin or module owner
         */

        if (Auth::user()->administers(static::$moduleId)) {
            return true;
        }

        /**
         * If module verifier
         */

        if (Auth::user()->isVerifier(static::$moduleId, $this->user_id)) {
            return true;
        }

        /**
         * If module custodian is unit head and user belongs to the same unit (custodian)
         */

        if (Auth::user()->isApprover(static::$moduleId, $this->user_id)) {
            return true;
        }

        /**
         * Else return false
         */

        return false;
    }

    public function canEdit()
    {

        /**
         * If user is admin or module owner
         */

        if (Auth::user()->administers(static::$moduleId)) {
            return true;
        }

        /**
         * If module custodian is unit head and user belongs to the same unit (custodian)
         */

        if (Auth::user()->isApprover(static::$moduleId, $this->user_id)) {
            return true;
        }

        /**
         * Else return false
         */

        return false;
    }

    public function canCancel()
    {
        return false;
    }

    public function canDelete()
    {
        return $this->status_id === 1 || $this->status_id === 5;
    }

    public function updateTotal()
    {
        $this->total = $this->payrollUsers()->sum('total');
        $this->save();
        return $this->total;
    }
}
