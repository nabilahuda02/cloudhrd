@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @if(Payroll__Main::canGenerate())
                @include('payrolls.menu')
            @endif
            <h3>
                <div class="label label-info">{{$payroll->status->name}}</div>
                Generated Payroll: {{$payroll->name}}
            </h3>
        </div>
        <h4>
            Payrolls
        </h4>
        <table data-path="payroll/{{$payroll->id}}" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Unit</th>
                    <th class="text-center">User</th>
                    <th class="text-center">Bank</th>
                    <th class="text-center">Account</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <h4>
            Payroll Total: {{$payroll->total}}
        </h4>
        <hr>
        <h4>
            EPF
        </h4>
        <table data-path="payroll-epf/{{$payroll->id}}" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Unit</th>
                    <th class="text-center">User</th>
                    <th class="text-center">EPF Account</th>
                    <th class="text-center">Employee Contribution</th>
                    <th class="text-center">Employer Contribution</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <h4>
            EPF Total: {{$epfTotal}}
        </h4>
        <hr>
        <h4>
            SOCSO
        </h4>
        <table data-path="payroll-socso/{{$payroll->id}}" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Unit</th>
                    <th class="text-center">User</th>
                    <th class="text-center">SOCSO Account</th>
                    <th class="text-center">Employee Contribution</th>
                    <th class="text-center">Employer Contribution</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <h4>
            SOCSO Total: {{$socsoTotal}}
        </h4>
        <hr>
        <h4>
            PCB
        </h4>
        <table data-path="payroll-pcb/{{$payroll->id}}" class="DT table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Unit</th>
                    <th class="text-center">User</th>
                    <th class="text-center">PCB Account</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <h4>
            PCB Total: {{$pcbTotal}}
        </h4>
    </div>
    <div class="col-md-12">
        <hr>
        <div class="well">
            @if($payroll->status_id == 6)
                <a href="{{action('AdminPayrollController@getPublish', $payroll->id)}}" class="btn btn-primary">Publish</a>
            @else
                <a href="{{action('AdminPayrollController@getUnpublish', $payroll->id)}}" class="btn btn-danger">Unpublish</a>
            @endif
            {{-- <a href="{{action('AdminPayrollController@getDownload', $payroll->id)}}" class="btn btn-default">Download</a> --}}
        </div>
    </div>
</div>
@stop
