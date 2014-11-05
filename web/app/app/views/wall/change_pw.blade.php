@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="row">
        <div class="col-md-12">
            <div>
                <h2>
                    <a href="/wall/profile" class="btn btn-small btn-primary pull-right"><i class="fa fa-undo"></i> Back</a>
                    Change Password
                </h2>
                <div class="clearfix"></div>
                <hr>
            </div>
        </div>
        <div class="col-md-12">
            {{ Former::vertical_open('/wall/change-password')
                -> method('POST') }}
            {{ Former::password('password')
                ->label('New Password')
                ->required() }}
            {{ Former::password('confirm_password')
                ->label('Confirm New Password')
                ->required() }}
            <div class="form-group">
                <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
            </div>
            <div class="clearfix"></div>
            {{ Former::close() }}
        </div>
    </div>
</div>
@stop