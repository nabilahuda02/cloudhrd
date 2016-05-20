@extends('layouts.module')
@section('content')
<div class="col-md-12">
  @include('html.notifications')
	<div class="col-md-12">
		<div class="page-header">
			@include('changerequests.menu')
			<h3>Change requests</h3>
		</div>

		<table data-path="change-requests" class="DT table table-striped table-bordered">
			<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">Created At</th>
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
			<h4>Other Change requests</h4>
			<div class="clearfix"></div>
		</div>
		<table data-path="other-change-requests" class="DT table table-striped table-bordered">
			<thead>
			<tr>
				<th class="text-center">Status</th>
				<th class="text-center">User</th>
				<th class="text-center">Created At</th>
				<th class="text-center">Reference No</th>
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
