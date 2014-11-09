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
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
          ga('create', 'UA-56564848-2', 'auto');
          ga('send', 'pageview');
        </script>
    </body>
</html>
