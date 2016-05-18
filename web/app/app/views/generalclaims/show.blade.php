@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    @include('generalclaims.header')
    <div class="col-md-12">
        <h3>{{ $claim->is_paid ? 'Paid' : 'Unpaid' }}</h3>
    </div>
    <div class="col-md-12">
        <div >
            <h4>Claim Application Form</h4>
            <div class="clearfix"></div>
        </div>
        <div style="padding:15px;">
            {{ Former::horizontal_open(action('GeneralClaimsController@store'))
                -> id('MyForm')
                -> rules(['name' => 'required'])
                -> method('POST') }}
            {{ Former::text('created_at')
                -> label('Created At')
                -> value(Helper::timestamp($claim->created_at))
                -> readonly()
                -> disabled() }}
            {{ Former::text('ref')
                -> label('Reference')
                -> value($claim->ref)
                -> readonly()
                -> disabled() }}
            {{ Former::text('user_id')
                -> label('Employee')
                -> value(User::fullName($claim->user_id))
                -> readonly()
                -> disabled() }}
            {{ Former::text('status_name')
                -> label('Status')
                -> value($claim->status->name)
                -> readonly()
                -> disabled() }}
            {{Former::populate($claim)}}
            {{ Former::text('title')
                -> readonly()
                -> disabled() }}
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
                            </tr>
                        </thead>
                        <tbody id="targettbody">
                            @foreach ($claim->entries as $entry)
                            <tr>
                                <td>{{ $entry->receipt_date }}</td>
                                <td>{{ $entry->receipt_number }}</td>
                                <td>{{ $entry->type->name }}</td>
                                <td>{{ $entry->description }}</td>
                                <td class="amount_col">{{ $entry->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ Former::number('value')
                -> readonly()
                -> disabled()
                -> placeholder('0.00') }}
            {{ Asset::push('js','upload')}}
            <div class="form-group">
                <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
                <div class="col-lg-10 col-sm-8">
                    <ul class="list-inline uploaded">
                        @foreach ($claim->uploads as $file)
                        <li class="view_uploaded" data-url="{{$file->file_url}}">
                            <a href="{{$file->file_url}}" target="_blank">{{$file->file_name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{ Former::textarea('remarks')
                -> readonly()
                -> disabled() }}
            <div class="form-group">
                <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                    @include('generalclaims.actions-buttons')
                </div>
            </div>
            {{ Former::close() }}
        </div>
    </div>
</div>
@stop
@section('script')
@include('generalclaims.actions-scripts')
<script>
    var targettbody = $('#targettbody');
    var calculateTotal = function() {
        var total = 0;
        $('.amount_col').each(function(){
            total += Math.round(parseFloat($(this).text()) * 100) / 100;
        });
        $('#value').val(total.toFixed(2));
    }
    $(document).on('click', '.removerow', function(){
        var target = $(this);
        bootbox.confirm('Are your sure you want to remove this row?', function(val){
            if(val) {
                target.parents('tr').remove();
                calculateTotal();
            }
        })
    });
</script>
@stop
