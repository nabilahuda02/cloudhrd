@extends('layouts.module')
@section('content')
<section id="generalclaim">
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
                @include('generalclaims.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                <div class="table-responsive">
                    <table data-path="general-claims" class="DT table table-striped table-bordered">
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
                @if(count($downlines) > 0)
                <div class="table-responsive">
                    <div class="clearfix"></div>
                    <br>
                    <div>
                        <h4>Other General Claims</h4>
                        <div class="clearfix"></div>
                    </div>
                    <table data-path="other-general-claims" class="DT table table-striped table-bordered">
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
        </div>
    </div>
</section>
@stop
