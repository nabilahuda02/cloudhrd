<!DOCTYPE html>
<html>
	<head>
		@include('html.head')
		<title>
			CloudHRD | {{ $controller }}
		</title>
    	{{{ Asset::tags('css') }}}
		@yield('style')
		@if($locale = app()->user_locale)
        <script>
        	var app_locale = {{json_encode($locale)}};
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
	</head>
	<body>
		@include('html.header')
		<div class="container">
			<div class="row">
				@if(!isset($hideSidebar))
					@include('html.sidebar')
				@endif
				@yield('content')
				@include('html.footer')
			    {{{ Asset::tags('js') }}}
				@yield('script')
			</div>
		</div>
	</body>
</html>