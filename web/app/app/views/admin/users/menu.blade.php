<div class="pull-right">
    <div class="btn-group">
        <a href="<?php echo action('AdminUserController@index'); ?>" class="btn btn-default {{Helper::isRouteAction('AdminUserController@index')}}">View Users</a>
        <a href="<?php echo action('AdminUserController@create'); ?>" class="btn btn-default {{Helper::isRouteAction('AdminUserController@create')}}">Create User</a>
    </div>
</div>