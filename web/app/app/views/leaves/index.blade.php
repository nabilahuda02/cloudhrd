@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
  
  @include('html.notifications')
	<div class="col-md-12">
		<div class="page-header">
			@include('leaves.menu')
			<h3>Leaves</h3>
		</div>
    	@include('leaves.entitlementtable')
		{{ Asset::push('js','app/tables.js')}}
		<table data-path="leaves" class="DT table table-striped table-bordered">
			<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">Type</th>
					<th class="text-center">Duration</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<hr>
	</div>

	@if(count($downlines) > 0)

	<div class="clearfix"></div>
	<br>
	<div class="col-md-12">
		<div>
			<h4>Other Leaves</h4>
			<div class="clearfix"></div>
		</div>
		<table data-path="other-leaves" class="DT table table-striped table-bordered">
			<thead>
			<tr>
				<th class="text-center">Status</th>
				<th class="text-center">User</th>
				<th class="text-center">Reference No</th>
				<th class="text-center">Type</th>
				<th class="text-center">Duration</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<hr>
	</div>
	@endif
</div>
@stop