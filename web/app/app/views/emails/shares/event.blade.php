@extends('emails.layout')
@section('content')

<h2>Hello,</h2>
<br>
<p class="lead">An event was shared with you</p>

<dl>
  <dt>{{$bulletin->title}}</dt>
  <dd>{{$bulletin->content}}</dd>
  <dd>{{$bulletin->event_date}}</dd>
</dl>

@stop