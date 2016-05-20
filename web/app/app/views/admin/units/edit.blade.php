@extends('layouts.module')
@section('content')
<div class="col-md-12">
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.units.menu')
            <h3>Edit Unit</h3>
        </div>
        {{ Former::horizontal_open(action('AdminUnitController@update', $unit->id))
        -> method('POST') }}
        {{ Former::hidden('_method', 'PUT') }}
        {{ Former::populate($unit) }}
        @include('admin.units.form')
        <div class="form-group">
            <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                <button type="button" id="delete_unit" class="btn-large btn-secondary btn pull-right">Delete</button>
                <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
            </div>
        </div>
        {{Former::close()}}
        {{ Former::open(action('AdminUnitController@destroy', $unit->id))->id('delete_form') }}
        {{ Former::hidden('_method', 'DELETE') }}
        {{ Former::close() }}
    </div>
</div>
@stop
@section('script')
<script>
document.getElementById('delete_unit').onclick = function() {
if(confirm('Are you sure you want to remove this user?'))
document.getElementById('delete_form').submit();
}
</script>
@stop
