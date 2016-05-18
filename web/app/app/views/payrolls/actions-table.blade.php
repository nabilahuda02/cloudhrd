<?php $claim = GeneralClaim__Main::find($id);?>
<div class="btn-group btn-group-xs">
  @if($claim->canView())
    <a href="{{action('PayrollsController@show',array($claim->id))}}" class="btn btn-primary"><i class="fa fa-folder-open"></i></a>
  @endif
  @if($claim->canEdit())
    <a href="{{action('PayrollsController@edit',array($claim->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
  @endif
</div>
