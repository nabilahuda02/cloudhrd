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
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            <a href="{{action('PayrollsController@index')}}" class="btn btn-info pull-right">Back</a>
            <h3>
                <div class="label label-info">{{$payrollUser->payroll->status->name}}</div>&nbsp;
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
</div>
@stop
