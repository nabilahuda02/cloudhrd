<!DOCTYPE html>
<html>
	<head>
		@include('html.head')
		<title>
			CloudHRD | {{ $controller }}
		</title>
    	{{{ Asset::tags('css') }}}
		@yield('style')
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