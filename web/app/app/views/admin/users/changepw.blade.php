@extends('layouts.module')
@section('content')
<section id="users">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Change {{User::fullName($currentuser->id)}}'s Password
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
                {{ Former::vertical_open(action('AdminUserController@postChangePassword', $currentuser->id))
                    -> id('leaveForm')
                    -> rules(['name' => 'required'])
                    -> method('POST') }}
                {{ Former::text('User')
                    ->value(User::fullName($currentuser->id))
                    ->disabled()
                    ->readonly() }}
                {{ Former::password('password')
                    ->required() }}
                {{ Former::password('confirm_password')
                    ->label('Confirm Password')
                    ->required() }}
                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
                    </div>
                </div>
                {{ Former::close() }}
            </div>
        </div>
    </div>
</section>
@stop
