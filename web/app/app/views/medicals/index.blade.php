@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
	@include('html.notifications')
	<div class="col-md-12">
		<div class="page-header">
			@include('medicals.menu')
			<h3>Medical Claims</h3>
		</div>
		@include('medicals.entitlementtable')
		<table data-path="medical-claims" class="DT table table-striped table-bordered">
			<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">Claim Type</th>
					<th class="text-center">Amount</th>
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
		<div class="page-header">
			<h3>Other Medical Claims</h3>
		</div>
		<table data-path="other-medical-claims" class="DT table table-striped table-bordered">
			<thead>
				<tr>
					<th class="text-center">Status</th>
					<th class="text-center">User</th>
					<th class="text-center">Reference No</th>
					<th class="text-center">Claim Type</th>
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