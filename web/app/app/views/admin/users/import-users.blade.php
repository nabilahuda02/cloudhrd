@extends('layouts.module')
@section('style')
    <style>
        td {
            text-align: left!important
        }
    </style>
@stop
@section('content')
<section id="users">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Import Users
                    {{-- <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button> --}}
                </h2>
            </div>
            <div class="col-sm-6 section-drop-menu" >
                @include('admin.users.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                <h3>Available Fields</h3>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Description</th>
                            <th>Example / Values</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Email Address</td>
                            <td>User's Email Address</td>
                            <td>user@example.com</td>
                        </tr>
                        <tr>
                            <td>First Name</td>
                            <td>User's First Name</td>
                            <td>John</td>
                        </tr>
                        <tr>
                            <td>Last Name</td>
                            <td>User's Last Name</td>
                            <td>Doe</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>User's Address</td>
                            <td>B3227, Torrington, Devon EX38 8ND, UK</td>
                        </tr>
                        <tr>
                            <td>Unit</td>
                            <td>User's Unit Name (Must be created first)</td>
                            <td>Software Development</td>
                        </tr>
                        <tr>
                            <td>Is Admin</td>
                            <td>Whether User Is Admin (Yes / No)</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Bank Name</td>
                            <td>Bank Branch Name</td>
                            <td>CIMB Tronoh</td>
                        </tr>
                        <tr>
                            <td>Bank Account</td>
                            <td>Bank Account Number</td>
                            <td>192837283</td>
                        </tr>
                        <tr>
                            <td>EPF Account</td>
                            <td>EPF Account Number</td>
                            <td>233212</td>
                        </tr>
                        <tr>
                            <td>EPF Employee Contrubution</td>
                            <td>EPF Employee Contrubution in percentage</td>
                            <td>11</td>
                        </tr>
                        <tr>
                            <td>EPF Employer Contrubution</td>
                            <td>EPF Employer Contrubution in percentage</td>
                            <td>12</td>
                        </tr>
                        <tr>
                            <td>Income Tax Account Number</td>
                            <td>Employee Income Tax Account Number</td>
                            <td>12235533</td>
                        </tr>
                        <tr>
                            <td>PCB Contribution Value</td>
                            <td>PCB Contribution in Monetary Value</td>
                            <td>550.00</td>
                        </tr>
                        <tr>
                            <td>SOCSO Account</td>
                            <td>SOCSO Account Number</td>
                            <td>840231-02-1122</td>
                        </tr>
                        <tr>
                            <td>SOCSO Employee Contribution</td>
                            <td>SOCSO Employee Contribution in Monetary Value</td>
                            <td>23.00</td>
                        </tr>
                        <tr>
                            <td>SOCSO Employer Contribution</td>
                            <td>SOCSO Employer Contribution in Monetary Value</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>Salary</td>
                            <td>Salary in Monetary Value</td>
                            <td>5000.00</td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td>Position Held in Company</td>
                            <td>Developer</td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>User's Gender</td>
                            <td>One of: Male or Female</td>
                        </tr>
                        <tr>
                            <td>Employment Type</td>
                            <td>User's Employment Type</td>
                            <td>One of: Permanent, Contract or Internship</td>
                        </tr>
                        @foreach($custom_fields as $field)
                            @if($field)
                                <tr>
                                    <td>{{$field}}</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <a href="{{action('AdminUserController@getDownloadTemplate')}}" class="btn btn-primary">Download Template</a>
                <hr>
                <br>
                {{ Former::vertical_for_files_open(action('AdminUserController@postImportUsers')) }}
                {{ Former::file('userimport')
                    ->required()
                    ->label('File')
                    ->accept('xlsx')
                    ->max(8, 'MB') }}
                <div class="form-group">
                    <input class="btn-large btn-primary btn" type="submit" value="Upload File">
                </div>
                {{ Former::close() }}
            </div>
        </div>
    </div>
</section>
@stop
