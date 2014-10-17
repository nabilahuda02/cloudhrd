@extends('emails.layout')
@section('content')

<h2>Hi {{ Helper::userName($recepient->id) }},</h2>
<br>
<p class="lead">This application needs your attention:</p>

@if($type === 'leave')
  @include('leaves.email-data')
@elseif($type === 'medical')
  @include('medicals.email-data')
@elseif($type === 'claims')
  @include('generalclaims.email-data')
@else
  @include('booking.email-data')
@endif

<br>
<p class="lead">The following actions are available for you:</p>
<br>
@foreach ($actions as $action)
    <a class="{{@$action['class']}}" href="{{$action['action']}}">{{$action['label']}}</a>
@endforeach

@stop