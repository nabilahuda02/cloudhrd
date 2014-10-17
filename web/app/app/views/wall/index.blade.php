@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
	<!-- <div class="row">
		@foreach (Leave__Type::where('display_wall',1)->get() as $leaveType)
			<div class="col-sm-3">
				<div class=>
					<div class="widget margin-none donut-charts">
						<div data-path="leave/{{$leaveType->id}}" class="widget-body" id="leave_{{$leaveType->id}}">
						</div>
						<div class="chart-footer">
							<div class="chart-title">{{$leaveType->name}}</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		@foreach (MedicalClaim__Type::where('display_wall',1)->get() as $medicalClaimType)
			<div class="col-sm-3">
				<div class=>
					<div class="widget margin-none donut-charts">
						<div data-path="medical/{{$medicalClaimType->id}}" class="widget-body" id="medical_{{$medicalClaimType->id}}">
						</div>
						<div class="chart-footer">
							<div class="chart-title">{{$medicalClaimType->name}}</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div> -->
	<div class="clearfix"></div>
	<div class="col-separator-h"></div>
	<div class="clearfix"></div>
	<ul id="newTabsLi" class="nav nav-tabs">
		<li class="active"><a href="#bulletin" data-toggle="tab">Bulletin</a></li>
		<li><a href="#profile" data-toggle="tab">My Details</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="bulletin">
			<div class="col-table-row">
				<div class="widget widget-tabs widget-tabs-icons-only-2 widget-activity margin-none widget-activity-social">
					<div class="widget-body padding-none">
						<div class="tab-content">
							<div class="tab-pane active" id="shareTab">
								<div class="share">
									{{ Former::open(action('SharesController@store')) }}
										{{ Former::hidden('type', 'bulletin') }}
										{{ Former::text('title')
												->required()
												->maxlength(30) }}
										<textarea name="content" id="sharetext" class="minHieght120 form-control" rows="2" placeholder="Share to bulletin..."></textarea>
										<div class="textRight">
											{{ Former::submit()
												-> class('btn btn-primary btn-blue') }}
										</div>
									{{ Former::close() }}
								</div>
							</div>
							<div class="tab-pane" id="pictureTab">
								<div class="share">
									{{ Former::open(action('SharesController@store'))
											-> enctype('multipart/form-data') }}
										{{ Former::hidden('type', 'image') }}
										{{ Former::file('imageinput')
											->label('Share Image')
											->accept('image/jpeg', 'image/png') }}
										{{ Former::text('title')
												->required()
												->maxlength(30) }}
										<textarea name="content" id="shareimage" class="minHieght120 form-control" rows="2" placeholder="Choose an image to share..."></textarea>
										<div class="textRight">
											{{ Former::submit()
												-> class('btn btn-primary btn-blue')
												-> id('shareBulletin') }}
										</div>
									{{ Former::close() }}
								</div>
							</div>
							<div class="tab-pane innerAll" id="pictureTab">Picture</div>
							<div class="tab-pane" id="linkTab">
								<div class="share">
									<textarea class="form-control" rows="2" placeholder="Share a link ..."></textarea>
									<a class="btn btn-default">Share <i class="text-primary fa fa-arrow-circle-o-right"></i></a>
								</div>
							</div>
							<div class="tab-pane innerAll" id="addEvent">
								<div class="share">
									{{ Former::open(action('SharesController@store')) }}
										<div class="row">
											{{ Former::hidden('type', 'event') }}
											{{ Former::date('event_date')
													->min(date('Y-m-d'))
													->value(date('Y-m-d')) }}
											{{ Former::text('title')
													->required()
													->maxlength(30) }}
											<textarea name="content" class="form-control" rows="2" placeholder="Event Description..."></textarea>
										</div>
										<div class="textRight">
											{{ Former::submit()
												-> class('btn btn-primary btn-blue') }}
										</div>
									{{ Former::close() }}
								</div>
							</div>
						</div>
					</div>
					<div class="widget-head">
						<ul>
							<li class="active"><a data-toggle="tab" href="#shareTab"><i class="glyphicon glyphicon-user"></i></a></li>
							<li><a data-toggle="tab" href="#pictureTab"><i class="glyphicon glyphicon-picture"></i></a></li>
							<li><a data-toggle="tab" href="#addEvent"><i class="glyphicon glyphicon-calendar"></i></a></li>
						</ul>
						<div class="myclear"></div>
					</div>
				</div>
				<div class="col-app col-unscrollable">
					<div class="col-app">
						<ul class="timeline-activity list-unstyled">
							@if($pinneds->count() === 0)
								<li class="no-border">
									No Pinned bulletin.
								</li>
							@else
								<h3 style="margin-left: 30px; padding-top: 20px">Pinned</h3>
							@endif
							@foreach($pinneds as $bulletin)
								@include('wall.bulletin-li')
							@endforeach
						</ul>
						<br>
						<hr>
						<ul class="timeline-activity list-unstyled">
							@if($bulletins->count() === 0)
								<li class="no-border">
									No Bulletins found. Add some from above.
								</li>
							@endif
							@foreach($bulletins as $bulletin)
								@include('wall.bulletin-li')
							@endforeach
						</ul>
						<br>
						<hr>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane form-inside" id="profile">
			@include('profiles.form')
		</div>
	</div>
</div>
@stop

@section('script')
	{{Asset::push('js','app/imagebulletin.js')}}
	{{Asset::push('js','app/wall.js')}}
@stop