@extends('emails.layout')
@section('content')

<h2>Hello,</h2>
<br>
<p class="lead">An Image was shared with you</p>
<img src="{{asset($bulletin->root_path . '/thumbnail.' . $bulletin->extension)}}" alt="">

<dl>
  <dt>{{$bulletin->title}}</dt>
  <dd>{{$bulletin->content}}</dd>
</dl>

@stop