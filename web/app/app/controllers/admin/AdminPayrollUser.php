<?php

class AdminPayrollUser extends \BaseController
{
    public function __construct()
    {
        View::share('controller', 'Unit Admin');
        View::share('types', UserUnit::all());
    }

}
