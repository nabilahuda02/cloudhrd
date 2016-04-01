@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.users.menu')
            <h3>Create New User</h3>
        </div>
        {{ Former::vertical_open(action('AdminUserController@store'))
        -> id('MyForm')
        -> rules(['name' => 'required'])
        -> method('POST') }}
        @include('admin.users.form')
        <div class="form-group">
            <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
        </div>
        {{ Former::close() }}
        <div class="clearfix"></div><br/><br/>
    </div>
</div>
@stop
