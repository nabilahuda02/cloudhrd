@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @if(Payroll__Main::canGenerate())
                @include('payrolls.menu')
            @endif
            <h3>My Payrolls</h3>
        </div>
        <table data-path="payrolls" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Status</th>
                    <th class="text-center">Period</th>
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
            <h4>Other Payroll History</h4>
            <div class="clearfix"></div>
        </div>
        <table data-path="other-payrolls" class="DT table table-bordered table-striped">
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
