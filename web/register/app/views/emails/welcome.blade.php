<h1>Welcome to CloudHRD!</h1>

<p class="lead">Thank you for registering {{$user->name}} to CloudHRD.</p>

@if(App::environment('local'))
<p>You can login with your admin credentials through <a href="http://{{$user->domain}}">{{$user->domain}}</a>.</p>
@else
<p>You can login with your admin credentials through <a href="https://{{$user->domain}}">{{$user->domain}}</a>.</p>
@endif
