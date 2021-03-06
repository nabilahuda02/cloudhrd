@if($user->administers(MedicalClaim__Main::$moduleId))
<div class="btn-group pull-right">
    @if(Route::currentRouteAction() === 'MedicalController@index')
        <a href="<?php echo url('medical/create'); ?>" class="btn btn-primary">New Claim</a>
    @else
        <a href="<?php echo url('medical'); ?>" class="btn btn-primary">List Claims</a>
    @endif
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right text-left" role="menu">
        <li><a href="javascript:;"  onclick="HelpFile.show('ADMIN_MANAGE_MC.md', 'Medical Claims Administration Help')">Medical Claims Administration Help</a></li>
        @if(Route::currentRouteAction() !== 'MedicalController@index' && Route::currentRouteAction() !== 'MedicalController@create')
            <li><a href="<?php echo url('medical/create'); ?>">New Claim</a></li>
        @endif
        <li><a href="<?php echo url('medical/admin/types', $parameters = array(), $secure = null); ?>"> Manage Types</a></li>
        <li><a href="<?php echo url('medical/admin/entitlements', $parameters = array(), $secure = null); ?>"> Manage User Entitlement</a></li>
        <li><a href="<?php echo url('medical/admin/reporting', $parameters = array(), $secure = null); ?>"> Reporting</a></li>
    </ul>
</div>
@else
    <div class="pull-right">
        @if(Route::currentRouteAction() !== 'MedicalController@index')
            <a href="<?php echo url('medical'); ?>" class="btn btn-primary">List Claims</a>
        @endif
        <a href="<?php echo url('medical/create'); ?>" class="btn btn-primary">New Claim</a>
    </div>

@endif