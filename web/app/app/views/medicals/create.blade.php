@extends('layouts.module')
@section('content')
<section id="medical">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h2>
                    Create Medical Claim
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_LEAVE.md', 'Medical')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-6 section-drop-menu" >
                @include('medicals.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                {{ Former::horizontal_open(action('MedicalController@store'))
                    -> id('MyForm')
                    -> rules(['name' => 'required'])
                    -> method('POST') }}
                {{Former::hidden('noonce', Helper::noonce())}}
                @if(Auth::user()->administers(MedicalClaim__Main::$moduleId))
                {{ Former::select('user_id')
                    -> label('For User')
                    -> options(Helper::userArray(), null)
                    -> class('form-control col-md-4')
                    ->required() }}
                @endif
                @include('medicals.form')
                <div class="form-group">
                    <label for="upload" class="control-label col-lg-2 col-sm-4">Upload</label>
                    <div class="col-lg-10 col-sm-8">
                        <div class="dropzone" id="upload" data-path="medicalclaim/temp/{{ Helper::noonce() }}" data-type="image/jpeg,image/png,application/pdf"></div>
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
                @include('medicals.entitlementtable')
            </div>
        </div>
    </div>
</section>
{{Asset::push('js','upload')}}
@stop
