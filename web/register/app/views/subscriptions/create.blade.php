@extends('layouts.default')
@section('content')
    <h2>New Subscription</h2>
    <hr>
    {{ Former::open(action('subscriptions.store')) }}
    @include('subscriptions.form')
    @include('subscriptions.actions-footer', ['has_submit' => true])
@stop