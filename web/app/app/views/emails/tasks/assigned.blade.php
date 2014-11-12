@extends('emails.layout')
@section('content')

<h2>Hi, {{$user->getFullName()}}</h2>
<br>
<p class="lead">A task was assigned to you:</p>

<dl>
  <dt>Title</dt>
  <dd>{{$task->description}}</dd>
  <dt>Tags</dt>
  <dd>
      <ul class="inline">
          @foreach($task->tags as $tag)
            <li class="label label-{{$tag->label}}">{{$tag->category->name}}: {{$tag->name}}</li>
          @endforeach
      </ul>
  </dd>
</dl>

@stop