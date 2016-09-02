@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div id="generalclaim_tags_rows">
            <div class="page-header">
                @include('generalclaims.menu')
                <h3>Manage General Claims Tags</h3>
            </div>
            <div class="row">
                <div class="col-md-9">
                    {{ Former::text('name')
                    -> label('Name')
                    -> placeholder('Project Alpha')
                    -> required() }}
                </div>
                <div class="col-md-1">
                    <label for="enabled">Enabled</label><br>
                    <input type="hidden" name="enabled" value="0">
                    <input id="enabled" type="checkbox" name="enabled" value="1">
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
    $('#generalclaim_tags_rows').duplicator({
        row: ".row",
        remotes: {
            post: '/generalclaimtags',
            put: '/generalclaimtags',
            delete: '/generalclaimtags',
            get: '/generalclaimtags'
        }
    });
</script>
@stop
