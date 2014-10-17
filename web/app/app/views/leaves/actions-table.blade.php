<?php $leave = Leave__Main::find($id); ?>
<div class="btn-group btn-group-xs ">
  @if($leave->canView())
    <a href="{{action('LeaveController@show',array($leave->id))}}" class="btn btn-primary"><i class="fa fa-folder-open"></i></a>
  @endif
  @if($leave->canEdit())
    <a href="{{action('LeaveController@edit',array($leave->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
  @endif
</div>