@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @if(Payroll__Main::canGenerate())
                @include('payrolls.menu')
            @endif
            <h3>Generated Payrolls</h3>
        </div>
        <table data-path="payrolls" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Period</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@stop
