@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    <div class="col-md-12">
        <div class="page-header">
            @include('changerequests.menu')
            <h3>Change Request: {{ $changerequest->ref }}</h3>
        </div>
        <h4>Changes</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Column</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($changerequest->items as $entry)
                <tr>
                    <td>{{$entry->field_name}}</td>
                    <td>{{$entry->old_value}}</td>
                    <td>{{$entry->new_value}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="form-group">
            <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                @include('changerequests.actions-buttons')
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
@include('changerequests.actions-scripts')
@stop
