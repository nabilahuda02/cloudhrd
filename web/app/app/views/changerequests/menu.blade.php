<div class="pull-right">
    @if(Route::currentRouteAction() !== 'ChangeRequestsController@index')
        <a href="<?php echo url('change-request'); ?>" class="btn btn-primary">List Change Requests</a>
    @endif
</div>
