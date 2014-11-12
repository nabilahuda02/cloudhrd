@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('leaves.menu')
            <h3>Leave Application Form</h3>
        </div>
        @include('leaves.entitlementtable')

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
        
        @if(Auth::user()->administers(Leave__Main::$moduleId))
        {{ Former::select('user_id')
        -> label('For User')
        -> options(Helper::userArray(), null)
        -> value($leave->ref)
        -> class('form-control col-md-4')
        -> required() }}
        @endif
        {{ Former::text('status_name')
        -> label('Status')
        -> value($leave->status->name)
        -> readonly()
        -> disabled() }}
        {{Former::populate($leave)}}
        @include('leaves.form')
        
        {{ Asset::push('js','app/upload.js')}}
        <div class="form-group">
            <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
            <div class="col-lg-10 col-sm-8">
                <ul class="list-inline uploaded">
                    @foreach ($leave->uploads as $file)
                    <li class="view_uploaded" data-url="{{$file->file_url}}">
                        <button type="button" class="btn btn-primary remove_uploaded" data-id="{{$file->id}}">&times;</button>
                        <img src="{{ $file->thumb_url }}" alt="" class="thumbnail">
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="form-group">
            <label for="dates" class="control-label col-lg-2 col-sm-4">Upload<br/>(If Any)</label>
            <div class="col-lg-10 col-sm-8">
                <div class="dropzone" id="upload" data-path="leave/{{$leave->upload_hash}}/{{$leave->id}}"></div>
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
</div>
@stop
@section('script')
@include('leaves.actions-scripts')
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
    var dates = ["{{ implode('","',$leave->dates->lists('date'))}}"].map(function(d){
        return new Date(d);
    });
    var disabledData = {{ json_encode(Leave__BlockedDate::select(['date', 'name'])->lists('name', 'date')) }};
    var options = {
        altField: '#dates',
        dateFormat: app_locale.short_date,
        addDates: dates,
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
    });
    setTimeout(function() {
        $('#leave_type_id').trigger('change');
    }, 10);
</script>
@stop