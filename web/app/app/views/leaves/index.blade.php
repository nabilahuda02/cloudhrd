@extends('layouts.module')
@section('content')
<section id="leaves">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h2>
                    Leaves
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_LEAVE.md', 'Leaves')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-6 section-drop-menu" >
                @include('leaves.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                <div class="table-responsive">
                    <table data-path="leaves" class="DT table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ref No</th>
                                <th class="text-center">Created</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Duration</th>
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
                    <table data-path="other-leaves" class="DT table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">Status</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Ref No</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Duration</th>
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
                @include('leaves.entitlementtable')
            </div>
        </div>
    </div>
</section>
@stop
