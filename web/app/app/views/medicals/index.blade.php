@extends('layouts.module')
@section('content')
<section id="medical">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h2>
                    Medical
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_LEAVE.md', 'Medical')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-6 section-drop-menu" >
                @include('medicals.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                <div class="table-responsive">
                    <table data-path="medical-claims" class="DT table table-striped table-bordered">
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
                </div>
                @if(count($downlines) > 0)
                <div class="table-responsive">
                    <div class="clearfix"></div>
                    <br>
                    <div>
                        <h4>Other Leaves</h4>
                        <div class="clearfix"></div>
                    </div>
                    <table data-path="other-medical-claims" class="DT table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">Status</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">User</th>
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
                @endif
            </div>
            <div class="col-md-3">
                @include('medicals.entitlementtable')
            </div>
        </div>
    </div>
</section>
@stop
