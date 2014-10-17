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
    <div class="container login-container">
        <div class="col-sm-4 col-sm-offset-7">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    @yield('login_heading')
                </div>
                <div class="panel-body">
                    @include('partials.notification')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    {{Asset::tags('js')}}
    @yield('scripts')
</body>
</html>
