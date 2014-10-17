@extends('layouts.default')
@section('content')
    <h2>New User Group</h2>
    <hr>
    {{ Former::open(action('organizationunits.store')) }}
        @include('organizationunits.form')
        @include('organizationunits.actions-footer', ['has_submit' => true])
@stop
