@extends('layouts.module')
@section('style')
    <style>
        td {
            text-align: left!important
        }
    </style>
@stop
@section('content')
<div class="col-md-10 col-sm-8">
    <div class="row">
        <div class="col-md-12">
            @include('html.notifications')
            <div class="page-header">
                @include('admin.users.menu')
                <h3>Import Users</h3>
            </div>
            <h4>Available Fields</h4>
            <br>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Description</th>
                        <th>Example</th>
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
                        <td>Password</td>
                        <td>User's Password (6 or more characters)</td>
                        <td>&nbsp;</td>
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
            <a href="{{action('AdminUserController@getManageTemplate')}}" class="btn btn-default">Customize Fields</a>
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
@stop