<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CloudHRD</title>
    {{{ Asset::tags('css') }}}
    @yield('style')
</head>
<body>
    @yield('content')
    {{{ Asset::tags('js') }}}
    @yield('script')
</body>
</html>