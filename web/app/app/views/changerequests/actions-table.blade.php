<?php $changeRequest = ChangeRequest__Main::find($id);?>
<div class="btn-group btn-group-xs ">
  @if($changeRequest->canView())
    <a href="{{action('ChangeRequestsController@show',array($changeRequest->id))}}" class="btn btn-primary"><i class="fa fa-folder-open"></i></a>
  @endif
  @if($changeRequest->canEdit())
    <a href="{{action('ChangeRequestsController@edit',array($changeRequest->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
  @endif
</div>
