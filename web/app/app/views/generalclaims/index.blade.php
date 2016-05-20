@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('generalclaims.menu')
            <h3>
                General Claims
                <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_GC.md', 'General Claims')">
                    <i class="fa fa-question-circle"></i>
                </button>
            </h3>
        </div>
        <table data-path="general-claims" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Status</th>
                    <th class="text-center">Reference No</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @if(count($downlines) > 0)
    <div class="clearfix"></div>
    <br>
    <div class="col-md-12">
        <div>
            <h4>Other General Claim History</h4>
            <div class="clearfix"></div>
        </div>
        <table data-path="other-general-claims" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Status</th>
                    <th class="text-center">User</th>
                    <th class="text-center">Reference No</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <hr>
    </div>
    @endif
</div>
@stop
