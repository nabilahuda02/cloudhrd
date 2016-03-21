@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">

    @include('html.notifications')
    @include('generalclaims.header')
    <div class="col-md-12">
        <div >
            <h4>Claim Application Form
        </div>
        <div style="padding:15px;">
            {{ Former::horizontal_open(action('GeneralClaimsController@update', $claim->id))
            -> id('MyForm')
            -> rules(['name' => 'required'])
            -> method('POST') }}
            {{ Former::hidden('_method', 'PUT') }}
            {{ Former::text('ref')
            -> label('Reference')
            -> value($claim->ref)
            -> readonly()
            -> disabled() }}
            {{ Former::text('user_id')
            -> label('Employee')
            -> value(User::fullName($claim->user_id))
            -> readonly() }}
            {{ Former::text('status_name')
            -> label('Status')
            -> value($claim->status->name)
            -> readonly()
            -> disabled() }}
            {{ Former::populate($claim) }}
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
                            @foreach($claim->entries as $entry)
                            <tr><td><input type="hidden" name="entries[]" value='{{json_encode($entry)}}' /> {{$entry->receipt_date}}</td><td>{{ $entry->receipt_number }}</td><td>{{$entry->type->name}}</td><td>{{$entry->description}}</td><td class="amount_col">{{$entry->amount}}</td><td><button type="button" class="removerow"><span class="glyphicon glyphicon-trash"></span></button></td></tr>
                            @endforeach
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

            {{ Asset::push('js','app/upload')}}
            <div class="form-group">
                <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
                <div class="col-lg-10 col-sm-8">
                    <ul class="list-inline uploaded">
                        @foreach ($claim->uploads as $file)
                        <li class="view_uploaded" data-url="{{$file->file_url}}">
                            <button type="button" class="btn btn-danger remove_uploaded" data-id="{{$file->id}}">&times;</button>
                            <img src="{{ $file->thumb_url }}" alt="" class="thumbnail">
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="dates" class="control-label col-lg-2 col-sm-4">Upload<br/>(If Any)</label>
                <div class="col-lg-10 col-sm-8 dropzone" id="upload" data-path="generalclaim/{{$claim->upload_hash}}/{{$claim->id}}">
                </div>
            </div>
            {{ Former::textarea('remarks') }}
            <div class="form-group">
                <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                    @include('generalclaims.actions-buttons')

                    <input class="btn-large btn-primary btn pull-right click-once" type="submit" value="Submit">
                </div>
            </div>
            {{ Former::close() }}
        </div>
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
{{Asset::push('js', 'app/general_claimd')}}
@stop
@section('script')
    @include('generalclaims.actions-scripts')
@stop