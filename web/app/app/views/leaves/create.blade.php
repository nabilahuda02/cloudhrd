@extends('layouts.module')
@section('content')

{{Asset::push('js', 'upload')}}

<section id="leaves">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h2>
                    Leaves Application Form
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_LEAVE.md', 'Leaves')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-6 section-drop-menu" >
                @include('leaves.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                {{ Former::horizontal_open(action('LeaveController@store'))
                    -> id('MyForm')
                    -> rules(['name' => 'required'])
                    -> method('POST') }}
                {{Former::hidden('noonce', Helper::noonce())}}
                @if(count(Auth::user()->getDownline(Leave__Main::$moduleId)) > 0)
                    {{ Former::select('user_id')
                        -> label('For User')
                        -> options(Helper::userArray(Auth::user()->getDownline(Leave__Main::$moduleId, true)), null)
                        -> class('form-control col-md-4')
                        ->required() }}
                @endif

                @include('leaves.form')

                <div class="form-group">
                    <label for="dates" class="control-label col-lg-2 col-sm-4">Upload</label>
                    <div class="col-lg-10 col-sm-8">
                        <div class="dropzone" id="upload" data-path="leave/temp/{{ Helper::noonce() }}" data-type="image/jpeg,image/png,application/pdf"></div>
                    </div>
                </div>

                {{ Former::textarea('remarks') }}

                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        <input class="btn-large btn-primary btn pull-right click-once" type="submit" value="Submit">
                    </div>
                </div>

                {{ Former::close() }}
            </div>
            <div class="col-md-3">
                @include('leaves.entitlementtable')
            </div>
        </div>
    </div>
</section>
@stop

@section('script')
	<script>
		var leaveTypes = {
			@foreach($types as $type)
			{{$type->id}} : {
				future: {{($type->future) ? 'true' : 'false'}},
				past: {{($type->past) ? 'true' : 'false'}}
			},
			@endforeach
		};
		var yyyymmdd = function(date) {
			var yyyy = date.getFullYear().toString();
			var mm = (date.getMonth()+1).toString();
			var dd  = date.getDate().toString();
			return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
		};
		var disabledData = {{ json_encode(Leave__BlockedDate::select(['date', 'name'])->lists('name', 'date')) }};
		var options = {
			altField: '#dates',
			dateFormat: app_locale.short_date,
			beforeShowDay: function(date){
				var disabled = disabledData[yyyymmdd(date)];
				if(disabled) {
					return [false, 'date-blocked', disabled];
				}
				return [true];
			},
		};
		$(document).on("mouseenter", ".date-blocked", function(){
			var target = $(this);
			if(!target.attr('title')) {
				var weekend = target.siblings('.ui-datepicker-week-end').first();
				var day = $('span', target).text();
				var month = weekend.data('month')+1;
				var date = weekend.data('year') + '-' + (month[1]?month:"0"+month) + '-' + (day[1]?day:"0"+day[0]);
				var disabled = disabledData[date];
				if(disabled) {
					target.tooltip({
						title: disabled,
						container: 'table'
					});
				}
			}

		});
		var dp = $("#datepicker").multiDatesPicker(options);
		$('#leave_type_id').change(function(){
			$('#dates').val('');
			dp.multiDatesPicker('resetDates', 'picked');
			var target = $(this);
			var current = leaveTypes[target.val()];
			if(current) {
				if(current.future === false) {
					options['maxDate'] = new Date;
				} else {
					options['maxDate'] = null;
				}
				if(current.past === false) {
					options['minDate'] = new Date;
				} else {
					options['minDate'] = null;
				}
				dp.datepicker('destroy');
				dp.multiDatesPicker(options);
			}
		})
	</script>
@stop
