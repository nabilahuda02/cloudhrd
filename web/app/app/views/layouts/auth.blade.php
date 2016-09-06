<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>CloudHRD Sign In</title>
    {{ Asset::tags('css') }}
    @yield('style')
</head>
<body class="login">
    <div class="container">
        <div class="row login-logo-top">
            <div class="col logo">
                <img src="/images/logo.png" alt="SandsConsulting">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 login-card">
                @include('html.notifications')
                @yield('login_heading')
                @yield('content')
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row login-logo-bottom text-center">
            <a href="http://www.cloudhrd.com" class="col logo">
                <div class="text-center text-muted">Powered by</div>
                <img src="/images/logo.png" alt="CloudHRD">
            </a>
        </div>
    </div>
    {{ Asset::tags('js') }}
    @yield('script')
</body>
</html>
