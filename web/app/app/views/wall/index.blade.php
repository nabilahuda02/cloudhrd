@extends('layouts.module')
@section('content')
{{Asset::push('js','app/wall.new.js')}}
<div class="col-md-10 col-sm-8" id="wall">
	<div class="row">
		<div class="col-sm-12 col-md-9">
			<div id="new-share">
				<textarea placeholder="Write something on the public wall and press shift + enter to submit"></textarea>
				<div class="new-share-actions">
					<div class="btn-group pull-right">
						<button class="btn btn-primary btn-sm" id="submit">Submit</button>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<br>
			<div id="feeds">
				<div class="inner"></div>
			</div>
			<br>
		</div>
		<div class="col-sm-3 hidden-sm hidden-xs">
			<div class="panel panel-primary text-center">
				<div class="panel-heading">October 2014</div>
				<div class="panel-body">
					<br>
					<br>
					<br>
					Calendar Content
					<br>
					<br>
					<br>
				</div>
				<div class="panel-footer">
					View Bigger Calendar
				</div>
			</div>
			<div class="panel panel-primary text-center">
				<div class="panel-heading">Todos</div>
				<div class="panel-body">
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					Todos Content
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
				</div>
				<div class="panel-footer">
					View 23 More
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="feeds-template">
	@include('wall.template')
</script>
@stop