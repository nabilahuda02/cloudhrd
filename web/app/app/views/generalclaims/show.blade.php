@extends('layouts.module')
@section('content')
<section id="generalclaim">
    <div class="container">
        <div class="row">
            <div class="col-xs-9">
                <h2>
                    @if($claim->is_paid)
                        <span class="pull-right label label-success">Paid</span>
                    @else
                        <span class="pull-right label label-warning">Unpaid</span>
                    @endif
                    General Claims Form: {{$claim->ref}}
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_GC.md', 'General Claims')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-3 section-drop-menu" >
                @include('generalclaims.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                {{ Former::horizontal_open(action('GeneralClaimsController@store'))
                    ->id('MyForm')
                    ->rules(['name' => 'required'])
                    ->method('POST') }}
                {{ Former::text('created_at')
                    ->label('Created At')
                    ->value(Helper::timestamp($claim->created_at))
                    ->readonly()
                    ->disabled() }}
                {{ Former::text('ref')
                    ->label('Reference')
                    ->value($claim->ref)
                    ->readonly()
                    ->disabled() }}
                {{ Former::text('user_id')
                    ->label('Employee')
                    ->value(User::fullName($claim->user_id))
                    ->readonly()
                    ->disabled() }}
                {{ Former::text('status_name')
                    ->label('Status')
                    ->value($claim->status->name)
                    ->readonly()
                    ->disabled() }}
                {{Former::populate($claim)}}
                {{ Former::text('title')
                    ->readonly()
                    ->disabled() }}
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
                    ->readonly()
                    ->disabled()
                    ->placeholder('0.00') }}
                @if($claim->uploads()->count() > 0)
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
                @endif
                {{ Former::textarea('remarks')
                    ->readonly()
                    ->disabled() }}
                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        @include('generalclaims.actions-buttons')
                    </div>
                </div>
                {{ Former::close() }}
            </div>
        </div>
    </div>
</section>
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
