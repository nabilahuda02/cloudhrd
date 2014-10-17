@extends('layouts.default')
@section('content')
    <h2>
        @if($user->confirmed === 'Active')
            <span class="pull-right label label-lg label-success">
        @else
            <span class="pull-right label label-lg label-warning">
        @endif
        {{$user->confirmed}}</span>
        Edit User
    </h2>
    <hr>
    {{ Former::open(action('users.update', $user->id)) }}
        {{Former::populate($user)}}
        {{Former::hidden('_method', 'PUT')}}
        @include('users.form')
        @include('users.actions-footer', ['has_submit' => true])
@stop
