@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('payrolls.menu')
            <h3>Generate Payroll</h3>
        </div>
        {{ Former::horizontal_open(action('PayrollsController@store'))
            -> id('MyForm')
            -> rules(['name' => 'required'])
            -> method('POST') }}
        {{Former::hidden('noonce', Helper::noonce())}}
        {{Former::text('name')->label('Period')->value(date('Y-m'))}}
        <div class="form-group">
            <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                <input class="btn-large btn-primary btn pull-right click-once" type="submit" value="Submit">
            </div>
        </div>
        {{ Former::close() }}
    </div>
</div>
@stop
@section('script')
    <script type="text/javascript">
        $('#name').datetimepicker({
            format: 'YYYY/MM'
        });
    </script>
@stop
