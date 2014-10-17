@extends('layouts.default')
@section('content')
    <h2>Edit User Group</h2>
    <hr>
    {{ Former::open(action('organizationunits.update', $organizationunit->id)) }}
        {{Former::populate($organizationunit)}}
        {{Former::hidden('_method', 'PUT')}}
        @include('organizationunits.form')
        @include('organizationunits.actions-footer', ['has_submit' => true])
@stop
