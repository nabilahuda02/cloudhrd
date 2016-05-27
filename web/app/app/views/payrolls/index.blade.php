@extends('layouts.module')
@section('content')
<section id="payroll">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h2>
                    General Claims
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_GC.md', 'General Claims')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-6 section-drop-menu" >
                @include('payrolls.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                <div class="table-responsive">
                    <table data-path="my-payrolls" class="DT table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Status</th>
                                <th class="text-center">Period</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
