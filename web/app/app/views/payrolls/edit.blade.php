@extends('layouts.module')
@section('content')
<div class="col-md-12">

    @include('html.notifications')
    @include('payrolls.header')
    <div class="col-md-12">
        <div >
            <h4>Manage Generated Payroll: {{$payroll->name}}</h4>
        </div>
        <div style="padding:15px;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @foreach($payroll->payrollUsers as $payrollUser)
                    <tr>
                        <td>{{$payrollUser->user->getFullName()}}</td>
                        <td>{{$payrollUser->total}}</td>
                        <td>
                            <a href="#" class="btn btn-xs btn-primary">
                                <span class="fa fa-pencil"></span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            <div class="form-group">
                <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                    @include('payrolls.actions-buttons')
                    <input class="btn-large btn-primary btn pull-right click-once" type="submit" value="Submit">
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
@stop
