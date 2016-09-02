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
                {{ Former::select('leave_type_id')
                    -> label('Type')
                    -> placeholder('Choose')
                    -> options($types->lists('name', 'id') ,null)
                    ->required() }}
                <div id="type-first" class="hidden">
                    <div class="form-group">
                        <label for="" class="control-label col-lg-2 col-sm-4">Dates</label>
                        <div class="col-lg-5 col-sm-5" id="datepicker"></div>
                        <style id="datepicker-style"></style>
                    </div>
                    {{ Former::text('dates')
                        ->readonly()
                        ->required() }}
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
            past: {{($type->past) ? 'true' : 'false'}},
            color: "{{$type->colors}}",
        },
        @endforeach
    };
    var leaveType = {}
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
    var selected = [];
    $('#datepicker').on('dp.change', function(e){
        if(e.date) {
            var date = e.date.format('MM/DD/YYYY');
            if(selected.indexOf(date) > -1) {
                selected.splice(selected.indexOf(date), 1);
            } else {
                selected.push(date);
            }
            updateStyle();
            updateDates();
        }
        setTimeout(function() {
            $('#datepicker').data("DateTimePicker").date(null);
        });
    });
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
@stop
