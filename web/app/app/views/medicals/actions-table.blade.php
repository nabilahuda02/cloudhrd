<?php $medical = MedicalClaim__Main::find($id); ?>
<div class="btn-group btn-group-xs ">
  @if($medical->canView())
    <a href="{{action('MedicalController@show',array($medical->id))}}" class="btn btn-primary"><i class="fa fa-folder-open"></i></a>
  @endif
  @if($medical->canEdit())
    <a href="{{action('MedicalController@edit',array($medical->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
  @endif
</div>