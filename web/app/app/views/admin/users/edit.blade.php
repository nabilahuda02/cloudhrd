@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.users.menu')
            <h3>Edit {{User::fullName($currentuser->id)}}</h3>
        </div>
        {{ Former::vertical_open(action('AdminUserController@update', $currentuser->id))
        -> id('leaveForm')
        -> rules(['name' => 'required'])
        -> method('POST') }}
        {{ Former::hidden('_method', 'PUT') }}
        {{ Former::populate($currentuser) }}
        @include('admin.users.form')
        <div class="form-group">
            <button type="button" id="delete_user" class="btn-large btn-secondary btn pull-right">Delete</button>
            <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
        </div>
        {{ Former::close() }}
        <div class="clearfix"></div><br/><br/>
        {{ Former::open(action('AdminUserController@destroy', $currentuser->id))->id('delete_form') }}
        {{ Former::hidden('_method', 'DELETE') }}
        {{ Former::close() }}
        @include('profiles.admin')
    </div>
    <script>
    var user_profile_id = {{$currentuser->profile->id}};
    </script>
</div>
@stop
@section('script')
<script>
$('#delete_user').click(function(){
    bootbox.confirm('Are you sure you want to delete {{User::fullName($currentuser->id)}}?', function(res){
        if(res) {
            $('#delete_form').submit();
        }
    });
});
</script>
@stop