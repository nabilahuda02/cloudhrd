@extends('layouts.default')
@section('content')
    <h2>Subscribers</h2>
    <hr>
    <table data-query='{"query":{"type" : "subscribers"}}' data-path="{{route('users.index')}}" class="DT table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Organization Unit</th>
                <th>Roles</th>
                <th>Confirmed</th>
                <th style="max-width:200px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <br>
    <h2>Resellers</h2>
    <hr>
    <table data-query='{"query":{"type" : "resellers"}}' data-path="{{route('users.index')}}" class="DT table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Organization Unit</th>
                <th>Roles</th>
                <th>Confirmed</th>
                <th style="max-width:200px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <br>
    <h2>Admins</h2>
    <hr>
    <table data-query='{"query":{"type" : "backend-users"}}' data-path="{{route('users.index')}}" class="DT table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Organization Unit</th>
                <th>Roles</th>
                <th>Confirmed</th>
                <th style="max-width:200px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <br>
    @include('users.actions-footer', ['is_list' => true])
@stop
