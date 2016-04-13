@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <h3>{{ $medical->is_paid ? 'Paid' : 'Unpaid' }}</h3>
    </div>
    <div class="col-md-12">
        <div class="page-header">
            @include('medicals.menu')
            <h3>Medical Claim Details</h3>
        </div>
        <div style="padding:15px;">
            {{ Former::horizontal_open(action('MedicalController@store'))
            -> id('medicalForm')
            -> rules(['name' => 'required'])
            -> method('POST') }}
            {{ Former::text('created_at')
            -> label('Created At')
            -> value(Helper::timestamp($medical->created_at))
            -> readonly()
            -> disabled() }}
            {{ Former::text('ref')
            -> label('Reference')
            -> value($medical->ref)
            -> readonly()
            -> disabled() }}
            {{ Former::text('user_id')
            -> label('Employee')
            -> value(User::fullName($medical->user_id))
            -> readonly()
            -> disabled() }}
            {{ Former::text('status')
            -> label('Status')
            -> value($medical->status->name)
            -> readonly()
            -> disabled() }}
            {{Former::populate($medical)}}
            @include('medicals.form')
            {{ Former::textarea('remarks')
            -> value($medical->remarks) }}
            {{ Asset::push('js','app/upload.js')}}
            <div class="form-group">
                <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
                <div class="col-lg-10 col-sm-8">
                    <ul class="list-inline uploaded">
                        @foreach ($medical->uploads as $file)
                        <li class="view_uploaded" data-url="{{$file->file_url}}">
                            <a href="{{$file->file_url}}" target="_blank">{{$file->file_name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                    @include('medicals.actions-buttons')
                </div>
            </div>
            {{ Former::close() }}
        </div>
    </div>
</div>
@stop
@section('script')
@include('medicals.actions-scripts')
<script>
$('input:not([type=hidden],[type=search]),select,textarea').attr({
readonly: true,
disabled: true
});
$('#medicalForm').on('submit',function(e){
e.preventDefault();
return false;
});
</script>
@stop
