@if($user->administers(Leave__Main::$moduleId))
<div class="btn-group pull-right">
    @if(Route::currentRouteAction() === 'LeaveController@index')
        <a href="<?php echo url('leave/create'); ?>" class="btn btn-primary">Create Leave</a>
    @else
        <a href="<?php echo url('leave'); ?>" class="btn btn-primary">List Leaves</a>
    @endif
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right text-left" role="menu">
        <li><a href="javascript:;"  onclick="HelpFile.show('ADMIN_LEAVE.md', 'Leave Administration Help')">Leave Administration Help</a></li>
        @if(Route::currentRouteAction() !== 'LeaveController@index' && Route::currentRouteAction() !== 'LeaveController@create')
            <li><a href="<?php echo url('leave/create'); ?>">Create Leave</a></li>
        @endif
        <li><a href="<?php echo url('leave/admin/types');?>"> Manage Types</a></li>
        <li><a href="<?php echo url('leave/admin/entitlements');?>"> Manage User Entitlement</a></li>
        <li><a href="<?php echo url('leave/admin/blocked-dates');?>">Manage Blocked Dates</a></li>
        <li><a href="<?php echo url('leave/admin/reporting');?>"> Reporting</a></li>
    </ul>
</div>
@else
    <div class="pull-right">
        @if(Route::currentRouteAction() !== 'LeaveController@index')
            <a href="<?php echo url('leave/index'); ?>" class="btn btn-primary">List Leaves</a>
        @endif
        <a href="<?php echo url('leave/create'); ?>" class="btn btn-primary">Create Leave</a>
    </div>
    
@endif