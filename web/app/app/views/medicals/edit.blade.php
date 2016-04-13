@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">

    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('medicals.menu')
            <h3>Edit Medical Claim Details</h3>
        </div>

        @include('medicals.entitlementtable')

        <div style="padding:15px;">
            {{ Former::horizontal_open(action('MedicalController@update', $medical->id))
                -> id('medicalForm')
                -> rules(['name' => 'required'])
                -> method('POST') }}
            {{ Former::hidden('_method', 'PUT') }}
            {{ Former::text('ref')
                -> label('Reference')
                -> value($medical->ref)
                -> readonly()
                -> disabled() }}
            {{ Former::text('user_id')
                -> label('Employee')
                -> value(User::fullName($medical->user_id))
                -> readonly() }}
            {{ Former::text('status_name')
                -> label('Status')
                -> value($medical->status->name)
                -> readonly()
                -> disabled() }}
            {{Former::populate($medical)}}

            @include('medicals.form')

            {{ Asset::push('js','app/upload.js')}}
            <div class="form-group">
                <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
                <div class="col-lg-10 col-sm-8">
                    <ul class="list-inline uploaded">
                        @foreach ($medical->uploads as $file)
                        <li class="view_uploaded" data-url="{{$file->file_url}}">
                            <button type="button" class="btn btn-primary remove_uploaded" data-id="{{$file->id}}">&times;</button>
                            <a href="{{$file->file_url}}" target="_blank">{{$file->file_name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="dates" class="control-label col-lg-2 col-sm-4">Upload<br/>(If Any)</label>
                <div class="col-lg-10 col-sm-8">
                    <div class="dropzone" id="upload" data-path="medicalclaim/{{$medical->upload_hash}}/{{$medical->id}}"  data-type="image/jpeg,image/png,application/pdf"></div>
                </div>
            </div>
            {{ Former::textarea('remarks')
            -> value($medical->remarks) }}
            <div class="form-group">
                <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                    @include('medicals.actions-buttons')
                    <input class="btn-large btn-primary btn pull-right click-once" type="submit" value="Submit">
                </div>
            </div>
            {{ Former::close() }}
        </div>
    </div>
</div>
@stop
@section('script')
@include('medicals.actions-scripts')
@stop
