@extends('layouts.module')
@section('content')
<section id="users">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Create User
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button>
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
                {{ Former::vertical_open(action('AdminUserController@store'))
                    -> id('MyForm')
                    -> rules(['name' => 'required'])
                    -> method('POST') }}
                @include('admin.users.form')
                <div class="form-group">
                    <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
                </div>
                {{ Former::close() }}
            </div>
        </div>
    </div>
</section>
@stop
