@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
	@include('html.notifications')
	<div class="col-md-12">
		<div class="page-header">
			@include('generalclaims.menu')
			<h3>Create New Claims</h3>
		</div>
		{{ Former::horizontal_open(action('GeneralClaimsController@store'))
			-> id('MyForm')
			-> rules(['name' => 'required'])
			-> method('POST') }}
		{{Former::hidden('noonce', Helper::noonce())}}
		@if(count(Auth::user()->getDownline(GeneralClaim__Main::$moduleId)) > 0)
		{{ Former::select('user_id')
			-> label('For User')
			-> options(Helper::userArray(Auth::user()->getDownline(GeneralClaim__Main::$moduleId, true)), null)
			-> class('form-control col-md-4')
			->required() }}
		@endif
		{{ Former::text('title')
		-> required() }}
		<div class="form-group">
			<div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td>Date</td>
							<td>Receipt Number</td>
							<td>Type</td>
							<td>Description</td>
							<td width="150px">Subtotal</td>
							<td width="90px">-</td>
						</tr>
					</thead>
					<tbody id="targettbody">
					</tbody>
				</table>
				<button type="button" id="newrow" class="btn btn-secondary">
				<span class="glyphicon glyphicon-plus"></span>
				</button>
			</div>
		</div>
		{{ Former::number('value')
			-> readonly()
			-> required() }}
		<div class="form-group">
			<label for="dates" class="control-label col-lg-2 col-sm-4">Upload<br/>(If Any)</label>
			<div class="col-lg-10 col-sm-8">
				<div class="dropzone" id="upload" data-path="generalclaim/temp/{{Helper::noonce()}}"></div>
			</div>
			{{Asset::push('js', 'app/upload')}}
		</div>
		{{ Former::textarea('remarks') }}
		<div class="form-group">
			<div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
				<input class="btn-large btn-primary btn pull-right click-once" type="submit" value="Submit">
			</div>
		</div>
		{{ Former::close() }}
	</div>
</div>
<template id="claimform">
<br>
<br>
{{ Former::horizontal_open()
	-> id('entryform') }}
{{ Former::select('claim_type_id')
	-> id('form_type')
	-> label('Type')
	-> required()
	-> options(GeneralClaim__Type::all()->lists('name', 'id'))
	-> placeholder('Choose a claim type') }}
{{ Former::input('receipt_date')
	-> data_type('date')
	-> value(Helper::today_short_date())
	-> label('Date')
	-> required() }}
{{ Former::text('receipt_number')
	-> label('Receipt Number')
	-> required() }}
{{ Former::text('description')
	-> id('form_title')
	-> required() }}
@foreach(GeneralClaim__Type::where('unit', '!=', '')->get() as $type)
{{ Former::number('quantity[]')
	-> step(1 / pow(10, app()->user_locale->decimal_places))
	-> class('form-control quantities')
	-> id('form_quantity_' . $type->id)
	-> label('Quantity (' . $type->unit . ')')
	-> data_unitprice($type->unit_price)
	-> help($user_locale->currency_symbol . ' ' . $type->unit_price . ' / ' . $type->unit) }}
@endforeach
{{ Former::number('amount')
	-> step(1 / pow(10, app()->user_locale->decimal_places))
	-> id('form_amount')
	-> required()
	-> readonly() }}
{{ Former::close() }}
</template>
<script>
	var createNewRow = function(data) {
	    if(data) {
	        data.quantity = data.quantity.filter(function(val){
	            return val;
	        }).pop() || 0;
	        data.amount = (Math.round(parseFloat(data.amount) * divisor) / divisor).toFixed(decimal_places);
	        data.quantity = (Math.round(parseFloat(data.quantity || 0) * divisor) / divisor).toFixed(decimal_places);
	        var types = {
	            @foreach(GeneralClaim__Type::all()->lists('name', 'id') as $id => $type)
	'{{$id}}': '{{$type}}',
	            @endforeach
	        }
	        targettbody.append('<tr><td><input type="hidden" name="entries[]" value=\'' + JSON.stringify(data) + '\' />' + data.receipt_date + '</td><td>' + data.receipt_number + '</td><td>' + types[data.claim_type_id] + '</td><td>' + data.description + '</td><td class="amount_col">' + data.amount + '</td><td><button type="button" class="removerow"><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
	        calculateTotal();
	    }
	};
</script>
{{Asset::push('js', 'app/general_claims.js')}}
@stop
