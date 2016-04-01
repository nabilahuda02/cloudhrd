@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.users.menu')
            <h3>User List</h3>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Email</th>
                    <th class="text-center">First Name</th>
                    <th class="text-center">Last Name</th>
                    <th class="text-center">Unit</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($users) > 0)
                @foreach($users as $i => $usr)
                <tr>
                    <td class="text-center">
                        {{ $usr->email }}
                        @if($usr->is_admin)
                        <i style="color:#fedb55" class="fa fa-star"></i>
                        @endif
                    </td>
                    <td class="text-center">{{ $usr->profile->first_name }}</td>
                    <td class="text-center">{{ $usr->profile->last_name }}</td>
                    <td class="text-center">{{ $usr->unit->name }}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-xs ">
                            <a href="{{action('AdminUserController@edit',array($usr->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                            <a href="{{action('AdminUserController@assume',array($usr->id))}}" class="btn btn-primary"><i class="fa fa-user"></i></a>
                            <a href="{{action('AdminUserController@getChangePassword',array($usr->id))}}" class="btn btn-primary"><i class="fa fa-lock"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
                @endif
            </tbody>
        </table>
        <hr>
    </div>
</div>
@stop
