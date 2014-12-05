<div class="pull-right">
    <div class="btn-group">
        <a href="{{action('AdminUserController@index')}}" class="btn btn-default {{Helper::isRouteAction('AdminUserController@index')}}">View Users</a>
        <a href="{{action('AdminUserController@create')}}" class="btn btn-default {{Helper::isRouteAction('AdminUserController@create')}}">Create User</a>
        <a href="{{action('AdminUserController@getManageTemplate')}}" class="btn btn-default {{Helper::isRouteAction('AdminUserController@getManageTemplate')}}">Manage User Template</a>
        <a href="{{action('AdminUserController@getImportUsers')}}" class="btn btn-default {{Helper::isRouteAction('AdminUserController@getImportUsers')}}">Import Users</a>
    </div>
</div>