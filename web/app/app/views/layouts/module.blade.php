<!DOCTYPE html>
<html>
    <head>
        <link href='//fonts.googleapis.com/css?family=Raleway:400,200|Open+Sans' rel='stylesheet' type='text/css'>
        <link href='//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('html.head')
        <title>
            CloudHRD | {{ $controller }}
        </title>
        {{ Asset::tags('css') }}
        @yield('style')
        @if($locale = app()->user_locale)
        <script>
            var app_locale = {{json_encode($locale)}};
            app_locale.long_date = app_locale.long_date.split('__').shift();
            app_locale.short_date = app_locale.short_date.split('__').shift();
            function currency_format(number) {
                var decimals = {{$locale->decimal_places}};
                var dec_point = '{{$locale->decimal_separator}}';
                var thousands_sep = '{{$locale->thousand_separator}}';
                number = (number + '')
                .replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + (Math.round(n * k) / k)
                    .toFixed(prec);
                };
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                .split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '')
                    .length < prec) {
                    s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1)
                .join('0');
            }
            @if($locale->symbol_location === 'before')
                return '{{$locale->currency_symbol}} ' +  s.join(dec);
            @else
                return s.join(dec) +  ' {{$locale->currency_symbol}}';
            @endif
        }
        </script>
        @endif
        @yield('script-head')
    </head>
    <body>
        @include('html.header')
        <div class="container">
            <div class="row">
                {{--
                @if(!isset($hideSidebar))
                    @include('html.sidebar')
                @endif
                --}}
                @yield('content')
                @include('html.footer')
            </div>
        </div>
        <div id="help-modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="help-title"></h4>
                    </div>
                    <div class="modal-body" id="help-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pagedown/1.0/Markdown.Converter.min.js"></script>
        {{ Asset::tags('js') }}
        @yield('script')
    </body>
</html>
