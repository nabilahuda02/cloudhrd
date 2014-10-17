@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
	@include('html.notifications')
	<div class="col-md-12">
		<div class="page-header">
			@include('generalclaims.menu')
			<h3>Create New Claims</h3>
		</div>
		<div style="padding:15px;">
			{{ Former::horizontal_open(action('GeneralClaimsController@store'))
				-> id('MyForm')
				-> rules(['name' => 'required'])
				-> method('POST') }}
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
								<td>Receipt Date</td>
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
				-> placeholder('0.00')
				-> required() }}
			{{ Former::textarea('remarks') }}
			{{ Former::actions()
				-> medium_primary_submit() }}
			{{ Former::close() }}
		</div>
	</div>
</div>
<template id="claimform">
<br>
<br>
{{ Former::horizontal_open()
	-> id('entryform') }}
{{ Former::date('receipt_date')
	-> label('Receipt Date')
	-> value(date('Y-m-d'))
	-> required() }}
{{ Former::text('receipt_number')
	-> label('Receipt Number')
	-> required() }}
{{ Former::text('description')
	-> id('form_title')
	-> required() }}
{{ Former::select('claim_type_id')
	-> id('form_type')
	-> label('Type')
	-> required()
	-> options(GeneralClaim__Type::all()->lists('name', 'id'))
	-> placeholder('Choose a claim type') }}
@foreach(GeneralClaim__Type::where('unit', '!=', '')->get() as $type)
{{ Former::number('quantity[]')
	-> step('0.01')
	-> class('form-control quantities')
	-> id('form_quantity_' . $type->id)
	-> label('Quantity (' . $type->unit . ')')
	-> data_unitprice($type->unit_price)
	-> placeholder('0.00')
	-> help('RM ' . $type->unit_price . ' / ' . $type->unit) }}
@endforeach
{{ Former::number('amount')
	-> step('0.01')
	-> id('form_amount')
	-> required()
	-> readonly()
	-> placeholder('0.00') }}
<div class="form-group">
	<label for="dates" class="control-label col-lg-2 col-sm-4">Upload<br/>(If Any)</label>
	<div class="col-lg-10 col-sm-8">
		<div class="dropzone" id="upload" data-path="generalclaim/temp/{{csrf_token()}}"></div>
	</div>
	{{Asset::push('js', 'app/upload')}}
</div>
{{ Former::close() }}
</template>
@stop
@section('script')
<script>
	var targettbody = $('#targettbody');
	var calculateTotal = function() {
		var total = 0;
		$('.amount_col').each(function(){
			total += Math.round(parseFloat($(this).text()) * 100) / 100;
		});
		$('#value').val(total.toFixed(2));
	};
	$(document).on('click', '.removerow', function(){
			var target = $(this);
		bootbox.confirm('Are your sure you want to remove this row?', function(val){
			if(val) {
				target.parents('tr').remove();
				calculateTotal();
			}
		})
	});
	var createNewRow = function(data) {
		if(data) {
			data.quantity = data.quantity.filter(function(val){
				return val;
			}).pop() || 0;
			data.amount = (Math.round(parseFloat(data.amount) * 100) / 100).toFixed(2);
			data.quantity = (Math.round(parseFloat(data.quantity || 0) * 100) / 100).toFixed(2);
			var types = {
				@foreach(GeneralClaim__Type::all()->lists('name', 'id') as $id => $type)
'{{$id}}': '{{$type}}',
				@endforeach
			}
			targettbody.append('<tr><td><input type="hidden" name="entries[]" value=\'' + JSON.stringify(data) + '\' />' + data.receipt_date + '</td><td>' + data.receipt_number + '</td><td>' + types[data.claim_type_id] + '</td><td>' + data.description + '</td><td class="amount_col">' + data.amount + '</td><td><button type="button" class="removerow"><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
			calculateTotal();
		}
	};
	bootbox.form = function(template, callback) {
		var modal = bootbox.confirm(template, function(res){
			if(!res) {
				callback(null);
			}
		});
		var form = $('form', modal).on('submit', function(e){
			e.preventDefault();
			return false;
		});
		$('[data-bb-handler="confirm"]').on('click', function(e){
			var isValid = true;
			$('input,textarea,select', form).each(function(index, field){
				if(!field.checkValidity()) {
					$(field).trigger('invalid');
					isValid = false;
				}
			});
			if(isValid) {
				callback(form.serializeJSON());
				return;
			}
			e.preventDefault();
			return false;
		});
	}
	$(document).on('click', '#newrow', function() {
		bootbox.form($('#claimform').html(), createNewRow);
		attachUploader();
		var amountRow = $('#form_amount').parents('.form-group');
		var amountInput = $('input', amountRow);
		var resetForm = function() {
			$('.quantities').each(function(idx, quantity){
				$(quantity).parents('.form-group').hide();
			});
			amountRow.hide();
			amountInput.attr({
				readonly: true
			}).val('');
		}
		$('#form_type').change(function(){
			var sel = $(this);
			var type = sel.val();
			resetForm();
			if(type) {
				amountRow.show();
				if($('#form_quantity_' + type).length) {
					var quantityRow = $('#form_quantity_' + type)
						.parents('.form-group')
						.show();
					var quantityInput = $('input', quantityRow)
						.attr('required', true)
						.keyup(function(e){
							var target = $(this);
							var val = parseFloat(target.val());
							if(!val || isNaN(val)) {
								amountInput.val(0);
								this.checkValidity();
								e.preventDefault();
								return false;
							}
							amountInput.val((Math.round(val * parseFloat(quantityInput.data('unitprice')) * 100) / 100).toFixed(2))
						});
				} else {
					$('.quantities').removeAttr('required');
					amountInput.removeAttr('readonly');
				}
			} else {
				amountRow.hide();
			}
		}).trigger('change');
	});
</script>
@stop