@extends('layouts.default')
@section('content')
    <h2>Subscriptions</h2>
    <hr>
    <table data-path="/subscriptions" class="DT table table-striped table-hover table-bordered">
        <thead>
            <tr>
                
                <th>User</th>
                <th>Staff Count</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Unit Price</th>
                <th>Is Trial</th>

                <th width="200px">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <br>
    @include('subscriptions.actions-footer', ['is_list' => true])
@stop