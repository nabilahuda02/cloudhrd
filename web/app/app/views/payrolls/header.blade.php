<div class="col-md-12 text-center">
  <br>
  <a href="<?php echo url('claims', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-list-alt"></span> View Claims </a>
  <a href="<?php echo url('claims/create', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-file"></span> Create Claim </a>
  @if(Auth::user()->administers(GeneralClaim__Main::$moduleId))
    <a href="<?php echo url('claims/admin/types', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-cog"></span> Manage Types</a>
    <a href="<?php echo url('claims/admin/reporting', $parameters = array(), $secure = null); ?>" class="btn btn-default"><span class="glyphicon glyphicon-list-alt"></span> Reporting</a>
  @endif
  <div class="clearfix"></div>
  <br>
</div>

<div class="clearfix"></div> <br>