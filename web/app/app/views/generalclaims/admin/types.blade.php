@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div id="generalclaim_type_rows">
            <div class="page-header">
                @include('generalclaims.menu')
                <h3>Manage General Claims Type</h3>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{ Former::text('name')
                    -> label('Name')
                    -> placeholder('Travel Expenses')
                    -> required() }}
                </div>
                <div class="col-md-2">
                    {{ Former::number('unit_price')
                    -> step(1 / pow(10, app()->user_locale->decimal_places))
                    -> label('Unit Price') }}
                </div>
                <div class="col-md-2">
                    {{ Former::text('unit')
                    -> label('Unit') }}
                </div>
                <div class="col-md-2">
                    <label for="has_gst">Has GST</label><br>
                    <input type="hidden" name="has_gst" value="0">
                    <input id="has_gst" type="checkbox" name="has_gst" value="1">
                </div>
                <div class="clearfix"></div>
                <br>
            </div>
            <hr>
            <br>
        </div>
    </div>
</div>

@stop
@section('script')
<script>
    $('#generalclaim_type_rows').duplicator({
        row: ".row",
        remotes: {
            post: '/generalclaimtype',
            put: '/generalclaimtype',
            delete: '/generalclaimtype',
            get: '/generalclaimtype'
        }
    });
</script>
@stop
