@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
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
        {{ Former::text('created_at')
        -> label('Created At')
        -> value(Helper::timestamp($leave->created_at))
        -> readonly()
        -> disabled() }}
        {{ Former::text('ref')
        -> label('Reference')
        -> value($leave->ref)
        -> readonly()
        -> disabled() }}
        @if(Auth::user()->administers(Leave__Main::$moduleId))
        {{ Former::select('user_id')
        -> label('For User')
        -> options(Helper::userArray(), null)
        -> value($leave->user_id)
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
                        <a href="{{$file->file_url}}" target="_blank">{{$file->file_name}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{ Former::textarea('remarks')
        -> value($leave->remarks) }}
        <div class="form-group">
            <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                @include('leaves.actions-buttons')

            </div>
        </div>
        {{ Former::close() }}
    </div>
</div>
@stop
@section('script')
@include('leaves.actions-scripts')
<script>

$('input:not([type=hidden],[type=search]),select,textarea').attr({
readonly: true,
disabled: true
});
$('#leaveForm').on('submit',function(e){
e.preventDefault();
return false;
});
var dates = ["{{ implode('","',$leave->dates->lists('date'))}}"].map(function(d){
return new Date(d);
});
var dp = $("#datepicker").multiDatesPicker({
altField: '#dates',
dateFormat: app_locale.short_date,
disabled: true,
addDates: dates
});
</script>
@stop
