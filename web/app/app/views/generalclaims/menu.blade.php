@if($user->administers(GeneralClaim__Main::$moduleId))
<div class="btn-group pull-right">
    @if(Route::currentRouteAction() === 'GeneralClaimsController@index')
        <a href="<?php echo url('claims/create'); ?>" class="btn btn-primary">Create Claim</a>
    @else
        <a href="<?php echo url('claims'); ?>" class="btn btn-primary">List Claims</a>
    @endif
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right text-left" role="menu">
        @if(Route::currentRouteAction() !== 'GeneralClaimsController@index' && Route::currentRouteAction() !== 'GeneralClaimsController@create')
            <li><a href="<?php echo url('claims/create'); ?>">Create Claim</a></li>
        @endif
        <li><a href="<?php echo url('claims/admin/types', $parameters = array(), $secure = null); ?>"> Manage Types</a></li>
        <li><a href="<?php echo url('claims/admin/reporting', $parameters = array(), $secure = null); ?>"> Reporting</a></li>
    </ul>
</div>
@else
    <div class="pull-right">
        @if(Route::currentRouteAction() !== 'GeneralClaimsController@index')
            <a href="<?php echo url('claims'); ?>" class="btn btn-primary">List Claims</a>
        @endif
        <a href="<?php echo url('claims/create'); ?>" class="btn btn-primary">Create Claim</a>
    </div>
@endif