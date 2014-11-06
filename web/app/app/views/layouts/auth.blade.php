<!DOCTYPE html>
<html lang="en">
<head>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,200|Open+Sans' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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