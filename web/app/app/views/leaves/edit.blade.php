@extends('layouts.module')
@section('content')
<section id="leaves">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h2>
                    Leave Application Form: {{$leave->ref}}
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
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                {{ Former::horizontal_open(action('LeaveController@update', $leave->id))
                    -> id('leaveForm')
                    -> rules(['name' => 'required'])
                    -> method('POST') }}
                {{ Former::hidden('_method', 'PUT') }}
                {{ Former::text('ref')
                    -> label('Reference')
                    -> value($leave->ref)
                    -> readonly()
                    -> disabled() }}
                {{ Former::text('user_name')
                    -> label('Employee')
                    -> value(User::fullName($leave->user_id))
                    -> readonly() }}
                {{ Former::select('leave_type_id')
                    -> label('Type')
                    -> placeholder('Choose')
                    -> options($types->lists('name', 'id'), $leave->leave_type_id)
                    ->required() }}
                {{ Former::text('status_name')
                    -> label('Status')
                    -> value($leave->status->name)
                    -> readonly()
                    -> disabled() }}
                {{Former::populate($leave)}}
                <div class="form-group">
                    <label for="" class="control-label col-lg-2 col-sm-4">Dates</label>
                    <div class="col-lg-5 col-sm-5" id="datepicker"></div>
                    <style id="datepicker-style"></style>
                </div>
                <div class="form-group required">
                    <label for="dates" class="control-label col-lg-2 col-sm-4">Dates <sup>*</sup></label>
                    <div class="col-lg-10 col-sm-8">
                        <input class="form-control" type="text" readonly required id="dates" name="dates" value="{{implode(', ', Helper::mysqls_to_short_dates($leave->dates->lists('date')))}}">
                    </div>
                </div>
                {{ Asset::push('js','upload')}}
                @if($leave->uploads()->count() > 0)
                    <div class="form-group">
                        <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
                        <div class="col-lg-10 col-sm-8">
                            <ul class="list-inline uploaded">
                                @foreach ($leave->uploads as $file)
                                <li class="view_uploaded" data-url="{{$file->file_url}}">
                                    <button type="button" class="btn btn-primary remove_uploaded" data-id="{{$file->id}}">&times;</button>
                                    <a href="{{$file->file_url}}" target="_blank">{{$file->file_name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label for="dates" class="control-label col-lg-2 col-sm-4">Upload<br/>(If Any)</label>
                    <div class="col-lg-10 col-sm-8">
                        <div class="dropzone" id="upload" data-path="leave/{{$leave->upload_hash}}/{{$leave->id}}" data-type="image/jpeg,image/png,application/pdf"></div>
                    </div>
                </div>
                {{ Former::textarea('remarks')
                    -> value($leave->remarks) }}
                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        @include('leaves.actions-buttons')
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
</div>
@stop
@section('script')
@include('leaves.actions-scripts')
<script>
    var leaveTypes = {
        @foreach($types as $type)
        {{$type->id}} : {
            future: {{($type->future) ? 'true' : 'false'}},
            past: {{($type->past) ? 'true' : 'false'}},
            color: "{{$type->colors}}",
        },
        @endforeach
    };
    var leaveType = leaveTypes[{{$leave->leave_type_id}}];
    var disabledData = {{ json_encode(Leave__BlockedDate::select(['date', 'name'])->lists('name', 'date')) }};
    var daysOfWeekDisabled = [];
    for(var i = 0; i <= 6; i++) {
        if(app_locale.working_days[i] === '0') {
            daysOfWeekDisabled.push(i);
        }
    }
    $('#datepicker').datetimepicker({
        inline: true,
        useCurrent: false,
        format: app_locale.short_date,
        disabledDates: Array.prototype.slice.call(Object.keys(disabledData)),
        daysOfWeekDisabled: daysOfWeekDisabled,
    });
    var selected = $('#dates').val().split(', ').map(function(date) {
        return moment(date, app_locale.short_date).format('MM/DD/YYYY');
    });
    $('#datepicker').on('dp.change', function(e){
        if(e.date) {
            var date = e.date.format('MM/DD/YYYY');
            if(selected.indexOf(date) > -1) {
                selected.splice(selected.indexOf(date), 1);
            } else {
                selected.push(date);
            }
        }
        updateStyle();
        updateDates();
        setTimeout(function() {
            $('#datepicker').data("DateTimePicker").date(null);
        });
    }).trigger('dp.change', {});
    function updateDates() {
        var str = selected.map(function(date){
            return moment(date, 'MM/DD/YYYY').format(app_locale.short_date);
        }).join(', ');
        $('#dates').val(str);
    }
    function updateStyle() {
        $('#datepicker-style').text('');
        var style = '';
        selected.forEach(function(select){
            style += '[data-day="' + select + '"]{background-color:' + leaveType.color + '; color:white}[data-day="' + select + '"]:hover{background-color:' + leaveType.color + '!important; color:white}'
        });
        $('#datepicker-style').text(style);
    }
    function create(config) {
        selected = [];
        updateStyle();
        updateDates();
        var options = {
            minDate: false,
            maxDate: false,
        };
        if(!config.future) {
            options.maxDate = moment();
        }
        if(!config.past) {
            options.minDate = moment();
        }
        $('#datepicker')
            .data("DateTimePicker")
            .options(options);
    }
    $('#leave_type_id').change(function(){
        if(leaveTypes[this.value]) {
            $('#type-first').removeClass('hidden');
            leaveType = leaveTypes[this.value];
            create(leaveTypes[this.value]);
        } else {
            $('#type-first').addClass('hidden');
        }
    });
</script>


<script>
    // var leaveTypes = {
    //     @foreach($types as $type)
    //     {{$type->id}} : {
    //         future: {{($type->future) ? 'true' : 'false'}},
    //         past: {{($type->past) ? 'true' : 'false'}}
    //     },
    //     @endforeach
    // };

    // var yyyymmdd = function(date) {
    //     var yyyy = date.getFullYear().toString();
    //     var mm = (date.getMonth()+1).toString();
    //     var dd  = date.getDate().toString();
    //     return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
    // };
    // var dates = ["{{ implode('","',$leave->dates->lists('date'))}}"].map(function(d){
    //     return new Date(d);
    // });
    // var disabledData = {{ json_encode(Leave__BlockedDate::select(['date', 'name'])->lists('name', 'date')) }};
    // var options = {
    //     altField: '#dates',
    //     dateFormat: app_locale.short_date,
    //     addDates: dates,
    //     beforeShowDay: function(date){
    //         var disabled = disabledData[yyyymmdd(date)];
    //         if(disabled) {
    //             return [false, 'date-blocked', disabled];
    //         }
    //         return [true];
    //     },
    // };
    // $(document).on("mouseenter", ".date-blocked", function(){
    //     var target = $(this);
    //     if(!target.attr('title')) {
    //         var weekend = target.siblings('.ui-datepicker-week-end').first();
    //         var day = $('span', target).text();
    //         var month = weekend.data('month')+1;
    //         var date = weekend.data('year') + '-' + (month[1]?month:"0"+month) + '-' + (day[1]?day:"0"+day[0]);
    //         var disabled = disabledData[date];
    //         if(disabled) {
    //             target.tooltip({
    //                 title: disabled,
    //                 container: 'table'
    //             });
    //         }
    //     }
    // });
    // var dp = $("#datepicker").multiDatesPicker(options);
    // $('#leave_type_id').change(function(){
    //     $('#dates').val('');
    //     dp.multiDatesPicker('resetDates', 'picked');
    //     var target = $(this);
    //     var current = leaveTypes[target.val()];
    //     if(current) {
    //         if(current.future === false) {
    //             options['maxDate'] = new Date;
    //         } else {
    //             options['maxDate'] = null;
    //         }
    //         if(current.past === false) {
    //             options['minDate'] = new Date;
    //         } else {
    //             options['minDate'] = null;
    //         }
    //         dp.datepicker('destroy');
    //         dp.multiDatesPicker(options);
    //     }
    // });
    // setTimeout(function() {
    //     $('#leave_type_id').trigger('change');
    // }, 10);
</script>
@stop
