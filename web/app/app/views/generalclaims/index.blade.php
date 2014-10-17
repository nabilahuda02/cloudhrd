@extends('layouts.module')
@section('style')
<style>
	.approved{
		border-left:5px solid #8bbf61;
	}
	.rejected{
		border-left:5px solid #bd362f;
	}
	.pending{
		border-left:5px solid #ececec;
	}
	.canceled{
			border-left:5px solid #424242;
	}
</style>
@stop
@section('content')
<div class="col-md-10 col-sm-8">
	@include('html.notifications')
	<div class="col-md-12">
		<div class="page-header">
			@include('generalclaims.menu')
			<h3>General Claims</h3>
		</div>
		{{ Asset::push('js','app/tables.js')}}
		<table data-path="general-claims" class="DT table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">Request Date</th>
					<th class="text-center">Title</th>
					<th class="text-center">Amount</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	@if(count($downlines) > 0)
	<div class="clearfix"></div>
	<br>
	<div class="col-md-12">
		<div>
			<h4>Other General Claim History</h4>
			<div class="clearfix"></div>
		</div>
		<table data-path="other-general-claims" class="DT table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">User</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">Request Date</th>
					<th class="text-center">Title</th>
					<th class="text-center">Amount</th>
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
@section('script')
@stop