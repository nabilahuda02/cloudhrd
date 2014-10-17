<div class="navbar hidden-print main navbar-default" role="navigation">
	<div class="row">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<h4 id="logo" href="{{ action('WallController@getIndex') }}" class="navbar-brand">
					CloudHRD | {{ $controller }}
				</h4>
			</div>
			<div class="navbar-collapse collapse navbar-right navbar-form search-container">
		        <div class="form-group">
		      		<input type="search" class="form-control input-sm" id="searchinput" placeholder="Search ...">
		        </div>
		        <button class="btn btn-blue" type="button"><i class="fa fa-search"></i></button>
		    	<div id="search-results"></div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<div class="row row-app margin-none">