@if($user->administers(Payroll__Main::$moduleId))
<div class="btn-group pull-right">
    @if(Route::currentRouteAction() === 'PayrollsController@index')
        <a class="btn btn-primary" href="{{action('AdminPayrollController@getGenerated')}}">Generated</a>
    @elseif(Route::currentRouteAction() === 'AdminPayrollController@getDetails')
        <a href="<?php echo url('payroll/admin/generated'); ?>" class="btn btn-primary">Back</a>
    @elseif(Route::currentRouteAction() === 'AdminPayrollController@getUserDetails')
        <a href="<?php echo url('payroll/admin/details/' . $payrollUser->payroll_id); ?>" class="btn btn-primary">Back</a>
    @else
        <a href="<?php echo url('payrolls'); ?>" class="btn btn-primary">Back</a>
    @endif
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right text-left" role="menu">
        <li><a href="<?php echo url('payrolls/create'); ?>">Generate Payroll</a></li>
        <!-- <li><a disabled href="">Manage User Deductions</a></li>
        <li><a disabled href="">Manage User Additions</a></li>
        <li><a disabled href="">Manage Deductions</a></li>
        <li><a disabled href="">Manage Additions</a></li>
        <li><a disabled>Reports</a></li> -->
    </ul>
</div>
@endif