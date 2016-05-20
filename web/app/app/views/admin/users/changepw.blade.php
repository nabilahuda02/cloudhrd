@extends('layouts.module')
@section('content')
<div class="col-md-12">
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.users.menu')
            <h3>Change {{User::fullName($currentuser->id)}}'s Password</h3>
        </div>
        <div style="padding:15px;">
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
@stop
