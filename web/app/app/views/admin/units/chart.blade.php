@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.units.menu')
            <h3>Your Organization Chart</h3>
        </div>
        <div id="orgchart"></div>
    </div>
</div>
@stop
@section('script')
{{Asset::push('js', 'primitives/primitives')}}
{{Asset::push('css', 'primitives/primitives.latest.css')}}
<script>
var options = new primitives.orgdiagram.Config();
var items = [
@foreach ($orgs as $org)
new primitives.orgdiagram.ItemConfig({
id: {{$org->id}},
@if($org->parent_id)
parent: {{$org->parent_id}},
@else
parent: null,
@endif
title: "{{$org->name}}",
description: "{{$org->head->profile->first_name}}",
image: "{{$org->head->profile->user_image}}"
}),
@endforeach
];
options.items = items;
options.cursorItem = 0;
options.hasSelectorCheckbox = false;
$("#orgchart").orgDiagram(options);
</script>
@stop