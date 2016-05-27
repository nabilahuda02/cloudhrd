<div class="pull-right">
    <div class="btn-group">
        <a href="<?php echo action('AdminUnitController@index'); ?>" class="btn btn-default {{Helper::isRouteAction('AdminUnitController@index')}}">View Units</a>
        <a href="<?php echo action('AdminUnitController@create'); ?>" class="btn btn-default {{Helper::isRouteAction('AdminUnitController@create')}}">Create New Unit</a>
        <!-- <a href="<?php echo action('AdminUnitController@getChart'); ?>" class="btn btn-default {{Helper::isRouteAction('AdminUnitController@getChart')}}">View Org Chart</a> -->
    </div>
</div>