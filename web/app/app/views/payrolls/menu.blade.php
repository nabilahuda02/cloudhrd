<div class="btn-group pull-right">
    @if(Route::currentRouteAction() === 'PayrollsController@index')
        <a href="<?php echo url('payrolls/create'); ?>" class="btn btn-primary">Generate Payroll</a>
    @else
        <a href="<?php echo url('payrolls'); ?>" class="btn btn-primary">List</a>
    @endif
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right text-left" role="menu">
        <li><a href="">Manage User Deductions</a></li>
        <li><a href="">Manage User Additions</a></li>
        <li><a href="">Manage Deductions</a></li>
        <li><a href="">Manage Additions</a></li>
        <li><a>Reports</a></li>
    </ul>
</div>
