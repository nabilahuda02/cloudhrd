@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.units.menu')
            <h3>Create New Unit</h3>
        </div>
        {{ Former::horizontal_open(action('AdminUnitController@store'))
        -> method('POST') }}
        @include('admin.units.form')
        <div class="form-group">
            <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
            </div>
        </div>
        {{ Former::close() }}
    </div>
</div>
@stop
