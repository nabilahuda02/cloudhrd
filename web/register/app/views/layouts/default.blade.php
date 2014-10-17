<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="_token" content="{{csrf_token()}}">
        @yield('meta')
        <title>CloudHRD Registration</title>
        {{Asset::tags('css')}}
        @yield('styles')
    </head>
    <body>
        <div class="container">
            <br>
            <div class="row">
                <div class="col-sm-2">
                    @include('partials.menu')
                </div>
                <div class="col-sm-10">
                    @include('partials.notification')
                    @yield('content')
                </div>
            </div>
        </div>
        {{Asset::tags('js')}}
        @yield('scripts')
    </body>
</html>
