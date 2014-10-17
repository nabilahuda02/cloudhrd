@extends('layouts.default')
@section('content')
    <h2>
        @if($user->confirmed === 'Active')
            <span class="pull-right label label-lg label-success">
        @else
            <span class="pull-right label label-lg label-warning">
        @endif
        {{$user->confirmed}}</span>
        View User
    </h2>
    <hr>
    {{ Former::open() }}
        {{Former::populate($user)}}
        @include('users.form')
        @if($user->isReseller())
            <div class="form-group required">
                <label for="" class="control-label col-lg-2 col-sm-2">Reseller Code</label>
                <div class="col-lg-10 col-sm-10">
                    <input type="text" class="form-control" disabled value="{{$user->reseller_code}}">
                </div>
            </div>
        @endif
        @include('users.actions-footer')
@stop
