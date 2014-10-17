@extends('layouts.default')
@section('content')
<h2>My Profile</h2>
<hr>
<!-- Tab panes -->
    <table class="table table-striped table-bordered">
        <tr>
            <th width="200px" class="text-right">Name :</th>
            <td>{{$user->name}}</td>
        </tr>
        <tr>
            <th width="200px" class="text-right">Username :</th>
            <td>{{$user->username}}</td>
        </tr>
        <tr>
            <th width="200px" class="text-right">Email :</th>
            <td>{{$user->email}}</td>
        </tr>
        @if($user->isReseller())
            <tr>
                <th width="200px" class="text-right">Reseller Code :</th>
                <td>{{$user->reseller_code}}</td>
            </tr>
        @endif
    </table>
    <br>
    <div class="well">
        <a href="{{action('UsersController@getChangePassword')}}" class="btn btn-default">Change My Password</a>
        @if($user->isReseller())
            {{link_to_action('UsersController@resetResellerCode', 'Change Reseller Code', ['user_id' => Auth::user()->id], ['class' => 'btn btn-default'])}}
        @endif
    </div>
@stop