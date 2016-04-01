@extends('emails.layout')
@section('content')

<h2>Hi {{ User::fullName($item->user_id) }},</h2>
<br>
<p class="lead">Your application was rejected:</p>

@if($type === 'leave')
  @include('leaves.email-data')
@elseif($type === 'medical')
  @include('medicals.email-data')
@elseif($type === 'claims')
  @include('generalclaims.email-data')
@elseif($type === 'change_request')
  @include('changerequests.email-data')
@else
  @include('booking.email-data')
@endif

@stop
