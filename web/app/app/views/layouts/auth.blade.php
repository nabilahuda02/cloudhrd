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
            <div class="col-md-4 col-md-offset-4 login-card">
              @include('html.notifications')
              @yield('login_heading')
              @yield('content')
              <div class="login-card-footer">
                  Login | Forget Password
              </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row login-logo-bottom">
            <div class="col logo">
                <small class="clearfix" style="color: #aeaeae">Powered by</small>
                <img src="/images/logo.png" alt="CloudHRD">
            </div>
        </div>
    </div>
    {{ Asset::tags('js') }}
    @yield('script')
</body>
</html>
