<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @yield('meta')
    <title>CloudHRD Registration</title>
    {{Asset::tags('css')}}
    @yield('styles')
</head>
<body class="login">
    <div class="container">
        <div class="row login-logo">
            <div class="col logo">
                <h1><img src="/images/logo.png" alt="CloudHRD"></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 login-card">
                @yield('login_heading')
                @include('partials.notification')
                @yield('content')
                <div class="login-card-footer">
                    {{link_to_action('AuthController@login', 'Login')}} | 
                    {{link_to_action('AuthController@forgotPassword', 'Forgot Password')}}
                </div>
            </div>
        </div>

    </div>
    {{Asset::tags('js')}}
    @yield('scripts')
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-56564848-2', 'auto');
      ga('send', 'pageview');
      ga('send', 'event', 'signup');
    </script>
</body>
</html>
