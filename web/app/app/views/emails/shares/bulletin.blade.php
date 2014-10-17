@extends('emails.layout')
@section('content')

<h2>Hello,</h2>
<br>
<p class="lead">A bulletin was shared with you</p>

<dl>
  <dt>{{$bulletin->title}}</dt>
  <dd>{{$bulletin->content}}</dd>
</dl>

@stop