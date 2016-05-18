@extends('layouts.module')
@section('style')
    <style>
        .payroll-table-inner {
            width: 100%;
        }
        .payroll-table-inner td {
            padding: 10px 0px;
        }
        td.payroll-table-title {
            text-align: left;
        }
        td.payroll-table-amount {
            text-align: right;
        }
    </style>
@stop
@section('content')
<?php $earnings = 0;?>
<?php $deductions = 0;?>
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @if(Payroll__Main::canGenerate())
                @include('payrolls.menu')
            @endif
            <h3>
                <div class="label label-info">{{$payrollUser->payroll->status->name}}</div>
                Generated Payroll: {{$payrollUser->payroll->name}} for {{$payrollUser->user->getFullName()}}
            </h3>
            <br>
            <br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Earnings</th>
                        <th>Deductions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table class="payroll-table-inner">
                                @foreach($payrollUser->items as $item)
                                    @if($item->amount >= 0)
                                            <tr>
                                                <td class="payroll-table-title">
                                                    @if($item->payrollable_type == 'GeneralClaim__Main')
                                                        <a href="{{action('AdminPayrollController@getRemoveGeneralClaim', [$item->id])}}" class="btn btn-danger btn-xs btn-confirm">
                                                            <i class="fa fa-minus"></i>
                                                        </a>&nbsp;
                                                    @elseif($item->payrollable_type == 'MedicalClaim__Main')
                                                        <a href="{{action('AdminPayrollController@getRemoveMedicalClaim', [$item->id])}}" class="btn btn-danger btn-xs btn-confirm">
                                                            <i class="fa fa-minus"></i>
                                                        </a>&nbsp;
                                                    @endif
                                                    {{$item->name}}
                                                </td>
                                                <td class="payroll-table-amount">{{Helper::currency_format($item->amount)}}</td>
                                            </tr>
                                        <?php $earnings += $item->amount;?>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                        <td>
                            <table class="payroll-table-inner">
                                @foreach($payrollUser->items as $item)
                                    @if($item->amount < 0)
                                            <tr>
                                                <td class="payroll-table-title">{{$item->name}}</td>
                                                <td class="payroll-table-amount">{{Helper::currency_format($item->amount)}}</td>
                                            </tr>
                                        <?php $deductions += $item->amount;?>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="payroll-table-amount">Total Earnings: {{Helper::currency_format($earnings)}}</td>
                        <td class="payroll-table-amount">Total Deductions: {{Helper::currency_format($deductions)}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="payroll-table-amount" colspan="2">Net Pay: {{Helper::currency_format($payrollUser->total)}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <hr>

    <div class="col-md-12">
        <div class="page-header">
            <h3>
                Unpaid General Claims
            </h3>
        </div>
        <table data-path="unpaid-general-claims/{{$payrollUser->id}}/{{$payrollUser->user_id}}" class="DT table table-bordered table-striped">
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

    <div class="col-md-12">
        <div class="page-header">
            <h3>
                Unpaid Medical Claims
            </h3>
        </div>
        <table data-path="unpaid-medical-claims/{{$payrollUser->id}}/{{$payrollUser->user_id}}" class="DT table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Status</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center">Reference No</th>
                    <th class="text-center">Claim Type</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <hr>
    </div>


</div>
@stop
