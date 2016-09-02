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
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                {{ Former::horizontal_open(action('LeaveController@update', $leave->id))
                    ->id('leaveForm')
                    ->rules(['name' => 'required'])
                    ->method('POST') }}
                {{ Former::hidden('_method', 'PUT') }}
                {{ Former::text('created_at')
                    ->label('Created At')
                    ->value(Helper::timestamp($leave->created_at))
                    ->readonly()
                    ->disabled() }}
                {{ Former::text('ref')
                    ->label('Reference')
                    ->value($leave->ref)
                    ->readonly()
                    ->disabled() }}
                @if(Auth::user()->administers(Leave__Main::$moduleId))
                {{ Former::select('user_id')
                    ->label('For User')
                    ->options(Helper::userArray(), null)
                    ->value($leave->user_id)
                    ->class('form-control col-md-4')
                    ->required() }}
                @endif
                {{ Former::select('leave_type_id')
                    -> label('Type')
                    -> placeholder('Choose')
                    -> options($types->lists('name', 'id'), $leave->leave_type_id)
                    ->required() }}
                {{ Former::text('status_name')
                    ->label('Status')
                    ->value($leave->status->name)
                    ->readonly()
                    ->disabled() }}
                {{Former::populate($leave)}}
                <div class="form-group">
                    <label for="" class="control-label col-lg-2 col-sm-4">Dates</label>
                    <div class="col-lg-5 col-sm-5" id="datepicker"></div>
                    <style id="datepicker-style"></style>
                </div>
                <div class="form-group required">
                    <label for="dates" class="control-label col-lg-2 col-sm-4">Dates <sup>*</sup></label>
                    <div class="col-lg-10 col-sm-8">
                        <input class="form-control" type="text" readonly disabled name="dates" value="{{implode(', ', Helper::mysqls_to_short_dates($leave->dates->lists('date')))}}">
                    </div>
                </div>
                @if($leave->uploads()->count() > 0)
                    {{ Asset::push('js','upload')}}
                    <div class="form-group">
                        <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
                        <div class="col-lg-10 col-sm-8">
                            <ul class="list-inline uploaded">
                                @foreach ($leave->uploads as $file)
                                <li class="view_uploaded" data-url="{{$file->file_url}}">
                                    <a href="{{$file->file_url}}" target="_blank">{{$file->file_name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                {{ Former::textarea('remarks')
                    ->value($leave->remarks) }}
                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        @include('leaves.actions-buttons')
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
    var leaveType = {{json_encode($leave->type)}};
    $('input:not([type=hidden],[type=search]),select,textarea').attr({
        readonly: true,
        disabled: true
    });
    $('#leaveForm').on('submit',function(e){
        e.preventDefault();
        return false;
    });
    $('#datepicker').datetimepicker({
        inline: true,
        useCurrent: false,
        defaultDate: moment("{{$leave->dates()->first()->date}}"),
        format: app_locale.short_date,
    });
    $('#datepicker').data("DateTimePicker").date(null);
    $('#datepicker').on('dp.change', function(e){
        $('#datepicker').data("DateTimePicker").date(null);
    });
    $('#datepicker-style').text('');
    var style = '';
    var dates = ["{{ implode('","',$leave->dates->lists('date'))}}"].map(function(d){
        var select = moment(d).format('MM/DD/YYYY');
        style += '[data-day="' + select + '"]{background-color:' + leaveType.colors + '; color:white}[data-day="' + select + '"]:hover{background-color:' + leaveType.colors + '!important; color:white}';
    });
    $('#datepicker-style').text(style);
</script>
@stop
